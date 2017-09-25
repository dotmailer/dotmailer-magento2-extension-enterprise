<?php

namespace Dotdigitalgroup\Enterprise\Helper;

use Magento\Framework\App\Helper\Context;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_CONNECTOR_ENTERPRISE_CURRENT_BALANCE = 'connector_data_mapping/enterprise_data/reward_points';
    const XML_PATH_CONNECTOR_ENTERPRISE_REWARD_AMOUNT = 'connector_data_mapping/enterprise_data/reward_ammount';
    const XML_PATH_CONNECTOR_ENTERPRISE_EXPIRATION_DATE = 'connector_data_mapping/enterprise_data/expiration_date';
    const XML_PATH_CONNECTOR_ENTERPRISE_LAST_USED_DATE = 'connector_data_mapping/enterprise_data/last_used_date';
    const XML_PATH_CONNECTOR_ENTERPRISE_CUSTOMER_SEGMENTS = 'connector_data_mapping/enterprise_data/customer_segments';

    public function __construct(Context $context)
    {
        parent::__construct($context);
    }
}