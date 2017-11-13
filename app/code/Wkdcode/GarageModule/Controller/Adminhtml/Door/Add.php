<?php

namespace Wkdcode\GarageModule\Controller\Adminhtml\Door;

use Magento\Framework\Controller\ResultFactory;

// class Add extends \Wkdcode\GarageModule\Controller\Adminhtml\Door
class Add extends \Magento\Backend\App\Action
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('Add Garage Door Type'));

        return $resultPage;
    }
}
