<?php
    namespace Wkdcode\GarageModule\Model\Attribute\Source;

    use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

    class Door extends AbstractSource
    {
        /**
         * Get all options
         * @return array
         */
        public function getAllOptions()
        {
            return [];
            if (!$this->_options) {
                $this->_options = [
                    ['label' => __('Cotton'), 'value' => 'cotton'],
                    ['label' => __('Leather'), 'value' => 'leather'],
                    ['label' => __('Silk'), 'value' => 'silk'],
                    ['label' => __('Denim'), 'value' => 'denim'],
                    ['label' => __('Fur'), 'value' => 'fur'],
                    ['label' => __('Wool'), 'value' => 'wool'],
                ];
            }
            return $this->_options;
        }
    }
