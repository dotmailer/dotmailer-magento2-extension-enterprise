<?php

namespace Dotdigitalgroup\Enterprise\Model\Form;

use Dotdigitalgroup\Enterprise\Api\Data\FormDataInterface;
use Dotdigitalgroup\Enterprise\Api\Data\FormDataInterfaceFactory;
use Dotdigitalgroup\Enterprise\Api\Data\FormOptionInterfaceFactory;
use Dotdigitalgroup\Enterprise\Api\FormManagementInterface;
use Dotdigitalgroup\Email\Helper\Data;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Store\Model\StoreManagerInterface;

class FormManagement implements FormManagementInterface
{
    public const EMBEDDED_URL = '/resources/sharing/embed.js';
    public const POPOVER_URL = '/resources/sharing/popover.js';
    public const FORM_SHARING_EMBED = 'lp-embed';
    public const FORM_SHARING_POPOVER = 'lp-popover';

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var FormOptionInterfaceFactory
     */
    private $formOptionFactory;

    /**
     * @var FormDataInterfaceFactory
     */
    private $formDataFactory;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Data $data
     * @param StoreManagerInterface $storeManager
     * @param FormOptionInterfaceFactory $formOptionFactory
     * @param FormDataInterfaceFactory $formDataFactory
     * @param Request $request
     */
    public function __construct(
        Data $data,
        StoreManagerInterface $storeManager,
        FormOptionInterfaceFactory $formOptionFactory,
        FormDataInterfaceFactory $formDataFactory,
        Request $request
    ) {
        $this->helper = $data;
        $this->storeManager = $storeManager;
        $this->formOptionFactory = $formOptionFactory;
        $this->formDataFactory = $formDataFactory;
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function getFormOptions($websiteId)
    {
        $forms = [];

        if (!$this->helper->isEnabled($websiteId)) {
            return [];
        }

        $client = $this->helper->getWebsiteApiClient($websiteId);

        if ($ECForms = $client->getSurveysAndForms()) {
            /** @var \stdClass $ECForm */
            foreach ($ECForms as $ECForm) {
                if (isset($ECForm->id) && $ECForm->state == 'Active' && $this->isNewFormType($ECForm->url)) {
                    $forms[] = $this->formOptionFactory->create()
                        ->setValue($ECForm->id)
                        ->setLabel($ECForm->name);
                }
            }
        }

        return $forms;
    }

    /**
     * @inheritDoc
     */
    public function augmentFormData($formId): ?FormDataInterface
    {
        $postContent = $this->getPost();
        $client = $this->helper->getWebsiteApiClient($postContent->account_select);
        $ECForm = $client->getFormById($formId);

        if (!$ECForm) {
            return null;
        }

        $pageId = $this->extractPageId($ECForm->url);
        $formAttachment = preg_replace('/[^a-zA-Z0-9_ -]/s', '-', "ddg-form-{$pageId}");
        $scriptDomain = $this->extractDomain($ECForm->url);

        $scriptSrc = $postContent->form_style === 'embedded' ?
                    '//'. $scriptDomain . self::EMBEDDED_URL :
                    '//'. $scriptDomain . self::POPOVER_URL;

        $scriptSharing = ($postContent->form_style === 'embedded') ?
                    self::FORM_SHARING_EMBED :
                    self::FORM_SHARING_POPOVER;

        $queryParameters = [
            'id' => $pageId,
            'domain' => $scriptDomain,
            'attach' => "#{$formAttachment}",
            'delay' => (int) $postContent->show_after,
            'appearance' =>  $postContent->appearance,
            'keydismiss' => (bool) $postContent->enable_use_esc,
            'mobile' => (bool) $postContent->show_mobile,
            'sharing' => $scriptSharing,
            'auto' => !($postContent->form_style === 'embedded')
        ];

        $encodedQueryParameters = http_build_query($queryParameters);
        return $this->formDataFactory->create()
            ->setFormName($ECForm->name)
            ->setFormPageId($pageId)
            ->setFormDomain($scriptDomain)
            ->setScriptSrc("{$scriptSrc}?{$encodedQueryParameters}")
            ->setFormSharing($scriptSharing)
            ->setFormAttachment($formAttachment);
    }

    /**
     * Get post data
     *
     * @return \stdClass
     */
    private function getPost(): \stdClass
    {
        return json_decode($this->request->getContent());
    }

    /**
     * Extract the page id from the form URL e.g. 001ln562-3411pu49
     *
     * @param string $url
     * @return string
     */
    private function extractPageId($url)
    {
        $bits = explode('/', $url);
        return array_reverse($bits)[1].'/'.array_reverse($bits)[0];
    }

    /**
     * Extract the domain from the survey URL e.g. rl.dotdigital-pages.com
     *
     * @param string $url
     * @return string
     */
    private function extractDomain($url)
    {
        $bits = explode('/', $url);
        return $bits[2];
    }

    /**
     * Check for type
     *
     * @param string $form
     * @return false|int
     */
    private function isNewFormType($form)
    {
        return strpos($form, '/p/');
    }
}
