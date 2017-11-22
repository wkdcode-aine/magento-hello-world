<?php
    namespace MiltonBayer\General\Ui\DataProvider\Product\Form\Modifier;

    use Magento\Catalog\Controller\Adminhtml\Product\Initialization\StockDataFilter;
    use Magento\Catalog\Model\Locator\LocatorInterface;
    use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
    use Magento\CatalogInventory\Api\Data\StockItemInterface;
    use Magento\CatalogInventory\Api\StockConfigurationInterface;
    use Magento\CatalogInventory\Api\StockRegistryInterface;
    use Magento\Framework\Serialize\Serializer\Json;
    use Magento\Framework\Stdlib\ArrayManager;
    use Magento\Framework\App\ObjectManager;
    use Magento\Framework\Serialize\JsonValidator;

    class AdvancedInventory extends \Magento\CatalogInventory\Ui\DataProvider\Product\Form\Modifier\AdvancedInventory
    {
        /**
         * @var LocatorInterface
         */
        private $locator;

        /**
         * @var StockRegistryInterface
         */
        private $stockRegistry;

        /**
         * @var ArrayManager
         */
        private $arrayManager;

        /**
         * @var StockConfigurationInterface
         */
        private $stockConfiguration;

        /**
         * @var array
         */
        private $meta = [];

        /**
         * @var Json
         */
        private $serializer;

        /**
         * @var JsonValidator
         */
        private $jsonValidator;

        /**
         * Constructor
         *
         * @param LocatorInterface $locator
         * @param StockRegistryInterface $stockRegistry
         * @param ArrayManager $arrayManager
         * @param StockConfigurationInterface $stockConfiguration
         * @param Json|null $serializer
         * @param JsonValidator|null $jsonValidator
         */
        public function __construct(
            LocatorInterface $locator,
            StockRegistryInterface $stockRegistry,
            ArrayManager $arrayManager,
            StockConfigurationInterface $stockConfiguration,
            Json $serializer = null,
            JsonValidator $jsonValidator = null
        ) {
            $this->locator = $locator;
            $this->stockRegistry = $stockRegistry;
            $this->arrayManager = $arrayManager;
            $this->stockConfiguration = $stockConfiguration;
            $this->serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
            $this->jsonValidator = $jsonValidator ?: ObjectManager::getInstance()->get(JsonValidator::class);

            return parent::__construct($locator, $stockRegistry, $arrayManager, $stockConfiguration, $serializer, $jsonValidator);
        }

        /**
         * {@inheritdoc}
         * @since 101.0.0
         */
        public function modifyMeta(array $meta)
        {
            $this->meta = $meta;

            $this->prepareMeta();

            return $this->meta;
        }

        /**
         * {@inheritdoc}
         * @since 101.0.0
         */
        public function modifyData(array $data)
        {
            return parent::modifyData($data);
        }

        /**
         * @return void
         */
        private function prepareMeta()
        {
            $fieldCode = 'quantity_and_stock_status';
            $pathField = $this->arrayManager->findPath($fieldCode, $this->meta, null, 'children');

            if ($pathField) {
                $labelField = $this->arrayManager->get(
                    $this->arrayManager->slicePath($pathField, 0, -2) . '/arguments/data/config/label',
                    $this->meta
                );
                $fieldsetPath = $this->arrayManager->slicePath($pathField, 0, -4);

                $this->meta = $this->arrayManager->merge(
                    $pathField . '/arguments/data/config',
                    $this->meta,
                    [
                        'label' => __('Stock Status'),
                        'value' => '1',
                        'dataScope' => $fieldCode . '.is_in_stock',
                        'scopeLabel' => '[GLOBAL]',
                        'imports' => [
                            'visible' => '${$.provider}:data.product.stock_data.manage_stock',
                        ],
                    ]
                );
                $this->meta = $this->arrayManager->merge(
                    $this->arrayManager->slicePath($pathField, 0, -2) . '/arguments/data/config',
                    $this->meta,
                    [
                        'label' => __('Stock Status'),
                        'scopeLabel' => '[GLOBAL]',
                    ]
                );

                $container['arguments']['data']['config'] = [
                    'formElement' => 'container',
                    'componentType' => 'container',
                    'component' => "Magento_Ui/js/form/components/group",
                    'label' => $labelField,
                    'breakLine' => false,
                    'dataScope' => $fieldCode,
                    'scopeLabel' => '[GLOBAL]',
                    'source' => 'product_details',
                    'sortOrder' => (int) $this->arrayManager->get(
                        $this->arrayManager->slicePath($pathField, 0, -2) . '/arguments/data/config/sortOrder',
                        $this->meta
                    ) - 1,
                ];
                $qty['arguments']['data']['config'] = [
                    'component' => 'Magento_CatalogInventory/js/components/qty-validator-changer',
                    'dataType' => 'number',
                    'formElement' => 'input',
                    'componentType' => 'field',
                    'visible' => '1',
                    'require' => '0',
                    'additionalClasses' => 'admin__field-small',
                    'label' => __('Quantity'),
                    'scopeLabel' => '[GLOBAL]',
                    'dataScope' => 'qty',
                    'validation' => [
                        'validate-number' => true,
                        'less-than-equals-to' => StockDataFilter::MAX_QTY_VALUE,
                    ],
                    'imports' => [
                        'handleChanges' => '${$.provider}:data.product.stock_data.is_qty_decimal',
                    ],
                    'sortOrder' => 10,
                ];
                $container['children'] = [ 'qty' => $qty ];

                $this->meta = $this->arrayManager->merge(
                    $fieldsetPath . '/children',
                    $this->meta,
                    ['quantity_and_stock_status_qty' => $container]
                );
            }
        }
    }
