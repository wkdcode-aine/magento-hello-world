<?php
    namespace MiltonBayer\General\Model\Product\Type;

    class Price extends \Magento\Catalog\Model\Product\Type\Price
    {
        public function getRecommendedRetailPrice($product)
        {
            $rrp = $product->getData('recommended_retail_price');
            return $rrp > 0 ? __('RRP') . ": " . $this->priceCurrency->format($rrp) : '';
        }
    }
