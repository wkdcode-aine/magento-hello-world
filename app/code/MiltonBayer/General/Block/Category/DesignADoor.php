<?php
    namespace MiltonBayer\General\Block\Category;

    class DesignADoor extends \Magento\Framework\View\Element\Template
    {
        /**
         * Core registry
         *
         * @var \Magento\Framework\Registry
         */
        protected $_coreRegistry = null;

        /**
         * @param \Magento\Framework\Registry $registry
         * @param array $data
         */
        public function __construct(
            \Magento\Framework\View\Element\Template\Context $context,
            \Magento\Framework\Registry $registry,
            array $data = []
        ) {
            $this->_coreRegistry = $registry;
            parent::__construct($context, $data);
        }

        /**
         * @return boolean
         */
        public function showDoorBuilder()
        {
            return $this->getCurrentCategory()->getData('show_design_a_door') == 1;
        }

        /**
         * Retrieve current category model object
         *
         * @return \Magento\Catalog\Model\Category
         */
        public function getCurrentCategory()
        {
            if (!$this->hasData('current_category')) {
                $this->setData('current_category', $this->_coreRegistry->registry('current_category'));
            }
            return $this->getData('current_category');
        }
    }
