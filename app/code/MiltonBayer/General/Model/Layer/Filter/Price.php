<?php
    namespace MiltonBayer\General\Model\Layer\Filter;

    class Price extends \Magento\CatalogSearch\Model\Layer\Filter\Price {

        /**
         * @var \Magento\Catalog\Model\Layer\Filter\DataProvider\Price
         */
        private $dataProvider;

        /**
         * @var array
         */
        private $facets = [
            '0_400' => ['value' => '0_400', 'count' => '1'],
            '400_500' => ['value' => '400_500', 'count' => '1'],
            '500_600' => ['value' => '500_600', 'count' => '1'],
            '600_700' => ['value' => '600_700', 'count' => '1'],
            '700_800' => ['value' => '700_800', 'count' => '1'],
            '800_900' => ['value' => '800_900', 'count' => '1'],
            '900_*' => ['value' => '900_*', 'count' => '1'],
        ];

        /**
         * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory
         * @param \Magento\Store\Model\StoreManagerInterface $storeManager
         * @param \Magento\Catalog\Model\Layer $layer
         * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
         * @param \Magento\Catalog\Model\ResourceModel\Layer\Filter\Price $resource
         * @param \Magento\Customer\Model\Session $customerSession
         * @param \Magento\Framework\Search\Dynamic\Algorithm $priceAlgorithm
         * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
         * @param \Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory $algorithmFactory
         * @param \Magento\Catalog\Model\Layer\Filter\DataProvider\PriceFactory $dataProviderFactory
         * @param array $data
         * @SuppressWarnings(PHPMD.ExcessiveParameterList)
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function __construct(
            \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
            \Magento\Store\Model\StoreManagerInterface $storeManager,
            \Magento\Catalog\Model\Layer $layer,
            \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
            \Magento\Catalog\Model\ResourceModel\Layer\Filter\Price $resource,
            \Magento\Customer\Model\Session $customerSession,
            \Magento\Framework\Search\Dynamic\Algorithm $priceAlgorithm,
            \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
            \Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory $algorithmFactory,
            \Magento\Catalog\Model\Layer\Filter\DataProvider\PriceFactory $dataProviderFactory,
            array $data = []
        ) {
            $this->_requestVar = 'price';
            $this->priceCurrency = $priceCurrency;
            $this->resource = $resource;
            $this->customerSession = $customerSession;
            $this->priceAlgorithm = $priceAlgorithm;
            parent::__construct(
                $filterItemFactory,
                $storeManager,
                $layer,
                $itemDataBuilder,
                $resource,
                $customerSession,
                $priceAlgorithm,
                $priceCurrency,
                $algorithmFactory,
                $dataProviderFactory,
                $data
            );
            $this->dataProvider = $dataProviderFactory->create(['layer' => $this->getLayer()]);
        }

        /**
         * Apply price range filter
         *
         * @param \Magento\Framework\App\RequestInterface $request
         * @return $this
         * @SuppressWarnings(PHPMD.NPathComplexity)
         */
        public function apply(\Magento\Framework\App\RequestInterface $request)
        {
            /**
             * Filter must be string: $fromPrice-$toPrice
             */
            $filter = $request->getParam($this->getRequestVar());
            if (!$filter || is_array($filter)) {
                return $this;
            }

            $filterParams = explode(',', $filter);
            $filter = $this->dataProvider->validateFilter($filterParams[0]);
            if (!$filter) {
                return $this;
            }

            sort($filterParams);

            $to = null;
            $from = null;
            foreach($filterParams as $_filter) {
                $to_from = explode("-", $_filter);

                if( $from == null )  $from = $to_from[0];
                if( $to == null || $to_from[0] == $to)  $to = $to_from[1];
            }

            $this->getLayer()->getProductCollection()->addFieldToFilter(
                'price',
                ['from' => $from, 'to' => empty($to) || $from == $to ? $to : $to - self::PRICE_DELTA]
            );

            foreach($filterParams as $_filter) {
                $to_from = explode("-", $_filter);


                //// FIX HERE!
                $this->getLayer()->getState()->addFilter(
                    $this->_createItem($this->_renderRangeLabel($to_from[0], $to_from[1]), $_filter)
                );
            }

            return $this;
        }

        /**
         * Get data array for building attribute filter items
         *
         * @return array
         *
         * @SuppressWarnings(PHPMD.NPathComplexity)
         */
        protected function _getItemsData()
        {
            $attribute = $this->getAttributeModel();
            $this->_requestVar = $attribute->getAttributeCode();

            $data = [];
            if (count($this->facets) > 1) { // two range minimum
                foreach ($this->facets as $key => $aggregation) {
                    $count = $aggregation['count'];
                    if (strpos($key, '_') === false) {
                        continue;
                    }
                    $data[] = $this->prepareData($key, $count);
                }
            }

            return $data;
        }

        /**
         * @param string $key
         * @param int $count
         * @return array
         */
        private function prepareData($key, $count)
        {
            list($from, $to) = explode('_', $key);
            if ($from == '*') {
                $from = $this->getFrom($to);
            }
            if ($to == '*') {
                $to = $this->getTo($to);
            }
            $label = $this->_renderRangeLabel($from, $to);
            $value = $from . '-' . $to . $this->dataProvider->getAdditionalRequestData();

            $data = [
                'label' => $label,
                'value' => $value,
                'count' => $count,
                'from' => $from,
                'to' => $to,
            ];

            return $data;
        }
    }
