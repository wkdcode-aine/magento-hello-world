<?php
    namespace MiltonBayer\General\Model\Layer\Filter;

    class Item extends \Magento\Catalog\Model\Layer\Filter\Item
    {
        /**
         * Get filter item url
         *
         * @return string
         */
        public function getUrl()
        {
            $str = "";
            $request_var = "6,7";
            $values = $request_var == "" ? [] : explode(",", $request_var);
            $values[] = $this->getValue();
            array_unique($values);

            $query = [
                $this->getFilter()->getRequestVar() => implode(",", $values),
                // exclude current page from urls
                $this->_htmlPagerBlock->getPageVarName() => null,
            ];
            return $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);
        }
    }
