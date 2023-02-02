<?php


namespace Dotdigitalgroup\Enterprise\Block;

use Dotdigitalgroup\Email\Helper\Data;
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
     * DotdigitalForm constructor.
     *
     * @param Context $context
     * @param Data $emailHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $emailHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->emailHelper = $emailHelper;
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
}
