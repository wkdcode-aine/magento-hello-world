<?php
    namespace MiltonBayer\General\Block\Product;

    use MiltonBayer\General\Block\Product\ProductList\Toolbar;
    use Magento\Catalog\Model\ResourceModel\Product\Collection;

    class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
    {
        /**
         * Retrieve loaded product collection
         *
         * The goal of this method is to choose whether the existing collection should be returned
         * or a new one should be initialized.
         *
         * It is not just a caching logic, but also is a real logical check
         * because there are two ways how collection may be stored inside the block:
         *   - Product collection may be passed externally by 'setCollection' method
         *   - Product collection may be requested internally from the current Catalog Layer.
         *
         * And this method will return collection anyway,
         * even when it did not pass externally and therefore isn't cached yet
         *
         * @return AbstractCollection
         */
        protected function _getProductCollection()
        {
            if ($this->_productCollection === null) {
                $this->_productCollection = $this->initializeProductCollection();
            }

            return $this->_productCollection;
        }

        /**
         * Configures product collection from a layer and returns its instance.
         *
         * Also in the scope of a product collection configuration, this method initiates configuration of Toolbar.
         * The reason to do this is because we have a bunch of legacy code
         * where Toolbar configures several options of a collection and therefore this block depends on the Toolbar.
         *
         * This dependency leads to a situation where Toolbar sometimes called to configure a product collection,
         * and sometimes not.
         *
         * To unify this behavior and prevent potential bugs this dependency is explicitly called
         * when product collection initialized.
         *
         * @return Collection
         */
        private function initializeProductCollection()
        {
            $layer = $this->getLayer();
            /* @var $layer \Magento\Catalog\Model\Layer */
            if ($this->getShowRootCategory()) {
                $this->setCategoryId($this->_storeManager->getStore()->getRootCategoryId());
            }

            // if this is a product view page
            if ($this->_coreRegistry->registry('product')) {
                // get collection of categories this product is associated with
                $categories = $this->_coreRegistry->registry('product')
                    ->getCategoryCollection()->setPage(1, 1)
                    ->load();
                // if the product is associated with any category
                if ($categories->count()) {
                    // show products from this category
                    $this->setCategoryId(current($categories->getIterator())->getId());
                }
            }

            $origCategory = null;
            if ($this->getCategoryId()) {
                try {
                    $category = $this->categoryRepository->get($this->getCategoryId());
                } catch (NoSuchEntityException $e) {
                    $category = null;
                }

                if ($category) {
                    $origCategory = $layer->getCurrentCategory();
                    $layer->setCurrentCategory($category);
                }
            }
            $collection = $layer->getProductCollection();

            $this->prepareSortableFieldsByCategory($layer->getCurrentCategory());

            if ($origCategory) {
                $layer->setCurrentCategory($origCategory);
            }

            $toolbar = $this->getToolbarBlock();
            $this->configureToolbar($toolbar, $collection);

            $this->_eventManager->dispatch(
                'catalog_block_product_list_collection',
                ['collection' => $collection]
            );

            return $collection;
        }

        /**
         * Prepare Sort By fields from Category Data
         *
         * @param \Magento\Catalog\Model\Category $category
         * @return \Magento\Catalog\Block\Product\ListProduct
         */
        public function prepareSortableFieldsByCategory($category)
        {
            if (!$this->getAvailableOrders()) {
                $this->setAvailableOrders($category->getAvailableSortByOptions());
            }

            $availableOrders = $this->getAvailableOrders();
            if (!$this->getSortBy()) {
                $categorySortBy = $this->getDefaultSortBy() ?: $category->getDefaultSortBy();
                if ($categorySortBy) {
                    if (!$availableOrders) {
                        $availableOrders = $this->_getConfig()->getAttributeUsedForSortByArray();
                    }
                    if (isset($availableOrders[$categorySortBy])) {
                        $this->setSortBy($categorySortBy);
                    }
                }
            }

            return $this;
        }


        /**
         * Configures the Toolbar block with options from this block and configured product collection.
         *
         * The purpose of this method is the one-way sharing of different sorting related data
         * between this block, which is responsible for product list rendering,
         * and the Toolbar block, whose responsibility is a rendering of these options.
         *
         * @param ProductList\Toolbar $toolbar
         * @param Collection $collection
         * @return void
         */
        private function configureToolbar(Toolbar $toolbar, Collection $collection)
        {
            // use sortable parameters
            $orders = $this->getAvailableOrders();
            if ($orders) {
                $toolbar->setAvailableOrders($orders);
            }
            $sort = $this->getSortBy();
            if ($sort) {
                $toolbar->setDefaultOrder($sort);
            }
            $dir = $this->getDefaultDirection();
            if ($dir) {
                $toolbar->setDefaultDirection($dir);
            }
            $modes = $this->getModes();
            if ($modes) {
                $toolbar->setModes($modes);
            }

            // set collection to toolbar and apply sort
            $toolbar->setCollection($collection);
            $this->setChild('toolbar', $toolbar);
        }
    }
