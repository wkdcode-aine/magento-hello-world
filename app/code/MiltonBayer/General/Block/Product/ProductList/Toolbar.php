<?php
    namespace MiltonBayer\General\Block\Product\ProductList;

    class Toolbar extends \Magento\Catalog\Block\Product\ProductList\Toolbar
    {
        /**
         * Retrieve available Order fields list
         *
         * @return array
         */
        public function getAvailableOrders()
        {
            $this->loadAvailableOrders();
            return $this->_availableOrder;
        }

        /**
         * Compare defined order and direction fields with current order direction field
         *
         * @param string $order
         * @return bool
         */
        public function isOrderDirectionCurrent($order)
        {
            return $order == $this->getCurrentOrder() . '-' . $this->getCurrentDirection();
        }

        /**
         * Retrieve available Order fields list
         *
         * @return array
         */
        public function getAvailableOrderAndDirections()
        {
            $orderAndDirection = [];
            $directions = ['asc', 'desc'];
            foreach( $this->getAvailableOrders() as $index => $order ) {
                foreach( $directions as $dir ) {
                    $orderAndDirection[$index . '-' . $dir] = $order . '-' . $dir;
                }
            }

            return $orderAndDirection;
        }

        /**
         * Load Available Orders
         *
         * @return $this
         */
        private function loadAvailableOrders()
        {
            $this->_availableOrder = ['special_price', 'name'];
            return $this;
        }
    }
