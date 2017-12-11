<?php
    namespace MiltonBayer\General\Block\Navigation;

    use Magento\Catalog\Model\Layer\Filter\FilterInterface;

    class FilterRenderer extends \Magento\LayeredNavigation\Block\Navigation\FilterRenderer
    {
        /**
         * @param FilterInterface $filter
         * @param array $selected_filters
         * @param bool $show_design_a_door
         * @return string
         */
        public function renderOptions(FilterInterface $filter, array $selected_filters = [], bool $show_design_a_door = false)
        {
            $this->assign('filterItems', $filter->getItems());
            foreach($selected_filters as $_selected) {
                if( $filter->getRequestVar() == $_selected->getFilter()->getRequestVar() ) {
                    $this->assign('selected', explode(",", $_selected->getValue()));
                }
            }
            $this->assign('is_price', $filter->getRequestVar() == 'price');
            $this->assign('designadoor', $show_design_a_door);
            $this->assign('param', $filter->getRequestVar());
            $html = $this->_toHtml();
            $this->assign('filterItems', []);
            return $html;
        }
    }
