<?php
    namespace MiltonBayer\General\Block\Product\ProductList;

    class Toolbar extends \Magento\Catalog\Block\Product\ProductList\Toolbar
    {
        /**
         * Get grit products sort order field
         *
         * @return string
         */
        public function getCurrentOrder()
        {
            $order = $this->_getData('_current_grid_order');
            if ($order) {
                return $order;
            }

            $orders = $this->getAvailableOrders();
            $defaultOrder = $this->getOrderField();

            if (!isset($orders[$defaultOrder])) {
                $keys = array_keys($orders);
                $defaultOrder = $keys[0];
            }

            $order = $this->_toolbarModel->getOrder();
            if (!$order || !isset($orders[$order])) {
                $order = $defaultOrder;
            }

            if ($order != $defaultOrder) {
                $this->_memorizeParam('sort_order', $order);
            }

            $this->setData('_current_grid_order', $order);
            return $order;
        }

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
         * Retrieve available Order fields list
         *
         * @return array
         */
        public function getAvailableOrderAndDirections()
        {
            $orderAndDirection = [];
            $directions = ['asc', 'desc'];
            foreach( $this->getAvailableOrders() as $order ) {
                foreach( $directions as $dir ) {
                    $orderAndDirection[$order . '-' . $dir] = $order . '-' . $dir;
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
