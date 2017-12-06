<?php
    namespace MiltonBayer\General\Model\ResourceModel\Fulltext;

    use Magento\CatalogSearch\Model\Search\RequestGenerator;
    use Magento\Framework\EntityManager\MetadataPool;
    use Magento\Framework\App\ObjectManager;
    use Magento\Framework\Api\Search\SearchResultFactory;
    use Magento\Framework\Search\Adapter\Mysql\TemporaryStorage;
    use Magento\Catalog\Model\ResourceModel\Product\Collection\ProductLimitationFactory;

    class Collection extends \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection
    {
        /**
         * @var \Magento\Framework\Api\Search\SearchCriteriaBuilder
         */
        private $searchCriteriaBuilder;

        /**
         * @var \Magento\Framework\Api\Search\SearchResultInterface
         */
        private $searchResult;

        /**
         * @var \Magento\Framework\Api\FilterBuilder
         */
        private $filterBuilder;

        /**
         * @var \Magento\Framework\Api\Search\FilterGroup
         */
        private $filterGroup;

        /**
         * @deprecated 100.1.0
         * @return \Magento\Framework\Api\Search\SearchCriteriaBuilder
         */
        private function getSearchCriteriaBuilder()
        {
            if ($this->searchCriteriaBuilder === null) {
                $this->searchCriteriaBuilder = ObjectManager::getInstance()
                    ->get(\Magento\Framework\Api\Search\SearchCriteriaBuilder::class);
            }
            return $this->searchCriteriaBuilder;
        }

        /**
         * @deprecated 100.1.0
         * @return \Magento\Framework\Api\FilterBuilder
         */
        private function getFilterBuilder()
        {
            if ($this->filterBuilder === null) {
                $this->filterBuilder = ObjectManager::getInstance()->get(\Magento\Framework\Api\FilterBuilder::class);
            }
            return $this->filterBuilder;
        }

        /**
         * Apply attribute filter to facet collection
         *
         * @param string $field
         * @param null $condition
         * @return $this
         */
        public function addFieldToFilter($field, $condition = null)
        {
            if ($this->searchResult !== null) {
                throw new \RuntimeException('Illegal state');
            }

            if(!is_array($condition) && strstr($condition, ',')) {
                $condition = explode(",", $condition);
            }

            $this->getSearchCriteriaBuilder();
            $this->getFilterBuilder();
            if (!is_array($condition) || !in_array(key($condition), ['from', 'to'], true)) {
                $this->filterBuilder->setField($field);
                $this->filterBuilder->setValue($condition);
                $this->searchCriteriaBuilder->addFilter($this->filterBuilder->create());
            } else {
                if (!empty($condition['from'])) {
                    $this->filterBuilder->setField("{$field}.from");
                    $this->filterBuilder->setValue($condition['from']);
                    $this->searchCriteriaBuilder->addFilter($this->filterBuilder->create());
                }
                if (!empty($condition['to'])) {
                    $this->filterBuilder->setField("{$field}.to");
                    $this->filterBuilder->setValue($condition['to']);
                    $this->searchCriteriaBuilder->addFilter($this->filterBuilder->create());
                }
            }
            return $this;
        }
    }
