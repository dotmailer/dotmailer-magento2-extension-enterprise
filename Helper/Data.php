<?php

namespace Dotdigitalgroup\Enterprise\Helper;

use Dotdigitalgroup\Email\Helper\Data as HelperDataAlias;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
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
            'reward_amount' => [
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
     * @var HelperDataAlias
     */
    private $emailHelper;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param HelperDataAlias $emailHelper
     */
    public function __construct(
        Context $context,
        HelperDataAlias $emailHelper
    ) {
        $this->emailHelper = $emailHelper;
        parent::__construct($context);
    }

    /**
     * Enterprise data datafields attributes.
     *
     * @param mixed $website
     * @return array/null
     */
    public function getEnterpriseAttributes($website)
    {
        $mappedData = $this->scopeConfig->getValue(
            'connector_data_mapping/extra_data',
            ScopeInterface::SCOPE_WEBSITES,
            $website->getId()
        );

        return $mappedData;
    }

    /**
     * Get enterprise fields
     *
     * @return array
     */
    public function getEnterpriseDataFields()
    {
        return $this->contactEnterpriseDataFields;
    }

    /**
     * Get reward point mapping configuration value
     *
     * @param int|string $website
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
     * Get reward amount mapping configuration value
     *
     * @param int|string $website
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
     * Get customer segment mapping configuration value
     *
     * @param int|string $website
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
     * Get last used date mapping configuration value
     *
     * @param int|string $website
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
     * Get expiration date mapping configuration value
     *
     * @param int|string $website
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
