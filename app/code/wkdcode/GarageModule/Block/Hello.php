<?php

namespace wkdcode\GarageModule\Block;

use Magento\Framework\View\Element\Template;
use wkdcode\GarageModule\Model\ResourceModel\Door\Collection;
use wkdcode\GarageModule\Model\ResourceModel\Door\CollectionFactory;

class Hello extends Template
{
    private $collectionFactory;

    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return \wkdcode\GarageModule\Model\Door[]
     */
    public function getItems()
    {
        return $this->collectionFactory->create()->getItems();
    }
}
