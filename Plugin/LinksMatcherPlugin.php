<?php

namespace Dotdigitalgroup\Enterprise\Plugin;

use Magento\CustomerSegment\Model\Segment;
use Magento\CustomerSegment\Model\ResourceModel\SegmentFactory as SegmentResourceFactory;
use Magento\CustomerSegment\Model\ResourceModel\Customer\LinksMatcher;

class LinksMatcherPlugin
{
    /**
     * @var SegmentResourceFactory
     */
    private $segmentResourceFactory;

    /**
     * LinksMatcherPlugin constructor.
     *
     * @param SegmentResourceFactory $segmentResourceFactory
     */
    public function __construct(
        SegmentResourceFactory $segmentResourceFactory
    ) {
        $this->segmentResourceFactory = $segmentResourceFactory;
    }

    /**
     * Reimport customers from the segment before it is changed.
     *
     * @param LinksMatcher $subject
     * @param Segment $segment
     */
    public function beforeMatchCustomerLinks(LinksMatcher $subject, Segment $segment)
    {
        $this->reimportSegmentCustomers($segment);
    }

    /**
     * Reimport customers following any change in conditions.
     *
     * @param LinksMatcher $subject
     * @param mixed $result Type is null but PHPCS doesn't permit that.
     * @param Segment $segment
     * @return void
     */
    public function afterMatchCustomerLinks(
        LinksMatcher $subject,
        $result,
        Segment $segment
    ) {
        $this->reimportSegmentCustomers($segment);
    }

    /**
     * Reset segment customers.
     *
     * @param Segment $segment
     */
    public function reimportSegmentCustomers(Segment $segment)
    {
        $segmentResource = $this->segmentResourceFactory->create();
        $customerSegmentQuery = $segmentResource->getConnection()
            ->select()
            ->from($segmentResource->getTable('magento_customersegment_customer'), ['customer_id'])
            ->join(
                ['segment' => 'magento_customersegment_segment'],
                'segment.segment_id = magento_customersegment_customer.segment_id',
                ''
            )
            ->where('segment.is_active = 1')
            ->where('magento_customersegment_customer.segment_id = ?', $segment->getId())
            ->assemble();

        $this->segmentResourceFactory->create()->getConnection()->update(
            $segmentResource->getTable('email_contact'),
            ['email_imported' => 0],
            ['customer_id IN (?)' => new \Zend_Db_Expr($customerSegmentQuery)]
        );
    }
}
