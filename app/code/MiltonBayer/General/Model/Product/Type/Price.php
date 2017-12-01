<?php
    namespace MiltonBayer\General\Model\Product\Type;

    class Price extends \Magento\Catalog\Model\Product\Type\Price
    {
        public function getRecommendedRetailPrice($product)
        {
            return $this->priceCurrency->format($product->getData('recommended_retail_price'));
        }
    }
