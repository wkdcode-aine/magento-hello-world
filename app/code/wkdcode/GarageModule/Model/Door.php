<?php

namespace Wkdcode\GarageModule\Model;

use Magento\Framework\Model\AbstractModel;

class Door extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Wkdcode\GarageModule\Model\ResourceModel\Door::class);
    }
}
