<?php

namespace Dotdigitalgroup\Enterprise\Helper;

use Magento\Framework\App\Helper\Context;
use Dotdigitalgroup\Enterprise\Helper\Config;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;

    /**
     * @var array
     */
    private $contactEnterpriseDataFields
        = [
            'reward_points' => [
                'name' => 'REWARD_POINTS',
                'type' => 'numeric',
                'visibility' => 'private'
            ],
            'reward_ammount' => [
                'name' => 'REWARD_AMOUNT',
                'type' => 'numeric',
                'visibility' => 'private'
            ],
            'expiration_date' => [
                'name' => 'REWARD_EXP_DATE',
                'type' => 'date',
                'visibility' => 'private'
            ],
            'last_used_date' => [
                'name' => 'LAST_USED_DATE',
                'type' => 'date',
                'visibility' => 'private'
            ],
            'customer_segments' => [
                'name' => 'CUSTOMER_SEGMENTS',
                'type' => 'string',
                'visibility' => 'private'
            ],

        ];

    /**
     * @var \Dotdigitalgroup\Email\Helper\Data
     */
    private $emailHelper;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Dotdigitalgroup\Email\Helper\Data $emailHelper
     */
    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Dotdigitalgroup\Email\Helper\Data $emailHelper
    ) {
        $this->storeManager = $storeManager;
        $this->emailHelper = $emailHelper;
        parent::__construct($context);
    }

    /**
     * Enterprise data datafields attributes.
     *
     * @param mixed $website
     *
     * @return array/null
     *
     */
    public function getEnterpriseAttributes($website)
    {
        $website = $this->storeManager->getWebsite($website);
        $store = $website->getDefaultStore();
        $mappedData = $this->scopeConfig->getValue(
            'connector_data_mapping/enterprise_data',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store->getId()
        );

        return $mappedData;
    }

    /**
     * Get enterprise data fields
     *
     * @return array
     */
    public function getEnterpriseDataFields()
    {
        return $this->contactEnterpriseDataFields;
    }

    /**
     * Get mapping for reward point data field
     *
     * @param $website
     * @return mixed
     */
    public function getRewardPointMapping($website)
    {
        return $this->emailHelper->getWebsiteConfig(
            Config::XML_PATH_CONNECTOR_ENTERPRISE_CURRENT_BALANCE,
            $website
        );
    }

    /**
     * Get mapping for reward amount data field
     *
     * @param $website
     * @return mixed
     */
    public function getRewardAmountMapping($website)
    {
        return $this->emailHelper->getWebsiteConfig(
            Config::XML_PATH_CONNECTOR_ENTERPRISE_REWARD_AMOUNT,
            $website
        );
    }

    /**
     * Get mapping for customer segment data field
     *
     * @param $website
     * @return mixed
     */
    public function getCustomerSegmentMapping($website)
    {
        return $this->emailHelper->getWebsiteConfig(
            Config::XML_PATH_CONNECTOR_ENTERPRISE_CUSTOMER_SEGMENTS,
            $website
        );
    }

    /**
     * Get mapping for last used date data field
     *
     * @param $website
     * @return mixed
     */
    public function getLastUsedDateMapping($website)
    {
        return $this->emailHelper->getWebsiteConfig(
            Config::XML_PATH_CONNECTOR_ENTERPRISE_LAST_USED_DATE,
            $website
        );
    }

    /**
     * Get mapping for expiration date data field
     *
     * @param $website
     * @return mixed
     */
    public function getExpirationDateMapping($website)
    {
        return $this->emailHelper->getWebsiteConfig(
            Config::XML_PATH_CONNECTOR_ENTERPRISE_EXPIRATION_DATE,
            $website
        );
    }
}