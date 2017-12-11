<?php
    namespace MiltonBayer\General\Block\Product\ProductList;

    use Magento\Catalog\Helper\Product\ProductList;
    use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory;
    use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as OptionCollectionFactory;
    use Magento\Catalog\Model\Product\ProductList\Toolbar as ToolbarModel;
    use Magento\Catalog\Ui\Component\Listing\Filters;

    class Toolbar extends \Magento\Catalog\Block\Product\ProductList\Toolbar
    {
        /**
         * List of available Manufacturers
         *
         * @var array
         */
        protected $_availableManufacturers = null;

        /**
         * @var CollectionFactory
         */
        protected $attributeCollectionFactory;

        /**
         * @var OptionCollectionFactory
         */
        protected $optionCollectionFactory;

        /**
         * @var \Magento\Framework\App\RequestInterface $request
         */
        protected $request;

        /**
         * @param \Magento\Framework\View\Element\Template\Context $context
         * @param \Magento\Catalog\Model\Session $catalogSession
         * @param \Magento\Catalog\Model\Config $catalogConfig
         * @param ToolbarModel $toolbarModel
         * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
         * @param ProductList $productListHelper
         * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
         * @param CollectionFactory $attributeCollectionFactory
         * @param OptionCollectionFactory $optionCollectionFactory
         * @param array $data
         */
        public function __construct(
            \Magento\Framework\View\Element\Template\Context $context,
            \Magento\Catalog\Model\Session $catalogSession,
            \Magento\Catalog\Model\Config $catalogConfig,
            ToolbarModel $toolbarModel,
            \Magento\Framework\Url\EncoderInterface $urlEncoder,
            ProductList $productListHelper,
            \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
            CollectionFactory $attributeCollectionFactory,
            OptionCollectionFactory $optionCollectionFactory,
            \Magento\Framework\App\RequestInterface $request,
            array $data = []
        ) {
            $this->_attributeCollectionFactory = $attributeCollectionFactory;
            $this->optionCollectionFactory = $optionCollectionFactory;
            $this->_request = $request;

            parent::__construct($context, $catalogSession, $catalogConfig, $toolbarModel, $urlEncoder, $productListHelper, $postDataHelper, $data);
        }

        /**
         * Retrieve available Order fields list
         *
         * @return array
         */
        public function getAvailableOrders()
        {
            $this->loadAvailableOrders();
            return $this->_availableOrder;
        }

        /**
         * Compare defined order and direction fields with current order direction field
         *
         * @param string $order
         * @return bool
         */
        public function isOrderDirectionCurrent($order)
        {
            return $order == $this->getCurrentOrder() . '-' . $this->getCurrentDirection();
        }

        /**
         * Retrieve available Order fields list
         *
         * @return array
         */
        public function getAvailableOrderAndDirections()
        {
            $orderAndDirection = [];
            $directions = ['asc', 'desc'];
            foreach( $this->getAvailableOrders() as $index => $order ) {
                foreach( $directions as $dir ) {
                    $orderAndDirection[$index . '-' . $dir] = $order . '-' . $dir;
                }
            }

            return $orderAndDirection;
        }

        /**
         * Retrieve available Order fields list
         *
         * @return array
         */
        public function getAvailableManufacturers()
        {
            $this->loadAvailableManufacturers();
            return $this->_availableManufacturers;
        }

        /**
         * Return the selected manufacturer
         *
         * @return string
         */
        public function isCurrentManufacturer( $key )
        {
            $params = $this->_request->getParams();
            if( array_key_exists('manufacturer', $params) ) {
                return $params['manufacturer'] == $key;
            }

            return '';
        }

        /**
         * Load Available Orders
         *
         * @return $this
         */
        private function loadAvailableOrders()
        {
            $this->_availableOrder = ['price' => 'price', 'name' => 'name'];
            return $this;
        }

        /**
         * Load Available Manufacturers
         *
         * @return $this
         */
        private function loadAvailableManufacturers()
        {
            $attributeCollection = $this->_attributeCollectionFactory->create();
            $attribute =  $attributeCollection->addFieldToFilter('attribute_code', ['in' => 'manufacturer'])->getFirstItem();

            $optionCollectionFactory = $this->optionCollectionFactory->create();
            $options = $optionCollectionFactory->setStoreFilter()->setAttributeFilter($attribute->getData('attribute_id'));

            $this->_availableManufacturers = $options->toOptionArray();
            return $this;
        }

        /**
         * Get the default mode to show on the search
         * @return string
         */
        public function getDefaultMode()
        {
            return $this->_productListHelper->getDefaultViewMode($this->getModes());
        }
    }
