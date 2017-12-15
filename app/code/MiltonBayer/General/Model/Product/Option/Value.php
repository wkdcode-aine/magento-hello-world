<?php
    namespace MiltonBayer\General\Model\Product\Option;

    class Value extends \Magento\Catalog\Model\Product\Option\Value implements \MiltonBayer\General\Api\Data\ProductCustomOptionValuesInterface
    {
        const KEY_CONDITIONAL_ON = 'conditional_on';
        const KEY_COLOUR_CODE = 'colour_code';

        /**
         * @var Value
         */
        protected $_conditional_on;

        /**
         * Get option title
         *
         * @return string
         * @codeCoverageIgnoreStart
         */
        public function getConditionalOn()
        {
            $conditional_on_id = $this->getData(static::KEY_CONDITIONAL_ON);

            if(empty($this->_conditional_on) && $conditional_on_id != 0)  $this->setConditionalOn($conditional_on_id);

            return $this->_conditional_on;
        }

        public function getConditionalOnId()
        {
            if( $this->getConditionalOn() != null ) {
                return $this->getData(static::KEY_CONDITIONAL_ON);;
            }

            return;
        }

        /**
         * Set Option type id
         *
         * @param int $conditional_on_id
         * @return int|null
         */
        public function setConditionalOn($conditional_on_id) {
            $this->_conditional_on = $this->_valueCollectionFactory
                ->create()
                ->addFieldToFilter(
                    'option_type_id',
                    ['eq' => $conditional_on_id]
                )
                ->getFirstItem();
        }

        /**
         * Get option colour code
         *
         * @return string
         * @codeCoverageIgnoreStart
         */
        public function getColourCode()
        {
            return $this->_getData(self::KEY_COLOUR_CODE);
        }

        /**
         * Set Option colour code
         *
         * @param string $colour_code
         * @return string|null
         */
        public function setColourCode($colour_code) {
            return $this->setData(self::KEY_COLOUR_CODE, $colour_code);
        }

        /*
         *
         */
        public function getConditionalParent()
        {
            if( $this->getConditionalOn() != null ) {
                return $this->_conditional_on->getData('option_id');
            }

            return;
        }

        /**
         * Return price. If $flag is true and price is percent
         *  return converted percent to price
         *
         * @param bool $flag
         * @return float|int
         */
        public function getPrice($flag = false)
        {
            if ($flag && $this->getPriceType() == self::TYPE_PERCENT) {
                $basePrice = $this->getOption()->getProduct()->getFinalPrice();
                $price = $basePrice * ($this->_getData(self::KEY_PRICE) / 100);
                return $price;
            }

            $price = $this->_getData(self::KEY_PRICE);
            return empty($price) ? '0.00' : $price;
        }
    }
