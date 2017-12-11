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

            $setup->endSetup();
        }

    }
