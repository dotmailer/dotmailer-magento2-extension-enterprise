<?php

namespace Dotdigitalgroup\Enterprise\Plugin;

use Dotdigitalgroup\Enterprise\Helper\Data;
use Magento\CustomerSegment\Model\ResourceModel\Customer;
use Magento\Framework\Stdlib\DateTime;
use Magento\Reward\Helper\Data as RewardHelper;
use Magento\Reward\Model\Reward as RewardModel;
use Magento\Reward\Model\Reward\History as RewardHistoryModel;
use Magento\Reward\Model\RewardFactory;
use Magento\Reward\Model\ResourceModel\Reward\History\CollectionFactory as RewardHistoryCollectionFactory;

class CustomerPlugin
{
    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var RewardHistoryCollectionFactory
     */
    private $rewardHistoryCollectionFactory;

    /**
     * @var \Magento\CustomerSegment\Model\ResourceModel\Customer
     */
    private $customerSegmentCustomerResource;

    /**
     * @var RewardFactory
     */
    private $rewardFactory;

    /**
     * @var RewardHelper
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
     * @param DateTime $dateTime
     * @param RewardHistoryCollectionFactory $rewardHistoryCollectionFactory
     * @param Customer $customerSegmentCustomerResource
     * @param RewardHelper $rewardHelper
     * @param RewardFactory $rewardFactory
     * @param Data $helper
     */
    public function __construct(
        DateTime $dateTime,
        RewardHistoryCollectionFactory $rewardHistoryCollectionFactory,
        Customer $customerSegmentCustomerResource,
        RewardHelper $rewardHelper,
        RewardFactory $rewardFactory,
        Data $helper
    ) {
        $this->dateTime = $dateTime;
        $this->rewardHistoryCollectionFactory = $rewardHistoryCollectionFactory;
        $this->customerSegmentCustomerResource = $customerSegmentCustomerResource;
        $this->rewardFactory = $rewardFactory;
        $this->rewardHelper = $rewardHelper;
        $this->helper = $helper;
    }

    /**
     * @param \Dotdigitalgroup\Email\Model\Apiconnector\Customer $subject
     * @return mixed
     */
    public function beforeSetContactData(\Dotdigitalgroup\Email\Model\Apiconnector\Customer $subject)
    {
        $this->customer = $subject->getModel();
        $customerId = $this->customer->getId();
        $websiteId = $this->customer->getWebsiteId();

        $reward = $this->rewardFactory->create()
            ->setCustomerId($customerId)
            ->setWebsiteId($websiteId)
            ->loadByCustomer();

        $mostRecentRewardHistoryItem = $this->getRewardDataFromHistory($customerId, $websiteId);

        if ($this->helper->getRewardPointMapping($websiteId)) {
            $this->customer->setRewardPoints(
                $this->getRewardPoints($reward)
            );
        }
        if ($this->helper->getRewardAmountMapping($websiteId)) {
            $this->customer->setRewardAmount(
                $this->getRewardAmount($reward)
            );
        }
        if ($this->helper->getExpirationDateMapping($websiteId)) {
            $this->customer->setExpirationDate(
                $this->getExpirationDate($mostRecentRewardHistoryItem)
            );
        }
        if ($this->helper->getLastUsedDateMapping($websiteId)) {
            $this->customer->setLastUsedDate(
                $this->getLastUsedDate($customerId, $websiteId)
            );
        }
        if ($this->helper->getCustomerSegmentMapping($websiteId)) {
            $this->customer->setCustomerSegments($this->getCustomerSegments());
        }

        return null;
    }

    /**
     * Fetch reward points balance.
     *
     * @param RewardModel $reward
     * @return string
     */
    private function getRewardPoints(RewardModel $reward)
    {
        return $reward->getPointsBalance() ?: '';
    }

    /**
     * Currency amount points.
     *
     * @param RewardModel $reward
     * @return float|string
     */
    private function getRewardAmount(RewardModel $reward)
    {
        return $reward->getCurrencyAmount() ?: '';
    }

    /**
     * Expiration date to use the points.
     *
     * @param RewardHistoryModel $reward
     * @return string
     */
    private function getExpirationDate(RewardHistoryModel $reward)
    {
        $expiredAt = $reward->getExpirationDate();
        return $expiredAt ? $this->dateTime->formatDate($expiredAt, true) : '';
    }

    /**
     * Get the customer reward.
     *
     * @param string $customerId
     * @param string $websiteId
     * @return RewardHistoryModel
     */
    private function getRewardDataFromHistory($customerId, $websiteId)
    {
        $collection = $this->rewardHistoryCollectionFactory->create()
            ->addCustomerFilter($this->customer->getId())
            ->addWebsiteFilter($this->customer->getWebsiteId())
            ->setExpiryConfig($this->rewardHelper->getExpiryConfig())
            ->addExpirationDate($this->customer->getWebsiteId())
            ->skipExpiredDuplicates()
            ->setDefaultOrder();

        return $collection->setPageSize(1)
            ->setCurPage(1)
            ->getFirstItem();
    }

    /**
     * Last used reward points.
     *
     * Fetches the created_at of the most recent history row where points_delta was negative.
     *
     * @param string $customerId
     * @param string $websiteId
     * @return string
     */
    private function getLastUsedDate($customerId, $websiteId)
    {
        $lastUsed = $this->rewardHistoryCollectionFactory->create()
            ->addCustomerFilter($customerId)
            ->addWebsiteFilter($websiteId)
            ->addFieldToFilter('points_delta', ['lt' => 0])
            ->setDefaultOrder()
            ->setPageSize(1)
            ->getFirstItem()
            ->getCreatedAt();

        return ($lastUsed) ? $this->dateTime->formatDate($lastUsed, true) : '';
    }

    /**
     * Customer segments id.
     *
     * @return string
     */
    private function getCustomerSegments()
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
}
