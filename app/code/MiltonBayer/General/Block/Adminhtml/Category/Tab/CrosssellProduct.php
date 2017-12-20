<?php
    namespace MiltonBayer\General\Block\Adminhtml\Category\Tab;

    class CrosssellProduct extends \Magento\Backend\Block\Widget\Grid\Extended
    {
        /**
         * Core registry
         *
         * @var \Magento\Framework\Registry
         */
        protected $_coreRegistry = null;

        /**
         * @var \Magento\Catalog\Model\ProductFactory
         */
        protected $_productFactory;

        /**
         * @param \Magento\Backend\Block\Template\Context $context
         * @param \Magento\Backend\Helper\Data $backendHelper
         * @param \Magento\Catalog\Model\ProductFactory $productFactory
         * @param \Magento\Framework\Registry $coreRegistry
         * @param array $data
         */
        public function __construct(
            \Magento\Backend\Block\Template\Context $context,
            \Magento\Backend\Helper\Data $backendHelper,
            \Magento\Catalog\Model\ProductFactory $productFactory,
            \Magento\Framework\Registry $coreRegistry,
            array $data = []
        ) {
            $this->_productFactory = $productFactory;
            $this->_coreRegistry = $coreRegistry;
            parent::__construct($context, $backendHelper, $data);
        }

        /**
         * @return void
         */
        protected function _construct()
        {
            parent::_construct();
            $this->setId('crosssell_category_product');
            $this->setDefaultSort('crosssell_category_product_id');
            $this->setUseAjax(true);
        }

        /**
         * @return array|null
         */
        public function getCategory()
        {
            return $this->_coreRegistry->registry('category');
        }

        /**
         * @return Grid
         */
        protected function _prepareCollection()
        {
            $collection = $this->_productFactory->create()->getCollection()->addAttributeToSelect(
                'name'
            )->addAttributeToSelect(
                'sku'
            )->addAttributeToSelect(
                'price'
            )->joinField(
                'product_id',
                'crosssell_category_product',
                'product_id',
                'product_id=entity_id',
                'category_id=' . (int)$this->getCategory()->getId(),
                'left'
            );
            $storeId = (int)$this->getRequest()->getParam('store', 0);
            if ($storeId > 0) {
                $collection->addStoreFilter($storeId);
            }
            $collection->addFieldToFilter('product_id', ['notnull' => true]);
            $this->setCollection($collection);

            return parent::_prepareCollection();
        }

        /**
         * @return Extended
         */
        protected function _prepareColumns()
        {
            if (!$this->getCategory()->getProductsReadonly()) {
                $this->addColumn(
                    'in_category',
                    [
                        'type' => 'checkbox',
                        'name' => 'in_category',
                        'values' => $this->_getSelectedProducts(),
                        'index' => 'entity_id',
                        'header_css_class' => 'col-select col-massaction',
                        'column_css_class' => 'col-select col-massaction'
                    ]
                );
            }
            $this->addColumn(
                'entity_id',
                [
                    'header' => __('ID'),
                    'sortable' => true,
                    'index' => 'entity_id',
                    'header_css_class' => 'col-id',
                    'column_css_class' => 'col-id'
                ]
            );
            $this->addColumn('name', ['header' => __('Name'), 'index' => 'name']);
            $this->addColumn('sku', ['header' => __('SKU'), 'index' => 'sku']);
            $this->addColumn(
                'price',
                [
                    'header' => __('Price'),
                    'type' => 'currency',
                    'currency_code' => (string)$this->_scopeConfig->getValue(
                        \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_BASE,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    ),
                    'index' => 'price'
                ]
            );
            $this->addColumn(
                'position',
                [
                    'header' => __('Position'),
                    'type' => 'number',
                    'index' => 'position',
                    'editable' => !$this->getCategory()->getProductsReadonly()
                ]
            );

            return parent::_prepareColumns();
        }

        /**
         * @return array
         */
        protected function _getSelectedProducts()
        {
            $products = $this->getRequest()->getPost('selected_products');
            if ($products === null) {
                $products = $this->getCategory()->getProductsPosition();
                return array_keys($products);
            }
            return $products;
        }
    }
