<?php

namespace Wkdcode\GarageModule\Controller\Adminhtml\Door;

use Wkdcode\GarageModule\Model\DoorFactory;

class Save extends \Magento\Backend\App\Action
{
    private $doorFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        DoorFactory $doorFactory
    ) {
        $this->doorFactory = $doorFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->doorFactory->create()
            ->setData($this->getRequest()->getPostValue()['general'])
            ->save();
        return $this->resultRedirectFactory->create()->setPath('wkdcode/index/index');
    }
}
