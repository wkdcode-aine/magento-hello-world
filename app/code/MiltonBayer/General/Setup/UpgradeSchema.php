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
            if (version_compare($context->getVersion(), '1.0.2', '<')) {

                $connection = $setup->getConnection();

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

            $setup->endSetup();
        }
    }
