<?php

namespace Dotdigitalgroup\Enterprise\Plugin;

use Dotdigitalgroup\Email\Model\Connector\ContactData\Customer as CustomerContactData;
use Magento\Reward\Model\ResourceModel\Reward\CollectionFactory;

class CustomerPlugin
{
    /**
     * @var object
     */
    private $rewardDataFromHistory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * @var CollectionFactory
     */
    private $rewardCollectionFactory;

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
     * @var \Dotdigitalgroup\Enterprise\Helper\Data
     */
    private $helper;

    /**
     * CustomerPlugin constructor.
     *
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param CollectionFactory $rewardCollectionFactory
     * @param \Magento\Reward\Model\ResourceModel\Reward\History\CollectionFactory $rewardHistoryCollectionFactory
     * @param \Magento\CustomerSegment\Model\ResourceModel\Customer $customerSegmentCustomerResource
     * @param \Magento\Reward\Helper\Data $rewardHelper
     * @param \Dotdigitalgroup\Enterprise\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime $dateTime,
        CollectionFactory $rewardCollectionFactory,
        \Magento\Reward\Model\ResourceModel\Reward\History\CollectionFactory $rewardHistoryCollectionFactory,
        \Magento\CustomerSegment\Model\ResourceModel\Customer $customerSegmentCustomerResource,
        \Magento\Reward\Helper\Data $rewardHelper,
        \Dotdigitalgroup\Enterprise\Helper\Data $helper
    ) {
        $this->dateTime = $dateTime;
        $this->rewardCollectionFactory = $rewardCollectionFactory;
        $this->rewardHistoryCollectionFactory = $rewardHistoryCollectionFactory;
        $this->customerSegmentCustomerResource = $customerSegmentCustomerResource;
        $this->rewardHelper = $rewardHelper;
        $this->helper = $helper;
    }

    /**
     * @param CustomerContactData $subject
     *
     * @return mixed
     */
    public function beforeSetContactData(CustomerContactData $subject)
    {
        $this->customer = $subject->getModel();
        $websiteId = $this->customer->getWebsiteId();
        $this->rewardDataFromHistory = false;

        if ($this->helper->getRewardPointMapping($websiteId)) {
            $this->customer->setRewardPoints($this->getRewardPoints());
        }
        if ($this->helper->getRewardAmountMapping($websiteId)) {
            $this->customer->setRewardAmount($this->getRewardAmount());
        }
        if ($this->helper->getExpirationDateMapping($websiteId)) {
            $this->customer->setExpirationDate($this->getExpirationDate());
        }
        if ($this->helper->getLastUsedDateMapping($websiteId)) {
            $this->customer->setLastUsedDate($this->getLastUsedDate());
        }
        if ($this->helper->getCustomerSegmentMapping($websiteId)) {
            $this->customer->setCustomerSegments($this->getCustomerSegments());
        }

        return null;
    }

    /**
     * Fetch reward points balance from the magento_reward table.
     * [Not from magento_reward_history because that doesn't accurately factor in expired rewards.]
     *
     * @return string
     */
    public function getRewardPoints()
    {
        $collection = $this->rewardCollectionFactory->create()
            ->addFieldToFilter('customer_id', $this->customer->getId())
            ->addWebsiteFilter($this->customer->getWebsiteId());

        if ($collection->getSize()) {
            return $collection->getFirstItem()->getPointsBalance();
        }

        return '';
    }

    /**
     * Currency amount points.
     *
     * @return mixed
     */
    public function getRewardAmount()
    {
        if (!$this->rewardDataFromHistory) {
            $this->setRewardDataFromHistory();
        }

        if ($this->rewardDataFromHistory !== true) {
            return $this->rewardDataFromHistory->getCurrencyAmount();
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
        if (!$this->rewardDataFromHistory) {
            $this->setRewardDataFromHistory();
        }

        if ($this->rewardDataFromHistory !== true) {
            $expiredAt = $this->rewardDataFromHistory->getExpirationDate();

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
     * @return void
     */
    public function setRewardDataFromHistory()
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

        $this->rewardDataFromHistory = $item;
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
     * @return string
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
