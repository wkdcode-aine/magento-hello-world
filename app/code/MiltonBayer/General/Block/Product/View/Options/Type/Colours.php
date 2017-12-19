<?php
    namespace MiltonBayer\General\Block\Product\View\Options\Type;

    use MiltonBayer\General\Api\Data\ProductCustomOptionInterface;

    class Colours extends \Magento\Catalog\Block\Product\View\Options\AbstractOptions
    {
        /**
         * Return html for control element
         *
         * @return string
         */
        public function getValuesHtml()
        {
            $_option = $this->getOption();
            $type = $_option->getType();

            $configValue = $this->getProduct()->getPreconfiguredValues()->getData('options/' . $_option->getId());
            $store = $this->getProduct()->getStore();

            if( $type == ProductCustomOptionInterface::OPTION_TYPE_COLOUR_SWATCH ) {
                $selectHtml = '<div class="options-list nested" id="options-' . $_option->getId() . '-list">';
                $require = $_option->getIsRequire() ? ' required' : '';
                $arraySign = '';

                $class = 'radio admin__control-radio js-colour-value product-custom-option';
                if (!$_option->getIsRequire()) {
                    $selectHtml .= '<div class="field choice admin__field admin__field-option colour-swatch js-colour-swatch">';
                        $selectHtml .= '<input type="radio" id="options_' . $_option->getId() . '" class="' . $class . '" name="options[' . $_option->getId() . ']"' . ' data-selector="options[' . $_option->getId() . ']"' . ' value="" checked="checked" />';
                        $selectHtml .= '<label class="label admin__field-label" for="options_' . $_option->getId() . '"><span>' . __('None') . '</span></label>';
                    $selectHtml .= '</div>';
                }

                $count = 1;
                foreach ($_option->getValues() as $_value) {
                    if( $count == 1 && $configValue == null ) {
                        $configValue = $_value->getId();
                    }
                    $count++;

                    $priceStr = $this->_formatPrice(
                        [
                            'is_percent' => $_value->getPriceType() == 'percent',
                            'pricing_value' => $_value->getPrice($_value->getPriceType() == 'percent'),
                        ]
                    );

                    $active = '';
                    $htmlValue = $_value->getOptionTypeId();
                    if ($arraySign) {
                        $checked = is_array($configValue) && in_array($htmlValue, $configValue) ? 'checked' : '';
                    } else {
                        $checked = $configValue == $htmlValue ? 'checked' : '';
                    }

                    if( $checked == 'checked' )  $active = ' active';

                    $dataSelector = 'options[' . $_option->getId() . ']';
                    if ($arraySign) {
                        $dataSelector .= '[' . $htmlValue . ']';
                    }

                    $input_id = 'options_' . $_option->getId() . '_' . $count;
                    $input_name = 'options[' . $_option->getId() . ']' . $arraySign;
                    $price = $this->pricingHelper->currencyByStore($_value->getPrice(true), $store, false);

                    $selectHtml .= '<div class="field choice admin__field admin__field-option colour-swatch js-colour-swatch' . $require . $active . '" style="background: #' . $_value->getColourCode() . '">';
                        $selectHtml .= '<input type="radio" class="' . $class . $require . '" name="' . $input_name . '" id="' . $input_id . '" value="' . $htmlValue . '" ' . $checked . ' data-selector="' . $dataSelector . '" price="' . $price . '" />';
                    $selectHtml .= '</div>';
                }
                $selectHtml .= '</div>';

                return $selectHtml;
            }

            return;
        }
    }
