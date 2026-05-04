<?php

namespace Shoppy\OutOfStockLast\Plugin;

/**
 * Plugin to modify product collection sorting to prioritize in-stock products
 */
class LayerPlugin
{
    /**
     * After prepare product collection, modify the order to sort by stock status first
     *
     * @param \Magento\Catalog\Model\Layer $subject
     * @param mixed $result
     * @return mixed
     */
    public function afterPrepareProductCollection($subject, $result)
    {
        $collection = $subject->getProductCollection();
        $select = $collection->getSelect();
        $select->reset(\Zend_Db_Select::ORDER);
        $select->order("is_salable DESC");
        $collection->addAttributeToSort(
            $subject->getCurrentOrder(),
            $subject->getCurrentDirection(),
        );
        return $result;
    }
}
