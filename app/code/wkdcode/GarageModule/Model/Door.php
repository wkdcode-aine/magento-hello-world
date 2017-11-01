<?php

namespace wkdcode\GarageModule\Model;

use Magento\Framework\Model\AbstractModel;

class Door extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\wkdcode\GarageModule\Model\ResourceModel\Door::class);
    }
}
