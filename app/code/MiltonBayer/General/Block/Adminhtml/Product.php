<?php
    namespace MiltonBayer\General\Block\Adminhtml;

    /**
     * @api
     * @since 100.0.2
     */
    class Product extends \Magento\Backend\Block\Widget\Container
    {
        /**
         * @var \Magento\Catalog\Model\ProductFactory
         */
        protected $_productFactory;

        /**
         * @param \Magento\Backend\Block\Widget\Context $context
         * @param \Magento\Catalog\Model\ProductFactory $productFactory
         * @param array $data
         */
        public function __construct(
            \Magento\Backend\Block\Widget\Context $context,
            \Magento\Catalog\Model\ProductFactory $productFactory,
            array $data = []
        ) {
            $this->_productFactory = $productFactory;

            parent::__construct($context, $data);
        }

        /**
         * Prepare button and grid
         *
         * @return \Magento\Catalog\Block\Adminhtml\Product
         */
        protected function _prepareLayout()
        {
            $addButtonProps = [
                'id' => 'add_new_product',
                'label' => __('Add Product'),
                'class' => 'add primary',
                'button_class' => '',
                'onclick' => "setLocation('" . $this->_getProductCreateUrl(\Magento\Catalog\Model\Product\Type::DEFAULT_TYPE) . "')",
            ];
            $this->buttonList->add('add_new', $addButtonProps);

            return parent::_prepareLayout();
        }

        /**
         * Retrieve product create url by specified product type
         *
         * @param string $type
         * @return string
         */
        protected function _getProductCreateUrl($type)
        {
            return $this->getUrl(
                'catalog/*/new',
                ['set' => $this->_productFactory->create()->getDefaultAttributeSetId(), 'type' => $type]
            );
        }

        /**
         * Check whether it is single store mode
         *
         * @return bool
         */
        public function isSingleStoreMode()
        {
            return $this->_storeManager->isSingleStoreMode();
        }
    }