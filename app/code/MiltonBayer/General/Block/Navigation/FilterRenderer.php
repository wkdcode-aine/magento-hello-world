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
                    $labels = $_selected->getLabel();
                    $this->assign('selected', is_array($labels) ? $labels : [$labels]);
                }
            }
            $html = $this->_toHtml();
            $this->assign('filterItems', []);
            return $html;
        }
    }
