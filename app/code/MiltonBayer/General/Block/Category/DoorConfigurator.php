<?php
    namespace MiltonBayer\General\Block\Category;

    class DoorConfigurator extends \Magento\Framework\View\Element\Template
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
            \Magento\Catalog\Model\Layer\Resolver $layerResolver,
            \Magento\Framework\Registry $registry,
            \Magento\Catalog\Helper\Category $categoryHelper,
            array $data = []
        ) {
            $this->_coreRegistry = $registry;
            parent::__construct($context, $data);
        }

        /**
         * @return boolean
         */
        public function showDoorConfigurator()
        {

            die(var_dump($this->getCurrentCategory()->getData('name')));
            return false;
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
