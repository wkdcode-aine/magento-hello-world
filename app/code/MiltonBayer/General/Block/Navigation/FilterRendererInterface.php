<?php
    namespace MiltonBayer\General\Block\Navigation;

    use Magento\Catalog\Model\Layer\Filter\FilterInterface;

    /**
     * Interface FilterRendererInterface
     * @api
     * @since 100.0.2
     */
    interface FilterRendererInterface
    {
        /**
         * Render filter
         *
         * @param FilterInterface $filter
         * @param array $selected_filters
         * @param bool $show_design_a_door
         * @return string
         */
        public function renderOptions(FilterInterface $filter, array $selected_filters, bool $show_design_a_door);
    }
