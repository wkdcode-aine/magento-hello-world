<?php

namespace Wkdcode\GarageModule\Model;

use Magento\Framework\Model\AbstractModel;

class Door extends AbstractModel
{
    protected $_eventPrefix = 'garage_door';
    
    protected function _construct()
    {
        $this->_init(\Wkdcode\GarageModule\Model\ResourceModel\Door::class);
    }
}
