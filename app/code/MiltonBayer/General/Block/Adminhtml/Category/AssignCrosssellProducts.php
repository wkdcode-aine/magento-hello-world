<?php
    namespace MiltonBayer\General\Block\Adminhtml\Category;

    class AssignCrosssellProducts extends \Magento\Backend\Block\Template
    {
        /**
         * @var \Magento\Catalog\Block\Adminhtml\Category\Tab\Product
         */
        protected $blockGrid;

        /**
         * Block template
         *
         * @var string
         */
        protected $_template = 'catalog/category/edit/assign_crosssell_products.phtml';

        /**
         * @var \Magento\Framework\Registry
         */
        protected $registry;

        /**
         * AssignCrosssellProducts constructor.
         *
         * @param \Magento\Backend\Block\Template\Context $context
         * @param \Magento\Framework\Registry $registry
         * @param array $data
         */
        public function __construct(
            \Magento\Backend\Block\Template\Context $context,
            \Magento\Framework\Registry $registry,
            array $data = []
        ) {
            $this->registry = $registry;
            parent::__construct($context, $data);
        }

        /**
         * Retrieve instance of grid block
         *
         * @return \Magento\Framework\View\Element\BlockInterface
         * @throws \Magento\Framework\Exception\LocalizedException
         */
        public function getBlockGrid()
        {
            if (null === $this->blockGrid) {
                $this->blockGrid = $this->getLayout()->createBlock(
                    \MiltonBayer\General\Block\Adminhtml\Category\Tab\CrosssellProduct::class,
                    'category.crosssell_product.grid'
                );
            }
            return $this->blockGrid;
        }

        /**
         * Return HTML of grid block
         *
         * @return string
         */
        public function getGridHtml()
        {
            return $this->getBlockGrid()->toHtml();
        }

        /**
         * Retrieve current category instance
         *
         * @return array|null
         */
        public function getCategory()
        {
            return $this->registry->registry('category');
        }

    }
