<?php


namespace Dotdigitalgroup\Enterprise\Block;

use Dotdigitalgroup\Email\Helper\Data;
use Dotdigitalgroup\Enterprise\Model\Token\MagentoApiAccessToken;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * DotdigitalForm block
 *
 * @api
 */
class DotdigitalForm extends Template
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
     *
     * @param Context $context
     * @param Data $emailHelper
     * @param MagentoApiAccessToken $magentoApiAccessToken
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $emailHelper,
        MagentoApiAccessToken $magentoApiAccessToken,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->emailHelper = $emailHelper;
        $this->magentoApiAccessToken = $magentoApiAccessToken;
    }

    /**
     * Is enabled check
     *
     * @return bool
     * @throws LocalizedException
     */
    public function isEnabled(): bool
    {
        return $this->emailHelper->isConnectorEnabledAtAnyLevel();
    }

    /**
     * Return the translated message for an invalid API key.
     *
     * @return Phrase
     */
    public function getActivationMessage(): Phrase
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
     * Get access token
     *
     * @return string
     */
    public function getApiAccessToken()
    {
        return $this->magentoApiAccessToken->getTokenForPreview();
    }
}
