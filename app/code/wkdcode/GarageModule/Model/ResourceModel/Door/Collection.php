<?php

namespace Wkdcode\GarageModule\Model\ResourceModel\Door;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Wkdcode\GarageModule\Model\Door;
use Wkdcode\GarageModule\Model\ResourceModel\Door as DoorResource;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(Door::class, DoorResource::class);
    }
}
