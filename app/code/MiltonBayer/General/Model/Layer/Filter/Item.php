<?php
    namespace MiltonBayer\General\Model\Layer\Filter;

    class Item extends \Magento\Catalog\Model\Layer\Filter\Item
    {
        /**
         * @var \Magento\Framework\App\RequestInterface $request
         */
        protected $_request;

        /**
         * Construct
         *
         * @param \Magento\Framework\UrlInterface $url
         * @param \Magento\Theme\Block\Html\Pager $htmlPagerBlock
         * @param array $data
         */
        public function __construct(
            \Magento\Framework\App\RequestInterface $request,
            \Magento\Framework\UrlInterface $url,
            \Magento\Theme\Block\Html\Pager $htmlPagerBlock,
            array $data = []
        ) {
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

            if( count($values) > 0 ) {
                $query = [
                    $request_var => implode(",", $values),
                    // exclude current page from urls
                    $this->_htmlPagerBlock->getPageVarName() => null,
                ];
            } else {
                $query = [$request_var => $this->getFilter()->getResetValue(), $this->_htmlPagerBlock->getPageVarName() => null];
            }

            return $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);
        }
    }
