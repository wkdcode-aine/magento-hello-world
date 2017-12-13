<?php
    namespace MiltonBayer\General\Observer\Edit\Tab\Front;

    use Magento\Config\Model\Config\Source;
    use Magento\Framework\Module\Manager;
    use Magento\Framework\Event\ObserverInterface;

    class ProductAttributeFormBuildFrontTabObserver implements ObserverInterface
    {
        /**
         * @var \Magento\Config\Model\Config\Source\Yesno
         */
        protected $optionList;

        /**
         * @var \Magento\Framework\Module\Manager
         */
        protected $moduleManager;

        /**
         * @param Manager $moduleManager
         * @param Source\Yesno $optionList
         */
        public function __construct(Manager $moduleManager, Source\Yesno $optionList)
        {
            $this->optionList = $optionList;
            $this->moduleManager = $moduleManager;
        }

        /**
         * @param \Magento\Framework\Event\Observer $observer
         * @return void
         */
        public function execute(\Magento\Framework\Event\Observer $observer)
        {
            if (!$this->moduleManager->isOutputEnabled('Magento_LayeredNavigation')) {
                return;
            }

            /** @var \Magento\Framework\Data\Form\AbstractForm $form */
            $form = $observer->getForm();

            $fieldset = $form->getElement('front_fieldset');

            $fieldset->addField(
                'search_excludes_selected',
                'select',
                [
                    'name' => 'search_excludes_selected',
                    'label' => __("Select Excludes Items?"),
                    'title' => __('If enabled, selecting items from the search filter will exclude those items from the search results.'),
                    'note' => __('If enabled, selecting items from the search filter will exclude those items from the search results.'),
                    'values' => $this->optionList->toOptionArray(),
                ]
            );

        }
    }
