<?php
    namespace Wkdcode\GarageModule\Ui\DataProvider\Product\Form\Modifier;

    use Magento\Ui\Component\Form\Field;
    use Magento\Ui\Component\Form\Element\Select;
    use Magento\Ui\Component\Form\Element\DataType\Text;

    class CustomOptions extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions
    {

        const FIELD_CONDITIONAL_ON_NAME = 'conditional_on';
        /**
         * {@inheritdoc}
         * @since 101.0.0
         */
        public function modifyMeta(array $meta)
        {
            $this->meta = $meta;

            $this->createCustomOptionsPanel();

            return $this->meta;
        }

        /**
         * Get config for grid for "select" types
         *
         * @param int $sortOrder
         * @return array
         * @since 101.0.0
         */
        protected function getSelectTypeGridConfig($sortOrder)
        {
            $config = parent::getSelectTypeGridConfig($sortOrder);

            $conditional_sort_order = 35;

            $keys = array_keys( $config['children']['record']['children'] );
            $added = false;

            $children = [];
            for($i = 0; $i < count($keys); $i ++) {
                $input = $config['children']['record']['children'][$keys[$i]];

                if( !$added && $input['arguments']['data']['config']['sortOrder'] > $conditional_sort_order ) {
                    $children[static::FIELD_CONDITIONAL_ON_NAME] = $this->getConditionalFieldConfig($conditional_sort_order);
                    $added = true;
                }

                $children[$keys[$i]] = $input;
            }
            $config['children']['record']['children'] = $children;
            if( !$added )  $config['children']['record']['children'][static::FIELD_CONDITIONAL_ON_NAME] = $this->getConditionalFieldConfig($conditional_sort_order);

            return $config;
        }

        /**
         * Get config for "SKU" field
         *
         * @param int $sortOrder
         * @return array
         * @since 101.0.0
         */
        protected function getConditionalFieldConfig($sortOrder)
        {
            // $collectionFactory =
            return [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Conditional On'),
                            'componentType' => Field::NAME,
                            'formElement' => Select::NAME,
                            'dataScope' => static::FIELD_CONDITIONAL_ON_NAME,
                            'dataType' => Text::NAME,
                            'options' => ['optdata' => [['label' => 'Canopy', 'value' => 7], ['value' => 8, 'label' => 'Retractable']]],
                            'sortOrder' => $sortOrder,
                        ],
                    ],
                ],
            ];
        }
    }
