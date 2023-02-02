<?php

namespace Dotdigitalgroup\Enterprise\Controller\Adminhtml\Ajax;

use Dotdigitalgroup\Enterprise\Api\Data\FormOptionInterface;
use Dotdigitalgroup\Enterprise\Api\FormManagementInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\AbstractSimpleObject;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

class Formoptions extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Dotdigitalgroup_Email::automation';

    /**
     * @var FormManagementInterface
     */
    private $formManagementInterface;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @param Context $context
     * @param FormManagementInterface $formManagementInterface
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        FormManagementInterface $formManagementInterface,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->formManagementInterface = $formManagementInterface;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Execute
     *
     * @return Json
     */
    public function execute()
    {
        $websiteId = $this->getRequest()->getParam('website_id');
        $response = $this->formManagementInterface->getFormOptions($websiteId);

        foreach ($response as $i => $item) {
            if ($item instanceof FormOptionInterface) {
                /** @var AbstractSimpleObject $item */
                $response[$i] = $item->__toArray();
            }
        }

        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData($response);

        return $resultJson;
    }
}
