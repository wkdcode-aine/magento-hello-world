<?php
    namespace MiltonBayer\General\Model\Layer\Filter;

    class Attribute extends \Magento\CatalogSearch\Model\Layer\Filter\Attribute {

        /**
         * @var \Magento\Framework\Filter\StripTags
         */
        private $tagFilter;

        /**
         * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory
         * @param \Magento\Store\Model\StoreManagerInterface $storeManager
         * @param \Magento\Catalog\Model\Layer $layer
         * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
         * @param \Magento\Framework\Filter\StripTags $tagFilter
         * @param array $data
         */
        public function __construct(
            \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
            \Magento\Store\Model\StoreManagerInterface $storeManager,
            \Magento\Catalog\Model\Layer $layer,
            \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
            \Magento\Framework\Filter\StripTags $tagFilter,
            array $data = []
        ) {
            parent::__construct(
                $filterItemFactory,
                $storeManager,
                $layer,
                $itemDataBuilder,
                $tagFilter,
                $data
            );
            $this->tagFilter = $tagFilter;
        }

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

            if( strstr($attributeValue, ',') ) {
                $attribute_value = explode(',', $attributeValue);
            } else {
                $attribute_value = [$attributeValue];
            }
            if( $attribute->getData('search_excludes_selected') == 1 ) {
                $all_options = $attribute->getSource()->getAllOptions(false);

                $all_values = [];
                foreach($all_options as $option) $all_values[] = $option['value'];
                $search_values = array_diff($all_values, $attribute_value);
            } else {
                $search_values = $attributeValue;
            }

            /** @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $productCollection */
            $productCollection = $this->getLayer()->getProductCollection();
            $productCollection->addFieldToFilter($attribute->getAttributeCode(), $search_values);
            $label = $this->getOptionText($attributeValue);
            $this->getLayer()
                ->getState()
                ->addFilter($this->_createItem($label, $attributeValue));

            return $this;
        }

        /**
         * Get data array for building attribute filter items
         *
         * @return array
         * @throws \Magento\Framework\Exception\LocalizedException
         */
        protected function _getItemsData()
        {
            $attribute = $this->getAttributeModel();
            /** @var \MiltonBayer\General\Model\ResourceModel\Fulltext\Collection $productCollection */
            $productCollection = $this->getLayer()->getProductCollection();

            $values = $attribute->getSource()->getAllOptions(false);

            $isAttributeFilterable =
                $this->getAttributeIsFilterable($attribute) === static::ATTRIBUTE_OPTIONS_ONLY_WITH_RESULTS;

            if (count($values) === 0 && !$isAttributeFilterable) {
                return $this->itemDataBuilder->build();
            }

            $productSize = $productCollection->getSize();

            $options = $attribute->getFrontend()
                ->getSelectOptions();
            foreach ($options as $option) {
                $this->buildOptionData($option);
            }

            return $this->itemDataBuilder->build();
        }

        /**
         * Build option data
         *
         * @param array $option
         * @param boolean $isAttributeFilterable
         * @param array $optionsFacetedData
         * @param int $productSize
         * @return void
         */
        private function buildOptionData($option)
        {
            $value = $this->getOptionValue($option);
            if ($value === false) {
                return;
            }

            $this->itemDataBuilder->addItemData(
                $this->tagFilter->filter($option['label']),
                $value,
                1
            );
        }

        /**
         * Retrieve option value if it exists
         *
         * @param array $option
         * @return bool|string
         */
        private function getOptionValue($option)
        {
            if (empty($option['value']) && !is_numeric($option['value'])) {
                return false;
            }
            return $option['value'];
        }
    }
