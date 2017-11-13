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
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'garage_door_id',
                [
                    'type' => 'int',
    				'backend' => '',
    				'frontend' => '',
    				'label' => 'Garage Door',
    				'input' => 'select',
    				'class' => '',
    				'source' => 'Wkdcode\GarageModule\Model\Attribute\Source\Door',
    				'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
    				'visible' => true,
    				'required' => true,
    				'user_defined' => false,
    				'default' => '',
    				'searchable' => false,
    				'filterable' => false,
    				'comparable' => false,
    				'visible_on_front' => false,
    				'used_in_product_listing' => true,
    				'unique' => false,
    				'apply_to' => ''
                ]
            );
        }

        if( version_compare($context->getVersion(), '1.0.4', '<') )
        {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'icon_id',
                [
                    'type' => 'int',
    				'backend' => '',
    				'frontend' => '',
    				'label' => 'Icon',
    				'input' => 'checkbox',
    				'class' => '',
    				'source' => 'Wkdcode\GarageModule\Model\Attribute\Source\Icon',
    				'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
    				'visible' => true,
    				'required' => true,
    				'user_defined' => false,
    				'default' => '',
    				'searchable' => false,
    				'filterable' => false,
    				'comparable' => false,
    				'visible_on_front' => false,
    				'used_in_product_listing' => true,
    				'unique' => false,
    				'apply_to' => ''
                ]
            );
        }

        $setup->endSetup();
    }
}
