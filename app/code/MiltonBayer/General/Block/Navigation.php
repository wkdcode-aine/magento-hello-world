<?php
    namespace MiltonBayer\General\Block;

    class Navigation extends \Magento\LayeredNavigation\Block\Navigation
    {
        /**
         * Core registry
         *
         * @var \Magento\Framework\Registry
         */
        protected $_coreRegistry = null;

        /**
         * @param \Magento\Framework\Registry $registry
         * @param Template\Context $context
         * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
         * @param \Magento\Catalog\Model\Layer\FilterList $filterList
         * @param \Magento\Catalog\Model\Layer\AvailabilityFlagInterface $visibilityFlag
         * @param array $data
         */
        public function __construct(
            \Magento\Framework\Registry $registry,
            \Magento\Framework\View\Element\Template\Context $context,
            \Magento\Catalog\Model\Layer\Resolver $layerResolver,
            \Magento\Catalog\Model\Layer\FilterList $filterList,
            \Magento\Catalog\Model\Layer\AvailabilityFlagInterface $visibilityFlag,
            array $data = []
        ) {
            $this->_coreRegistry = $registry;
            parent::__construct($context, $layerResolver, $filterList, $visibilityFlag, $data);
        }

        /**
         * @return boolean
         */
        public function getIsDesignADoor()
        {
            $category = $this->getCurrentCategory();
            return $category != null && $category->getData('show_design_a_door') == 1;
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
