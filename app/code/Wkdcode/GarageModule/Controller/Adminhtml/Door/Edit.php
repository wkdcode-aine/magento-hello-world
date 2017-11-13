<?php

namespace Wkdcode\GarageModule\Controller\Adminhtml\Door;

use Magento\Framework\Controller\ResultFactory;

class Edit extends \Wkdcode\GarageModule\Controller\Adminhtml\Door
{
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Garage Door Type'));

        return $resultPage;
    }
}
