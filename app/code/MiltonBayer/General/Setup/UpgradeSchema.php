<?php
    namespace MiltonBayer\General\Setup;

    use Magento\Framework\DB\Ddl\Table;
    use Magento\Framework\Setup\UpgradeSchemaInterface;
    use Magento\Framework\Setup\ModuleContextInterface;
    use Magento\Framework\Setup\SchemaSetupInterface;

    class UpgradeSchema implements UpgradeSchemaInterface
    {
        /**
         * {@inheritdoc}
         */
        public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
        {
            $setup->startSetup();
            $connection = $setup->getConnection();
            if (version_compare($context->getVersion(), '1.0.2', '<')) {


                $connection->addColumn(
                    $setup->getTable('catalog_product_option'),
                    'is_show_product_page',
                    ['type' => Table::TYPE_BOOLEAN, 'default' => 0, 'comment' => 'Show on product page']
                );

                $connection->addColumn(
                    $setup->getTable('catalog_product_option'),
                    'is_show_design_a_door',
                    ['type' => Table::TYPE_BOOLEAN, 'default' => 0, 'comment' => 'Show on design a door']
                );

                $connection->addColumn(
                    $setup->getTable('catalog_product_option'),
                    'is_required_design_a_door',
                    ['type' => Table::TYPE_BOOLEAN, 'default' => 0, 'comment' => 'Required on design a door']
                );
            }

            if( version_compare($context->getVersion(), '1.0.4', '<') ) {
                $connection->addColumn(
                    $setup->getTable('eav_attribute'),
                    'search_excludes_selected',
                    ['type' => Table::TYPE_BOOLEAN, 'default' => 0, 'comment' => 'Request param is not used to limit results to that value, instead to exclude the value from results']
                );

                $connection->addColumn(
                    $setup->getTable('eav_attribute_option'),
                    'searchable_option',
                    ['type' => Table::TYPE_BOOLEAN, 'default' => 1, 'comment' => 'Option can be used in filters and dropdowns to search/exclude products matching this attribute id']
                );
            }

            if( version_compare($context->getVersion(), '1.0.5', '<') ) {
                $connection->addColumn(
                    $setup->getTable('catalog_product_option_type_value'),
                    'colour_code',
                    Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => true,
                        'default'  => NULL
                    ],
                    'Colour Code'
                );
            }

            if( version_compare($context->getVersion(), '1.0.6', '<') ) {
                $table = $connection
                    ->newTable($setup->getTable('crosssell_category_product'))
                    ->addColumn(
                        'crosssell_category_product_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'Greeting ID'
                    )
                    ->addColumn(
                        'category_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['unsigned' => true, 'nullable' => false],
                        'Greeting ID'
                    )
                    ->addColumn(
                        'product_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['unsigned' => true, 'nullable' => false],
                        'Message'
                    )
                    ->addColumn(
                        'sort_order',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => false, 'default' => ''],
                        'Message'
                    )
                    ->addForeignKey(
                          $setup->getFkName('crosssell_category_product_id', 'category_id', 'catalog_category_entity', 'entity_id'),
                          'category_id',
                          $setup->getTable('catalog_category_entity'),
                          'entity_id',
                          \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                          $setup->getFkName('crosssell_category_product_id', 'product_id', 'catalog_product_entity', 'entity_id'),
                          'product_id',
                          $setup->getTable('catalog_product_entity'),
                          'entity_id',
                          \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )->setComment("Featured  table");
                  $connection->createTable($table);
            }

            $setup->endSetup();
        }

    }
