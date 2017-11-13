<?php

namespace Wkdcode\GarageModule\Setup;

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

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $table = $setup->getConnection()->newTable(
                $setup->getTable('garage_motor')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Name'
            )->addColumn(
                'hand_crank',
                Table::TYPE_BOOLEAN,
                null,
                [],
                'Type'
            )->addColumn(
                'price',
                Table::TYPE_DECIMAL,
                '20,2',
                ['nullable' => false],
                'Price'
            )->addIndex(
                $setup->getIdxName('garage_motor', ['name']),
                ['name']
            )->setComment(
                'Garage Motors'
            );
            $setup->getConnection()->createTable($table);
        }

        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('sales_order_grid'),
                'base_tax_amount',
                [
                    'type' => Table::TYPE_DECIMAL,
                    'comment' => 'Base Tax Amount'
                ]
            );
        }

        if( version_compare($context->getVersion(), '1.0.4', '<')) {
            $connection = $setup->getConnection();

            $connection->addColumn(
                $setup->getTable('garage_door'),
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                [],
                'Created At'
            );
            $connection->addColumn(
                $setup->getTable('garage_door'),
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                [],
                'Updated At'
            );
            $connection->addColumn(
                $setup->getTable('garage_motor'),
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                [],
                'Created At'
            );
            $connection->addColumn(
                $setup->getTable('garage_motor'),
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                [],
                'Updated At'
            );
        }

        if( version_compare($context->getVersion(), '1.0.5', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('catalog_product_option_type_value'),
                'conditional_on',
                Table::TYPE_INTEGER,
                null,
                [],
                'Conditional On'
            );
        }

        $setup->endSetup();
    }
}
