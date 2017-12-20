<?php
    namespace MiltonBayer\General\Block\Category;

    use Magento\Catalog\Model\ResourceModel\Product\Collection;

    class Crosssell extends \Magento\Catalog\Block\Product\AbstractProduct
    {
        /**
         * Core registry
         *
         * @var \Magento\Framework\Registry
         */
        protected $_coreRegistry = null;

        /**
         * Current category
         *
         * @var \agento\Catalog\Model\Category
         */
        protected $_currentCategory;

        /**
         * @var ImageBuilder
         */
        protected $imageBuilder;


        /**
         * @var \Magento\Catalog\Model\ProductFactory
         */
        protected $_productFactory;

        /**
         * @param \Magento\Framework\Registry $registry
         * @param ImageBuilder $imageBuilder
         * @param \Magento\Catalog\Model\ProductFactory $productFactory
         * @param array $data
         */
        public function __construct(
            \Magento\Catalog\Block\Product\Context $context,
            \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
            \Magento\Catalog\Model\ProductFactory $productFactory,
            \Magento\Framework\Registry $registry,
            array $data = []
        ) {
            $this->_coreRegistry = $registry;
            $this->imageBuilder = $imageBuilder;
            $this->_productFactory = $productFactory;
            parent::__construct($context, $data);
        }

        /**
         * Check if Showing Cross-sell has been enabled on category
         *
         * @return boolean
         */
        public function canShowCrosssell()
        {
            return $this->getCurrentCategory()->getData('show_crosssell_carousel');
        }

        /**
         * Get the title for the carousel
         *
         * @return string
         */
        public function getCarouselTitle()
        {
            return $this->getCurrentCategory()->getData('crosssell_carousel_title');
        }

        /**
         * Get the products that have been assigned to the carousel
         *
         * @return array
         */
        public function getCarouselProducts()
        {
            return $this->initializeProductCollection();
        }

        /**
         * Get the products assigned to the carousel
         *
         * @return Collection
         */
        private function initializeProductCollection()
        {
            $collection = $this->_productFactory->create()->getCollection()->addAttributeToSelect([
                'name',
                'sku',
                'price',
                'listing_title',
                'image',
                'small_image',
                'media_gallery',
                'short_description'
            ])->joinField(
                'product_id',
                'crosssell_category_product',
                'product_id',
                'product_id=entity_id',
                'category_id=' . (int)$this->getCurrentCategory()->getId(),
                'left'
            );
            $storeId = (int)$this->getRequest()->getParam('store', 0);
            if ($storeId > 0) {
                $collection->addStoreFilter($storeId);
            }
            $collection
                ->addFieldToFilter('product_id', ['notnull' => true])
                ->addMinimalPrice()
                ->addUrlRewrite();
            $this->setCollection($collection);

            return $collection;
        }

        /**
         * Retrieve current category model object
         *
         * @return \Magento\Catalog\Model\Category
         */
        public function getCurrentCategory()
        {
            if( empty($this->_currentCategory) ) {
                $this->_currentCategory = $this->_coreRegistry->registry('current_category');
            }
            return $this->_currentCategory;
        }

        /**
         * Retrieve product image
         *
         * @param \Magento\Catalog\Model\Product $product
         * @param string $imageId
         * @param array $attributes
         * @return \Magento\Catalog\Block\Product\Image
         */
        public function getImage($product, $imageId, $attributes = [])
        {
            return $this->imageBuilder->setProduct($product)
                ->setImageId($imageId)
                ->setAttributes($attributes)
                ->create();
        }
    }
