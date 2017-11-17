<?php

namespace MiltonBayer\General\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $setup->getConnection()->addColumn(
            $setup->getTable('catalog_product_option_type_value'),
            'conditional_on',
            Table::TYPE_INTEGER,
            0,
            [],
            'Conditional On'
        );

        $setup->endSetup();
    }
}
