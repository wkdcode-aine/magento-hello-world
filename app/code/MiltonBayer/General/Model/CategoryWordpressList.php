<?php

namespace MiltonBayer\General\Model;

use MiltonBayer\General\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Store\Model\StoreManager;

class CategoryWordpressList
{
    /** @var CollectionFactory */
    private $categoryCollectionFactory;

    /** @var StoreManager */
    private $storeManager;

    /**
     * @param CollectionFactory $categoryCollectionFactory
     * @param StoreManager $storeManager
     */
    public function __construct(
        CollectionFactory $categoryCollectionFactory,
        StoreManager $storeManager
    ) {
        $this->storeManager = $storeManager->getStore();
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

	public function getWordpressList()
	{
        $collection = $this->categoryCollectionFactory
            ->create()
            ->addAttributeToSelect(['name', 'image'])
            ->addAttributeToFilter('is_active', ['eq' => 1])
            ->setStoreId($this->storeManager['store_id']);

		return $collection->getItems();
	}
}
