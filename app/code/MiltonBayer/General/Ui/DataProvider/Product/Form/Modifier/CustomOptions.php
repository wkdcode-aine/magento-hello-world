<?php
    namespace MiltonBayer\General\Ui\DataProvider\Product\Form\Modifier;

    use Magento\Catalog\Model\Config\Source\Product\Options\Price as ProductOptionsPrice;
    use Magento\Catalog\Model\Locator\LocatorInterface;
    use Magento\Catalog\Model\ProductOptions\ConfigInterface;
    use Magento\Framework\Stdlib\ArrayManager;
    use Magento\Framework\UrlInterface;
    use Magento\Store\Model\StoreManagerInterface;
    use Magento\Ui\Component\Container;
    use Magento\Ui\Component\DynamicRows;
    use Magento\Ui\Component\Form\Element\DataType\Text;
    use Magento\Ui\Component\Form\Element\Checkbox;
    use Magento\Ui\Component\Form\Element\Input;
    use Magento\Ui\Component\Form\Element\Select;
    use Magento\Ui\Component\Form\Field;
    use Magento\Ui\Component\Form\Fieldset;


    use MiltonBayer\General\Model\Config\Source\Product\Options\Options as ProductOptionsOptions;


    class CustomOptions extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions
    {

        const FIELD_IS_SHOW_PRODUCT_PAGE_NAME = 'is_show_product_page';
        const FIELD_IS_SHOW_DESIGN_A_DOOR_NAME = 'is_show_design_a_door';
        const FIELD_IS_REQUIRED_DESIGN_A_DOOR_NAME = 'is_required_design_a_door';
        const FIELD_CONDITIONAL_ON_NAME = 'conditional_on';

        const GRID_TYPE_COLOURS_NAME = 'colours';
        const FIELD_COLOUR_CODE_OPTION_TITLE = 'colour_code';

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
        public function modifyData(array $data)
        {
            $options = [];
            $productOptions = $this->locator->getProduct()->getOptions() ?: [];

            /** @var \Magento\Catalog\Model\Product\Option $option */
            foreach ($productOptions as $index => $option) {
                $optionData = $option->getData();
                $optionData[static::FIELD_IS_USE_DEFAULT] = !$option->getData(static::FIELD_STORE_TITLE_NAME);
                $options[$index] = $this->formatPriceByPath(static::FIELD_PRICE_NAME, $optionData);
                $values = $option->getValues() ?: [];
                $is_colour = $option->hasValues() == false && $option->hasColours() == true;

                foreach ($values as $value) {
                    $value->setData(static::FIELD_IS_USE_DEFAULT, !$value->getData(static::FIELD_STORE_TITLE_NAME));
                }
                /** @var \Magento\Catalog\Model\Product\Option $value */
                foreach ($values as $value) {
                    $options[$index][($is_colour ? static::GRID_TYPE_COLOURS_NAME : static::GRID_TYPE_SELECT_NAME)][] = $this->formatPriceByPath(
                        static::FIELD_PRICE_NAME,
                        $value->getData()
                    );
                }
            }

            return array_replace_recursive(
                $data,
                [
                    $this->locator->getProduct()->getId() => [
                        static::DATA_SOURCE_DEFAULT => [
                            static::FIELD_ENABLE => 1,
                            static::GRID_OPTIONS_NAME => $options
                        ]
                    ]
                ]
            );
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
         * Get config for container with common fields for any type
         *
         * @param int $sortOrder
         * @return array
         * @since 101.0.0
         */
        protected function getCommonContainerConfig($sortOrder)
        {
            $commonContainer = [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'componentType' => Container::NAME,
                            'formElement' => Container::NAME,
                            'component' => 'Magento_Ui/js/form/components/group',
                            'breakLine' => false,
                            'showLabel' => false,
                            'additionalClasses' => 'admin__field-group-columns admin__control-group-equal',
                            'sortOrder' => $sortOrder,
                        ],
                    ],
                ],
                'children' => [
                    static::FIELD_OPTION_ID => $this->getOptionIdFieldConfig(10),
                    static::FIELD_TITLE_NAME => $this->getTitleFieldConfig(
                        20,
                        [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'label' => __('Option Title'),
                                        'component' => 'Magento_Catalog/component/static-type-input',
                                        'valueUpdate' => 'input',
                                        'imports' => [
                                            'optionId' => '${ $.provider }:${ $.parentScope }.option_id',
                                            'isUseDefault' => '${ $.provider }:${ $.parentScope }.is_use_default'
                                        ]
                                    ],
                                ],
                            ],
                        ]
                    ),
                    static::FIELD_TYPE_NAME => $this->getTypeFieldConfig(30),
                    static::FIELD_IS_REQUIRE_NAME => $this->getIsRequireFieldConfig(40),
                    static::FIELD_IS_SHOW_PRODUCT_PAGE_NAME => $this->getIsShowProductPageFieldConfig(50),
                    static::FIELD_IS_SHOW_DESIGN_A_DOOR_NAME => $this->getIsShowDesignADoorFieldConfig(60),
                    static::FIELD_IS_REQUIRED_DESIGN_A_DOOR_NAME => $this->getIsRequireDesignADoorFieldConfig(70),
                ]
            ];

            if ($this->locator->getProduct()->getStoreId()) {
                $useDefaultConfig = [
                    'service' => [
                        'template' => 'Magento_Catalog/form/element/helper/custom-option-service',
                    ]
                ];
                $titlePath = $this->arrayManager->findPath(static::FIELD_TITLE_NAME, $commonContainer, null)
                    . static::META_CONFIG_PATH;
                $commonContainer = $this->arrayManager->merge($titlePath, $commonContainer, $useDefaultConfig);
            }

            return $commonContainer;
        }

        /**
         * Get config for "Option Type" field
         *
         * @param int $sortOrder
         * @return array
         * @since 101.0.0
         */
        protected function getTypeFieldConfig($sortOrder)
        {
            $types = parent::getTypeFieldConfig($sortOrder);

            $types['arguments']['data']['config']['component'] = 'MiltonBayer_General/js/custom-options-type';
            $types['arguments']['data']['config']['groupsConfig']['colours'] = [
                'values' => ['colour_swatch'],
                'indexes' => [
                    static::GRID_TYPE_COLOURS_NAME
                ]
            ];

            return $types;
        }

        /**
         * Get config for the whole grid
         *
         * @param int $sortOrder
         * @return array
         * @since 101.0.0
         */
        protected function getOptionsGridConfig($sortOrder)
        {
            return [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'addButtonLabel' => __('Add Option'),
                            'componentType' => DynamicRows::NAME,
                            'component' => 'Magento_Catalog/js/components/dynamic-rows-import-custom-options',
                            'template' => 'ui/dynamic-rows/templates/collapsible',
                            'additionalClasses' => 'admin__field-wide',
                            'deleteProperty' => static::FIELD_IS_DELETE,
                            'deleteValue' => '1',
                            'addButton' => false,
                            'renderDefaultRecord' => false,
                            'columnsHeader' => false,
                            'collapsibleHeader' => true,
                            'sortOrder' => $sortOrder,
                            'dataProvider' => static::CUSTOM_OPTIONS_LISTING,
                            'imports' => ['insertData' => '${ $.provider }:${ $.dataProvider }'],
                        ],
                    ],
                ],
                'children' => [
                    'record' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'headerLabel' => __('New Option'),
                                    'componentType' => Container::NAME,
                                    'component' => 'Magento_Ui/js/dynamic-rows/record',
                                    'positionProvider' => static::CONTAINER_OPTION . '.' . static::FIELD_SORT_ORDER_NAME,
                                    'isTemplate' => true,
                                    'is_collection' => true,
                                ],
                            ],
                        ],
                        'children' => [
                            static::CONTAINER_OPTION => [
                                'arguments' => [
                                    'data' => [
                                        'config' => [
                                            'componentType' => Fieldset::NAME,
                                            'collapsible' => true,
                                            'label' => null,
                                            'sortOrder' => 10,
                                            'opened' => true,
                                        ],
                                    ],
                                ],
                                'children' => [
                                    static::FIELD_SORT_ORDER_NAME => $this->getPositionFieldConfig(50),
                                    static::CONTAINER_COMMON_NAME => $this->getCommonContainerConfig(10),
                                    static::CONTAINER_TYPE_STATIC_NAME => $this->getStaticTypeContainerConfig(20),
                                    static::GRID_TYPE_SELECT_NAME => $this->getSelectTypeGridConfig(30),
                                    static::GRID_TYPE_COLOURS_NAME => $this->getColourSelectTypeGridConfig(30),
                                ]
                            ],
                        ]
                    ]
                ]
            ];
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
                            'sortOrder' => $sortOrder
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
                            static::FIELD_CONDITIONAL_ON_NAME => $this->getConditionalFieldConfig(
                                35,
                                $this->locator->getProduct()->getStoreId() == 0 ? false : true
                            ),
                            static::FIELD_SORT_ORDER_NAME => $this->getPositionFieldConfig(50),
                            static::FIELD_IS_DELETE => $this->getIsDeleteFieldConfig(60)
                        ]
                    ]
                ]
            ];
        }

        /**
         * Get config for grid for "select" types
         *
         * @param int $sortOrder
         * @return array
         * @since 101.0.0
         */
        protected function getColourSelectTypeGridConfig($sortOrder)
        {
            return [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'addButtonLabel' => __('Add Colour'),
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
                            static::FIELD_TITLE_NAME => $this->getTitleFieldConfig(10),
                            static::FIELD_PRICE_NAME => $this->getPriceFieldConfig(20),
                            static::FIELD_PRICE_TYPE_NAME => $this->getPriceTypeFieldConfig(30, ['fit' => true]),
                            static::FIELD_COLOUR_CODE_OPTION_TITLE => $this->getColourCodeOptionFieldConfig(35),
                            static::FIELD_SORT_ORDER_NAME => $this->getPositionFieldConfig(50),
                            static::FIELD_IS_DELETE => $this->getIsDeleteFieldConfig(60)
                        ]
                    ]
                ]
            ];
        }

        /**
         * Get config for "Conditional On" field
         *
         * @param int $sortOrder
         * @param boolean $disabled
         * @return array
         * @since 101.0.0
         */
        protected function getConditionalFieldConfig($sortOrder, $disabled)
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
                            'sortOrder' => $sortOrder,
                            'disabled' => $disabled,
                            'value' => ''
                        ],
                    ],
                ],
            ];
        }

        /**
         * Get config for "Colour Code" field
         *
         * @param int $sortOrder
         * @return array
         * @since 101.0.0
         */
        protected function getColourCodeOptionFieldConfig($sortOrder)
        {
            $data =  [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Colour Code'),
                            'componentType' => Field::NAME,
                            'formElement' => Input::NAME,
                            'dataScope' => static::FIELD_COLOUR_CODE_OPTION_TITLE,
                            'dataType' => 'text',
                            'sortOrder' => $sortOrder,
                        ],
                    ],
                ],
            ];

            return $data;
        }


        /**
         * Get config for "Required on Product Page" field
         *
         * @param int $sortOrder
         * @return array
         * @since 101.0.0
         */
        protected function getIsRequireFieldConfig($sortOrder)
        {
            return [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Required on Product Page?'),
                            'componentType' => Field::NAME,
                            'formElement' => Checkbox::NAME,
                            'dataScope' => static::FIELD_IS_REQUIRE_NAME,
                            'dataType' => Text::NAME,
                            'sortOrder' => $sortOrder,
                            'value' => '1',
                            'valueMap' => [
                                'true' => '1',
                                'false' => '0'
                            ],
                        ],
                    ],
                ],
            ];
        }

        /**
         * Get config for "Show on Product Page" field
         *
         * @param int $sortOrder
         * @return array
         * @since 101.0.0
         */
        protected function getIsShowProductPageFieldConfig($sortOrder)
        {
            return [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Show on Product Page?'),
                            'componentType' => Field::NAME,
                            'formElement' => Checkbox::NAME,
                            'dataScope' => static::FIELD_IS_SHOW_PRODUCT_PAGE_NAME,
                            'dataType' => Text::NAME,
                            'sortOrder' => $sortOrder,
                            'value' => '1',
                            'valueMap' => [
                                'true' => '1',
                                'false' => '0'
                            ],
                        ],
                    ],
                ],
            ];
        }

        /**
         * Get config for "Show on Design a Door" field
         *
         * @param int $sortOrder
         * @return array
         * @since 101.0.0
         */
        protected function getIsShowDesignADoorFieldConfig($sortOrder)
        {
            return [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Show on \'Design a Door\' Page?'),
                            'componentType' => Field::NAME,
                            'formElement' => Checkbox::NAME,
                            'dataScope' => static::FIELD_IS_SHOW_DESIGN_A_DOOR_NAME,
                            'dataType' => Text::NAME,
                            'sortOrder' => $sortOrder,
                            'value' => '1',
                            'valueMap' => [
                                'true' => '1',
                                'false' => '0'
                            ],
                        ],
                    ],
                ],
            ];
        }

        /**
         * Get config for "Required on Design a Door" field
         *
         * @param int $sortOrder
         * @return array
         * @since 101.0.0
         */
        protected function getIsRequireDesignADoorFieldConfig($sortOrder)
        {
            return [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Required on \'Design a Door\' Page?'),
                            'componentType' => Field::NAME,
                            'formElement' => Checkbox::NAME,
                            'dataScope' => static::FIELD_IS_REQUIRED_DESIGN_A_DOOR_NAME,
                            'dataType' => Text::NAME,
                            'sortOrder' => $sortOrder,
                            'value' => '1',
                            'valueMap' => [
                                'true' => '1',
                                'false' => '0'
                            ],
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
