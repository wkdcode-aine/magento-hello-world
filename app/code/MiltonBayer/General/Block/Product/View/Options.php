<?php
    namespace MiltonBayer\General\Block\Product\View;

    class Options extends \Magento\Catalog\Block\Product\View\Options
    {
        /**
         * Get json representation of
         *
         * @return string
         */
        public function getJsonConfig()
        {
            $config = [];
            foreach ($this->getOptions() as $option) {
                /* @var $option \Magento\Catalog\Model\Product\Option */
                /** Custom Code here */
                if ($option->hasValues() || $option->hasColours()) {
                /** end custom code */
                    $tmpPriceValues = [];
                    foreach ($option->getValues() as $valueId => $value) {
                        $tmpPriceValues[$valueId] = $this->_getPriceConfiguration($value);
                    }
                    $priceValue = $tmpPriceValues;
                } else {
                    $priceValue = $this->_getPriceConfiguration($option);
                }
                $config[$option->getId()] = $priceValue;
            }

            $configObj = new \Magento\Framework\DataObject(
                [
                    'config' => $config,
                ]
            );

            //pass the return array encapsulated in an object for the other modules to be able to alter it eg: weee
            $this->_eventManager->dispatch('catalog_product_option_price_configuration_after', ['configObj' => $configObj]);

            $config=$configObj->getConfig();

            return $this->_jsonEncoder->encode($config);
        }
    }
