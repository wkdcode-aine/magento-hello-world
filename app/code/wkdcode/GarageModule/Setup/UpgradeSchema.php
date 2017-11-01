<?php

namespace wkdcode\GarageModule\Setup;

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

        $setup->endSetup();
    }
}
