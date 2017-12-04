<?php
namespace MiltonBayer\General\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Model\Category;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

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

        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), '1.0.3', '<')) {

            $eavSetup->addAttribute(Category::ENTITY,
               'show_design_a_door', [
                    'type'     => 'int',
                    'label'    => 'Show Design a Door',
                    'input'    => 'text',
                    'visible'  => true,
                    'default'  => 0,
                    'sort_order' => 3,
                    'required' => false,
                    'global'   => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'group'    => 'Display Settings',
                    'source_model' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean'
                ]);
        }

        $setup->endSetup();
    }
}
