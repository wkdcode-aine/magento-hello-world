<?php
    namespace Wkdcode\GarageModule\Model\Product\Option;

    class Value extends \Magento\Catalog\Model\Product\Option\Value implements \Magento\Catalog\Api\Data\ProductCustomOptionValuesInterface
    {
        const KEY_CONDITIONAL_ON = 'conditional_on';

        /**
         * Get option title
         *
         * @return string
         * @codeCoverageIgnoreStart
         */
        public function getConditionalOn()
        {
            return $this->_getData(self::KEY_CONDITIONAL_ON);
        }
    }
