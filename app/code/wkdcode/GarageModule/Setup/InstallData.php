<?php

namespace Wkdcode\GarageModule\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $setup->getConnection()->insert(
            $setup->getTable('garage_door'),
            [
                'name' => 'Roller Door 001',
                'type' => 'roller',
                'price' => '2000.00'
            ]
        );

        $setup->getConnection()->insert(
            $setup->getTable('garage_door'),
            [
                'name' => 'Roller Door 002',
                'type' => 'roller',
                'price' => '2100.00'
            ]
        );

        $setup->getConnection()->insert(
            $setup->getTable('garage_door'),
            [
                'name' => 'Solid Door 001',
                'type' => 'solid',
                'price' => '1000.00'
            ]
        );

        $setup->endSetup();
    }
}
