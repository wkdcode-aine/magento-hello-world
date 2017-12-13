<?php
    namespace MiltonBayer\General\Plugin\Model\ResourceModel\Entity;

    use Magento\Eav\Model\ResourceModel\Entity\Attribute as AttributeResource;
    use Magento\Framework\App\ObjectManager;

    class AttributePlugin
    {
        protected $_resource;

        public function __construct(
            \Magento\Framework\App\ResourceConnection $resource
        ) {
            $this->_resource = $resource;
        }

        /**
         * Save attribute options
         *
         * @param AttributeResource $object
         * @return $this
         */
        public function afterSave(
            \Magento\Catalog\Model\ResourceModel\Attribute $subject,
            \Magento\Catalog\Model\ResourceModel\Attribute $result,
            $object
        ) {
            $this->_saveOption($object);
            return $result;
        }

        /**
         * Save attribute options
         *
         * @param AttributeResource $object
         * @return $this
         */
        protected function _saveOption($object)
        {
            $option = $object->getOption();
            if (!is_array($option)) {
                return $this;
            }

            if (isset($option['order'])) {
                $this->_processAttributeOptions($object, $option);
            }

            return $this;
        }

        /**
         * Save changes of attribute options, return obtained default value
         *
         * @param EntityAttribute|AbstractModel $object
         * @param array $option
         * @return array
         */
        protected function _processAttributeOptions($object, $option)
        {
            $defaultValue = [];
            foreach ($option['order'] as $optionId => $values) {
                $this->_updateAttributeOption($object, $optionId, $option);
            }
            return $defaultValue;
        }


        /**
         * Save option records
         *
         * @param AbstractModel $object
         * @param int $optionId
         * @param array $option
         * @return int|bool
         */
        protected function _updateAttributeOption($object, $optionId, $option)
        {
            $connection = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
            $table = $connection->getTableName('eav_attribute_option');

            // ignore strings that start with a number
            $intOptionId = is_numeric($optionId) ? (int)$optionId : 0;

            if (!empty($option['delete'][$optionId])) {
                return false;
            }

            $searchableOption = empty($option['searchable_option'][$optionId]) ? 0 : $option['searchable_option'][$optionId];
            $data = ['searchable_option' => $searchableOption];
            $where = ['option_id = ?' => $intOptionId];
            $connection->update($table, $data, $where);

            return;
        }
    }
