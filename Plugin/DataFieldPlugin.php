<?php

namespace Dotdigitalgroup\Enterprise\Plugin;

/**
 * Class DataFieldPlugin
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class DataFieldPlugin
{
    /**
     * @var \Dotdigitalgroup\Enterprise\Helper\Data
     */
    private $helper;

    /**
     * DdgHelperPlugin constructor.
     * @param \Dotdigitalgroup\Enterprise\Helper\Data $helper
     */
    public function __construct(\Dotdigitalgroup\Enterprise\Helper\Data $helper)
    {
        $this->helper = $helper;
    }


    /**
     * @param \Dotdigitalgroup\Email\Model\Connector\Datafield $subject
     * @param $result
     * @return array
     */
    public function afterGetEnterpriseDataFields(
        \Dotdigitalgroup\Email\Model\Connector\Datafield $subject,
        $result
    ) {
        return $this->helper->getEnterpriseDataFields();
    }
}