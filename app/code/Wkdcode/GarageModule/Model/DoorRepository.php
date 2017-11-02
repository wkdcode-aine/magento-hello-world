<?php

namespace Wkdcode\GarageModule\Model;

use Wkdcode\GarageModule\Api\DoorRepositoryInterface;
use Wkdcode\GarageModule\Model\ResourceModel\Door\CollectionFactory;

class DoorRepository implements DoorRepositoryInterface
{
    private $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function getList()
    {
        return $this->collectionFactory->create()->getItems();
    }
}
