<?php
    namespace MiltonBayer\General\Model;

    class Product extends \Magento\Catalog\Model\Product
    {
        /**
         * Returns the recommended retail price
         *
         * @return mixed
         */
        public function getRecommendedRetailPrice()
        {
            return $this->getPriceModel()->getRecommendedRetailPrice($this);
        }
    }
