<?php
    namespace MiltonBayer\General\Model\Layer\Filter;

    class Attribute extends \Magento\CatalogSearch\Model\Layer\Filter\Attribute {

        /**
         * Apply attribute option filter to product collection
         *
         * @param \Magento\Framework\App\RequestInterface $request
         * @return $this
         * @throws \Magento\Framework\Exception\LocalizedException
         */
        public function apply(\Magento\Framework\App\RequestInterface $request)
        {
            $attributeValue = $request->getParam($this->_requestVar);
            if (empty($attributeValue) && !is_numeric($attributeValue)) {
                return $this;
            }
            $attribute = $this->getAttributeModel();
            /** @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $productCollection */
            $productCollection = $this->getLayer()
                ->getProductCollection();
            $productCollection->addFieldToFilter($attribute->getAttributeCode(), $attributeValue);
            $label = $this->getOptionText($attributeValue);
            $this->getLayer()
                ->getState()
                ->addFilter($this->_createItem($label, $attributeValue));

            return $this;
        }
    }
