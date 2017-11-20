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

            $orders = ['special_price', 'name'];
            $defaultOrder = $this->getOrderField();

            die(var_dump($defaultOrder));
            if (!isset($orders[$defaultOrder])) {
                $keys = array_keys($orders);
                $defaultOrder = $keys[0];
            }

            $order = $this->_toolbarModel->getOrder();
            die(var_dump($order));

            if (!$order || !isset($orders[$order])) {
                $order = $defaultOrder;
            }

            if ($order != $defaultOrder) {
                $this->_memorizeParam('sort_order', $order);
            }

            $this->setData('_current_grid_order', $order);
            return $order;
        }
    }
