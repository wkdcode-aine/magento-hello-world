<?php
    namespace MiltonBayer\General\Model;

    use Magento\Framework\Api\Search\SearchCriteriaInterface;
    use Magento\Framework\Api\Search\SearchInterface;
    use Magento\Framework\App\ScopeResolverInterface;
    use Magento\Framework\Search\Request\Builder;
    use Magento\Framework\Search\SearchEngineInterface;
    use Magento\Framework\Search\SearchResponseBuilder;

    class Search extends \Magento\Search\Model\Search
    {
        /**
         * @var Builder
         */
        private $requestBuilder;

        /**
         * @var ScopeResolverInterface
         */
        private $scopeResolver;

        /**
         * @var SearchEngineInterface
         */
        private $searchEngine;

        /**
         * @var SearchResponseBuilder
         */
        private $searchResponseBuilder;

        /**
         * @param Builder $requestBuilder
         * @param ScopeResolverInterface $scopeResolver
         * @param SearchEngineInterface $searchEngine
         * @param SearchResponseBuilder $searchResponseBuilder
         */
        public function __construct(
            Builder $requestBuilder,
            ScopeResolverInterface $scopeResolver,
            SearchEngineInterface $searchEngine,
            SearchResponseBuilder $searchResponseBuilder
        ) {
            $this->requestBuilder = $requestBuilder;
            $this->scopeResolver = $scopeResolver;
            $this->searchEngine = $searchEngine;
            $this->searchResponseBuilder = $searchResponseBuilder;
        }

        /**
         * {@inheritdoc}
         */
        public function search(SearchCriteriaInterface $searchCriteria)
        {
            $this->requestBuilder->setRequestName($searchCriteria->getRequestName());

            $scope = $this->scopeResolver->getScope()->getId();
            $this->requestBuilder->bindDimension('scope', $scope);

            foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
                foreach ($filterGroup->getFilters() as $filter) {
                    $this->addFieldToFilter($filter->getField(), $filter->getValue(), $filter->getConditionType());
                }
            }

            $this->requestBuilder->setFrom($searchCriteria->getCurrentPage() * $searchCriteria->getPageSize());
            $this->requestBuilder->setSize($searchCriteria->getPageSize());
            $request = $this->requestBuilder->create();
            $searchResponse = $this->searchEngine->search($request);

            return $this->searchResponseBuilder->build($searchResponse)
                ->setSearchCriteria($searchCriteria);
        }

        /**
         * Apply attribute filter to facet collection
         *
         * @param string $field
         * @param string|array|null $condition
         * @return $this
         */
        private function addFieldToFilter($field, $condition = null, $type = 'eq')
        {
            if( !is_array($condition) && strstr($condition, ',') ) {
                $condition = explode(',', $condition);
            }

            if (!is_array($condition) || !in_array(key($condition), ['from', 'to'], true)) {
                $this->requestBuilder->bind($field, $condition);
            } else {
                if (!empty($condition['from'])) {
                    $this->requestBuilder->bind("{$field}.from", $condition['from']);
                }
                if (!empty($condition['to'])) {
                    $this->requestBuilder->bind("{$field}.to", $condition['to']);
                }
            }

            return $this;
        }
    }