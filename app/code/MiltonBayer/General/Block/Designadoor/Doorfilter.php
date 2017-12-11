<?php
    namespace MiltonBayer\General\Block\Designadoor;

    class Doorfilter extends \Magento\Framework\View\Element\Template
    {
        public $category_id;

        public function renderOptions(int $category_id)
        {
            $this->assign('category_id', $category_id);
            $html = $this->_toHtml();

            return $html;
        }
    }
