<?php

namespace Wkdcode\GarageModule\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Setup\EavSetupFactory;

class UpgradeData implements UpgradeDataInterface
{

    private $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }
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

        if( version_compare($context->getVersion(), '1.0.3', '<') )
        {
            $eavSetup = $this->eavSetupFactory->create();
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'garage_door_id',
                [
                    'group' => 'General',
                    'type' => 'int',
                    'label' => 'Garage Door',
                    'input' => 'select',
                    'source' => 'Wkdcode\GarageModule\Model\Attribute\Source\Door',
                    'frontend' => 'Wkdcode\GarageModule\Model\Attribute\Frontend\Door',
                    'backend' => 'Wkdcode\GarageModule\Model\Attribute\Backend\Door',
                    'required' => true,
                    'sort_order' => 5,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'is_used_in_grid' => false,
                    'is_visible_in_grid' => false,
                    'is_filterable_in_grid' => false,
                    'visible' => true,
                    'is_html_allowed_on_front' => true,
                    'visible_on_front' => true
                ]
            );
        }

        $setup->endSetup();
    }
}
