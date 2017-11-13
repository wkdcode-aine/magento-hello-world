<?php
    namespace Wkdcode\GarageModule\Block\Adminhtml\Grid\Door;

    class Form extends \Magento\Backend\Block\Widget\Form\Generic
    {

        /**
         * @param \Magento\Backend\Block\Template\Context $context
         * @param \Magento\Framework\Registry             $registry
         * @param \Magento\Framework\Data\FormFactory     $formFactory
         * @param array                                   $data
         */
        public function __construct(
            \Magento\Backend\Block\Template\Context $context,
            \Magento\Framework\Registry $registry,
            \Magento\Framework\Data\FormFactory $formFactory,
            \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
            array $data = []
        )
        {
            $this->_wysiwygConfig = $wysiwygConfig;
            parent::__construct($context, $registry, $formFactory, $data);
        }

        protected function _prepareForm()
        {

            $this->addAttribute();
            
            return parent::_prepareForm();
        }
    }
