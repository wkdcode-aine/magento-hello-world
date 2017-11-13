<?php
    namespace Wkdcode\GarageModule\Controller\Adminhtml;

    class Door extends \Magento\Backend\App\Action
    {
        /**
         * @param Action\Context $context
         */
        public function __construct(
            \Magento\Backend\App\Action\Context $context
        ) {
            parent::__construct($context);
        }
    }
