<?php
    namespace MiltonBayer\General\Model\Entity\Attribute\Source;

    use Magento\Framework\App\ObjectManager;
    use Magento\Store\Model\StoreManagerInterface;

    class Table extends \Magento\Eav\Model\Entity\Attribute\Source\Table
    {
        /**
         * @var StoreManagerInterface
         */
        private $storeManager;

        /**
         * Retrieve Full Option values array
         *
         * @param bool $withEmpty       Add empty option to array
         * @param bool $defaultValues
         * @return array
         */
        public function getAllOptions($withEmpty = true, $defaultValues = false)
        {
            $storeId = $this->getAttribute()->getStoreId();
            if ($storeId === null) {
                $storeId = $this->getStoreManager()->getStore()->getId();
            }
            if (!is_array($this->_options)) {
                $this->_options = [];
            }
            if (!is_array($this->_optionsDefault)) {
                $this->_optionsDefault = [];
            }
            $attributeId = $this->getAttribute()->getId();
            if (!isset($this->_options[$storeId][$attributeId])) {
                $collection = $this->_attrOptionCollectionFactory->create()->setPositionOrder(
                    'asc'
                )->setAttributeFilter(
                    $attributeId
                )->addFieldToFilter(
                    'searchable_option', 1
                )->setStoreFilter(
                    $storeId
                )->load();
                $this->_options[$storeId][$attributeId] = $collection->toOptionArray();
                $this->_optionsDefault[$storeId][$attributeId] = $collection->toOptionArray('default_value');
            }
            $options = $defaultValues
                ? $this->_optionsDefault[$storeId][$attributeId]
                : $this->_options[$storeId][$attributeId];
            if ($withEmpty) {
                $options = $this->addEmptyOption($options);
            }

            return $options;
        }

        /**
         * Get StoreManager dependency
         *
         * @return StoreManagerInterface
         * @deprecated 100.1.6
         */
        private function getStoreManager()
        {
            if ($this->storeManager === null) {
                $this->storeManager = ObjectManager::getInstance()->get(StoreManagerInterface::class);
            }
            return $this->storeManager;
        }

        /**
         * @param array $options
         * @return array
         */
        private function addEmptyOption(array $options)
        {
            array_unshift($options, ['label' => $this->getAttribute()->getIsRequired() ? '' : ' ', 'value' => '']);
            return $options;
        }
    }
