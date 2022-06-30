<?php

namespace Dotdigitalgroup\Enterprise\Plugin;

use Dotdigitalgroup\Email\Model\Connector\Datafield;
use Dotdigitalgroup\Enterprise\Helper\Data;

class DataFieldPlugin
{
    public const DATA_MAPPING_PATH_PREFIX = 'extra_data';

    /**
     * @var Data
     */
    private $helper;

    /**
     * DataFieldPlugin constructor.
     *
     * @param Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Before Get contact data fields.
     *
     * @param Datafield $subject
     * @param bool $withXmlPathPrefixes
     * @return null
     */
    public function beforeGetContactDatafields(
        Datafield $subject,
        bool $withXmlPathPrefixes = false
    ) {
        $subject->setContactDatafields($this->helper->getEnterpriseDataFields(), self::DATA_MAPPING_PATH_PREFIX);
        return null;
    }
}
