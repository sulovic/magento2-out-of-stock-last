<?php

namespace Shoppy\OutOfStockLast\Plugin\Collection;

use Magento\Catalog\Model\ResourceModel\Product\Collection;

class CategoryCollectionPlugin
{
    public function beforeLoad(Collection $subject)
    {
        if (!$this->isApplicable($subject)) {
            return;
        }

        /**
         * IMPORTANT:
         * We DO NOT reset ORDER BY.
         * We ONLY prepend stock priority.
         *
         * This preserves:
         * - user sorting (price, name, position)
         * - layered navigation sorting
         * - toolbar sorting
         */
        $subject->getSelect()->order("is_salable DESC");

        $subject->setFlag("out_of_stock_last_applied", true);
    }

    private function isApplicable(Collection $subject): bool
    {
        if ($subject->getFlag("out_of_stock_last_applied")) {
            return false;
        }

        /**
         * Category detection (safe Magento signal)
         * Works in category, layered nav, and most listing contexts
         */
        if (!$subject->getFlag("category_id") && !$subject->getCategoryId()) {
            return false;
        }

        return true;
    }
}
