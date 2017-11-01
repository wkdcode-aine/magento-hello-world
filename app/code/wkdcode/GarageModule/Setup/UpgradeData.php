<?php

namespace Wkdcode\GarageModule\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $setup->getConnection()->insert(
                $setup->getTable('garage_motor'),
                [
                    'name' => 'Motor 001',
                    'hand_crank' => '1',
                    'price' => '2000.00'
                ]
            );

            $setup->getConnection()->insert(
                $setup->getTable('garage_motor'),
                [
                    'name' => 'Motor 002',
                    'hand_crank' => '1',
                    'price' => '2100.00'
                ]
            );

            $setup->getConnection()->insert(
                $setup->getTable('garage_motor'),
                [
                    'name' => 'Motor 003',
                    'hand_crank' => '0',
                    'price' => '1000.00'
                ]
            );
        }

        $setup->endSetup();
    }
}
