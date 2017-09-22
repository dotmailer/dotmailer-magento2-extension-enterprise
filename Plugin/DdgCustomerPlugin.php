<?php

namespace Dotdigitalgroup\Enterprise\Plugin;

class DdgCustomerPlugin
{
    /**
     * @var object
     */
    private $reward;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * @var \Magento\Reward\Model\ResourceModel\Reward\History\CollectionFactory
     */
    private $rewardHistoryCollectionFactory;

    /**
     * @var \Magento\CustomerSegment\Model\ResourceModel\Customer
     */
    private $customerSegmentCustomerResource;

    /**
     * @var \Magento\Reward\Helper\Data
     */
    private $rewardHelper;

    /**
     * @var \Magento\Customer\Model\Customer
     */
    private $customer;

    /**
     * @var \Dotdigitalgroup\Email\Model\Apiconnector\CustomerExtensionFactory
     */
    protected $customerExtensionFactory;

    /**
     * DdgCustomerPlugin constructor.
     *
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param \Magento\Reward\Model\ResourceModel\Reward\History\CollectionFactory $rewardHistoryCollectionFactory
     * @param \Magento\CustomerSegment\Model\ResourceModel\Customer $customerSegmentCustomerResource
     * @param \Magento\Reward\Helper\Data $rewardHelper
     * @param \Dotdigitalgroup\Email\Model\Apiconnector\CustomerExtensionFactory $customerExtensionFactory
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Reward\Model\ResourceModel\Reward\History\CollectionFactory $rewardHistoryCollectionFactory,
        \Magento\CustomerSegment\Model\ResourceModel\Customer $customerSegmentCustomerResource,
        \Magento\Reward\Helper\Data $rewardHelper,
        \Dotdigitalgroup\Email\Model\Apiconnector\CustomerExtensionFactory $customerExtensionFactory
    ) {
        $this->dateTime = $dateTime;
        $this->rewardHistoryCollectionFactory = $rewardHistoryCollectionFactory;
        $this->customerSegmentCustomerResource = $customerSegmentCustomerResource;
        $this->rewardHelper = $rewardHelper;
        $this->customerExtensionFactory = $customerExtensionFactory;
    }

    /**
     * @param \Dotdigitalgroup\Email\Model\Apiconnector\Customer $subject
     * @param $customer
     * @return mixed
     */
    public function beforeSetCustomerData(\Dotdigitalgroup\Email\Model\Apiconnector\Customer $subject, $customer)
    {
        $this->customer = $customer;

        $customerExtension = $subject->getExtensionAttributes();
        if ($customerExtension === null) {
            $customerExtension = $this->customerExtensionFactory>create();
        }
        $customerExtension->setRewardPoints($this->getRewardPoints());
        $customerExtension->setRewardAmmount($this->getRewardAmmount());
        $customerExtension->setExpirationDate($this->getExpirationDate());
        $customerExtension->setLastUsedDate($this->getLastUsedDate());
        $customerExtension->setCustomerSegments($this->getCustomerSegments());
        $subject->setExtensionAttributes($customerExtension);

        return [$customer];
    }

    /**
     * Reward points balance.
     *
     * @return int
     */
    public function getRewardPoints()
    {
        if (! $this->reward) {
            $this->_setReward();
        }

        if ($this->reward !== true) {
            return $this->reward->getPointsBalance();
        }

        return '';
    }

    /**
     * Currency amount points.
     *
     * @return mixed
     */
    public function getRewardAmmount()
    {
        if (!$this->reward) {
            $this->_setReward();
        }

        if ($this->reward !== true) {
            return $this->reward->getCurrencyAmount();
        }

        return '';
    }

    /**
     * Expiration date to use the points.
     *
     * @return string
     */
    public function getExpirationDate()
    {
        //set reward for later use
        if (!$this->reward) {
            $this->_setReward();
        }

        if ($this->reward !== true) {
            $expiredAt = $this->reward->getExpirationDate();

            if ($expiredAt) {
                $date = $this->dateTime->formatDate($expiredAt, true);
            } else {
                $date = '';
            }

            return $date;
        }

        return '';
    }

    /**
     * Get the customer reward.
     *
     * @return null
     */
    public function _setReward()
    {
        $rewardData = $this->rewardHelper;
        $historyCollectionFactory = $this->rewardHistoryCollectionFactory;
        $collection = $historyCollectionFactory->create()
            ->addCustomerFilter($this->customer->getId())
            ->addWebsiteFilter($this->customer->getWebsiteId())
            ->setExpiryConfig($rewardData->getExpiryConfig())
            ->addExpirationDate($this->customer->getWebsiteId())
            ->skipExpiredDuplicates()
            ->setDefaultOrder();

        $item = $collection->setPageSize(1)
            ->setCurPage(1)
            ->getFirstItem();

        $this->reward = $item;
    }

    /**
     * Customer segments id.
     *
     * @return string
     */
    public function getCustomerSegments()
    {
        $customerSegmentResource = $this->customerSegmentCustomerResource;
        $segmentIds = $customerSegmentResource->getCustomerWebsiteSegments(
            $this->customer->getId(),
            $this->customer->getWebsiteId()
        );

        if (! empty($segmentIds)) {
            return implode(',', $segmentIds);
        }

        return '';
    }

    /**
     * Last used reward points.
     *
     * @return mixed
     */
    public function getLastUsedDate()
    {
        $historyCollectionFactory = $this->rewardHistoryCollectionFactory;
        //last used from the reward history based on the points delta used enterprise module
        $lastUsed = $historyCollectionFactory->create()
            ->addCustomerFilter($this->customer->getId())
            ->addWebsiteFilter($this->customer->getWebsiteId())
            ->addFieldToFilter('points_delta', ['lt' => 0])
            ->setDefaultOrder()
            ->setPageSize(1)
            ->getFirstItem()
            ->getCreatedAt();
        //for any valid date
        if ($lastUsed) {
            return $this->dateTime->formatDate($lastUsed, true);
        }

        return '';
    }
}
