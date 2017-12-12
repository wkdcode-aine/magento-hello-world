<?php
    namespace MiltonBayer\General\Block\Adminhtml\Attribute\Edit\Options;

    use Magento\Eav\Block\Adminhtml\Attribute\Edit\Options\Options as EavOptions;

    class Options extends EavOptions
    {
        /**
         * @var string
         */
        protected $_template = 'MiltonBayer_General::catalog/product/attribute/options.phtml';

        /**
         * @var int
         */
        public $searchable_enabled_value = 1;

        /**
         * Prepare option values of user defined attribute
         *
         * @param array|\Magento\Eav\Model\ResourceModel\Entity\Attribute\Option $option
         * @param string $inputType
         * @param array $defaultValues
         * @return array
         */
        protected function _prepareUserDefinedAttributeOptionValues($option, $inputType, $defaultValues)
        {
            $optionId = $option->getId();

            $value['checked'] = in_array($optionId, $defaultValues) ? 'checked="checked"' : '';
            $value['intype'] = $inputType;
            $value['id'] = $optionId;
            $value['sort_order'] = $option->getSortOrder();
            $value['searchable_option_checked'] = $option->getData('searchable_option') == $this->searchable_enabled_value ? 'checked="checked"' : '';

            foreach ($this->getStores() as $store) {
                $storeId = $store->getId();
                $storeValues = $this->getStoreOptionValues($storeId);
                $value['store' . $storeId] = isset(
                    $storeValues[$optionId]
                ) ? $this->escapeHtml(
                    $storeValues[$optionId]
                ) : '';
            }

            return [$value];
        }

    }
