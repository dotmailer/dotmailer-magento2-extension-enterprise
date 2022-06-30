<?php

namespace Dotdigitalgroup\Enterprise\Plugin;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\GroupedProduct\Model\Product\Type\Grouped;

class GroupedProductTypePlugin
{
    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * GroupedProductTypePlugin constructor.
     *
     * @param CollectionFactory $productCollectionFactory
     */
    public function __construct(
        CollectionFactory $productCollectionFactory
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * After get parent
     *
     * @param Grouped $groupedType
     * @param array $parentRowIds
     * @return array
     */
    public function afterGetParentIdsByChild(Grouped $groupedType, array $parentRowIds)
    {
        $parentEntityIds = [];
        $collection = $this->productCollectionFactory->create()
            ->addFieldToFilter('row_id', ['in' => $parentRowIds]);

        foreach ($collection as $row) {
            $parentEntityIds[] = $row->getEntityId();
        }
        return !empty($parentEntityIds) ? $parentEntityIds : $parentRowIds;
    }
}
