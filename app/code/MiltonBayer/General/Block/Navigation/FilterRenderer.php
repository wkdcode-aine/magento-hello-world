<?php
    namespace MiltonBayer\General\Block\Navigation;

    use Magento\Catalog\Model\Layer\Filter\FilterInterface;

    class FilterRenderer extends \Magento\LayeredNavigation\Block\Navigation\FilterRenderer
    {
        /**
         * @param FilterInterface $filter
         * @param array $selectedFilters
         * @return string
         */
        public function renderOptions(FilterInterface $filter, $selectedFilters)
        {
            $this->assign('filterItems', $filter->getItems());
            foreach($selectedFilters as $_selected) {
                if( $filter->getRequestVar() == $_selected->getFilter()->getRequestVar() ) {
                    $this->assign('selected', explode(",", $_selected->getValue()));
                }
            }
            $this->assign('is_price', $filter->getRequestVar() == 'price');
            $this->assign('param', $filter->getRequestVar());
            $html = $this->_toHtml();
            $this->assign('filterItems', []);
            return $html;
        }
    }
