<?php


namespace Dotdigitalgroup\Enterprise\Block;

use Dotdigitalgroup\Email\Helper\Data;
use Dotdigitalgroup\Enterprise\Model\Token\MagentoApiAccessToken;
use Magento\Framework\View\Element\Template;

/**
 * DotdigitalForm block
 *
 * @api
 */
class DotdigitalForm extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Data
     */
    private $emailHelper;

    /**
     * @var MagentoApiAccessToken
     */
    private $magentoApiAccessToken;

    /**
     * DotdigitalForm constructor.
     * @param Template\Context $context
     * @param Data $emailHelper
     * @param MagentoApiAccessToken $magentoApiAccessToken
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Data $emailHelper,
        MagentoApiAccessToken $magentoApiAccessToken,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->emailHelper = $emailHelper;
        $this->magentoApiAccessToken = $magentoApiAccessToken;
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function isEnabled(): bool
    {
        return $this->emailHelper->isConnectorEnabledAtAnyLevel();
    }

    /**
     * Return the translated message for an invalid API key.
     *
     * @return \Magento\Framework\Phrase
     */
    public function getActivationMessage(): \Magento\Framework\Phrase
    {
        return __(
            "An active Dotdigital account is required to use this feature.
             Please enable your account <a href='%1' target='_blank'>here</a>.",
            $this->_urlBuilder->getUrl(
                'adminhtml/system_config/edit/section/connector_api_credentials',
                ['_fragment' => 'cms_pagebuilder']
            )
        );
    }

    /**
     * @return string
     */
    public function getApiAccessToken()
    {
        return $this->magentoApiAccessToken->getTokenForPreview();
    }
}
