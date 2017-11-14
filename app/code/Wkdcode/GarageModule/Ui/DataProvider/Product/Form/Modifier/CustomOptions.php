<?php
    namespace Wkdcode\GarageModule\Ui\DataProvider\Product\Form\Modifier;

    use Magento\Catalog\Model\Config\Source\Product\Options\Price as ProductOptionsPrice;
    use Magento\Catalog\Model\Locator\LocatorInterface;
    use Magento\Catalog\Model\ProductOptions\ConfigInterface;
    use Magento\Framework\Stdlib\ArrayManager;
    use Magento\Framework\UrlInterface;
    use Magento\Store\Model\StoreManagerInterface;
    use Magento\Ui\Component\Container;
    use Magento\Ui\Component\DynamicRows;
    use Magento\Ui\Component\Form\Element\DataType\Text;
    use Magento\Ui\Component\Form\Element\Select;
    use Magento\Ui\Component\Form\Field;

    use Wkdcode\GarageModule\Model\Config\Source\Product\Options\Options as ProductOptionsOptions;


    class CustomOptions extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions
    {

        const FIELD_CONDITIONAL_ON_NAME = 'conditional_on';

        /**
         * @param LocatorInterface $locator
         * @param StoreManagerInterface $storeManager
         * @param ConfigInterface $productOptionsConfig
         * @param ProductOptionsPrice $productOptionsPrice
         * @param ProductOptionsOptions $productOptionsOptions
         * @param UrlInterface $urlBuilder
         * @param ArrayManager $arrayManager
         */
        public function __construct(
            LocatorInterface $locator,
            StoreManagerInterface $storeManager,
            ConfigInterface $productOptionsConfig,
            ProductOptionsPrice $productOptionsPrice,
            ProductOptionsOptions $productOptionsOptions,
            UrlInterface $urlBuilder,
            ArrayManager $arrayManager
        ) {
            $this->locator = $locator;
            $this->storeManager = $storeManager;
            $this->productOptionsConfig = $productOptionsConfig;
            $this->productOptionsPrice = $productOptionsPrice;
            $this->productOptionsOptions = $productOptionsOptions;
            $this->urlBuilder = $urlBuilder;
            $this->arrayManager = $arrayManager;
        }

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
            $options = [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'imports' => [
                                'optionId' => '${ $.provider }:${ $.parentScope }.option_id',
                                'optionTypeId' => '${ $.provider }:${ $.parentScope }.option_type_id',
                                'isUseDefault' => '${ $.provider }:${ $.parentScope }.is_use_default'
                            ],
                            'service' => [
                                'template' => 'Magento_Catalog/form/element/helper/custom-option-type-service',
                            ],
                        ],
                    ],
                ],
            ];

            return [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'addButtonLabel' => __('Add Value'),
                            'componentType' => DynamicRows::NAME,
                            'component' => 'Magento_Ui/js/dynamic-rows/dynamic-rows',
                            'additionalClasses' => 'admin__field-wide',
                            'deleteProperty' => static::FIELD_IS_DELETE,
                            'deleteValue' => '1',
                            'renderDefaultRecord' => false,
                            'sortOrder' => $sortOrder,
                        ],
                    ],
                ],
                'children' => [
                    'record' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'componentType' => Container::NAME,
                                    'component' => 'Magento_Ui/js/dynamic-rows/record',
                                    'positionProvider' => static::FIELD_SORT_ORDER_NAME,
                                    'isTemplate' => true,
                                    'is_collection' => true,
                                ],
                            ],
                        ],
                        'children' => [
                            static::FIELD_TITLE_NAME => $this->getTitleFieldConfig(
                                10,
                                $this->locator->getProduct()->getStoreId() ? $options : []
                            ),
                            static::FIELD_PRICE_NAME => $this->getPriceFieldConfigForSelectType(20),
                            static::FIELD_PRICE_TYPE_NAME => $this->getPriceTypeFieldConfig(30, ['fit' => true]),
                            static::FIELD_CONDITIONAL_ON_NAME => $this->getConditionalFieldConfig(35),
                            static::FIELD_SKU_NAME => $this->getSkuFieldConfig(40),
                            static::FIELD_SORT_ORDER_NAME => $this->getPositionFieldConfig(50),
                            static::FIELD_IS_DELETE => $this->getIsDeleteFieldConfig(60)
                        ]
                    ]
                ]
            ];
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
            return [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Conditional On'),
                            'componentType' => Field::NAME,
                            'formElement' => Select::NAME,
                            'dataScope' => static::FIELD_CONDITIONAL_ON_NAME,
                            'dataType' => Text::NAME,
                            'options' => $this->productOptionsOptions->toOptionArray(),
                            'sortOrder' => $sortOrder
                        ],
                    ],
                ],
            ];
        }

        /**
         * Get config for "Price" field for select type.
         *
         * @param int $sortOrder
         * @return array
         */
        private function getPriceFieldConfigForSelectType(int $sortOrder)
        {
            $priceFieldConfig = $this->getPriceFieldConfig($sortOrder);
            $priceFieldConfig['arguments']['data']['config']['template'] = 'Magento_Catalog/form/field';

            return $priceFieldConfig;
        }
    }
