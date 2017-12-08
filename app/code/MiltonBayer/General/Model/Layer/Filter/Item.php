<?php
    namespace MiltonBayer\General\Model\Layer\Filter;

    class Item extends \Magento\Catalog\Model\Layer\Filter\Item
    {
        /**
         * Core registry
         *
         * @var \Magento\Framework\Registry
         */
        protected $_coreRegistry = null;

        /**
         * @var \Magento\Framework\App\RequestInterface $request
         */
        protected $_request;

        /**
         * Construct
         *
         * @param \Magento\Framework\Registry $registry
         * @param \Magento\Framework\UrlInterface $url
         * @param \Magento\Theme\Block\Html\Pager $htmlPagerBlock
         * @param array $data
         */
        public function __construct(
            \Magento\Framework\App\RequestInterface $request,
            \Magento\Framework\Registry $registry,
            \Magento\Framework\UrlInterface $url,
            \Magento\Theme\Block\Html\Pager $htmlPagerBlock,
            array $data = []
        ) {
            $this->_coreRegistry = $registry;
            $this->_request = $request;
            parent::__construct($url, $htmlPagerBlock, $data);
        }

        /**
         * Get filter item url
         *
         * @return string
         */
        public function getUrl()
        {
            $params = $this->_request->getParams();
            $request_var = $this->getFilter()->getRequestVar();
            $values = [];
            $value = $this->getValue();

            if( array_key_exists($request_var, $params) ) {
                $get_var = $params[$request_var];
                $values = $get_var == "" ? [] : explode(",", $get_var);
                if( ($index = array_search($value, $values)) !== false ) {
                    array_splice($values, $index, 1);
                } else {
                    $values[] = $value;
                }
            } else {
                $values = [$value];
            }

            sort($values);

            $query = [
                $request_var => count($values) > 0 ? implode(",", $values) : $this->getFilter()->getResetValue(),
                // exclude current page from urls
                $this->_htmlPagerBlock->getPageVarName() => null,
            ];

            return $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);
        }

        /**
         *
         */
        public function getDesignADoorUrl()
        {
            return 'woop!';
        }

        /**
         * Retrieve current category model object
         *
         * @return \Magento\Catalog\Model\Category
         */
        private function getCurrentCategory()
        {
            if (!$this->hasData('current_category')) {
                $this->setData('current_category', $this->_coreRegistry->registry('current_category'));
            }
            return $this->getData('current_category');
        }
    }
