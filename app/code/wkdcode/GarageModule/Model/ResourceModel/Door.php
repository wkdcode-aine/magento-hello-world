<?php

namespace Wkdcode\GarageModule\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Door extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('garage_door', 'id');
    }
}
