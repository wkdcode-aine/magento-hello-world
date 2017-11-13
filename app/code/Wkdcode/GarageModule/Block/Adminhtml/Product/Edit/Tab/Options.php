<?php
namespace Wkdcode\GarageModule\Block\Adminhtml\Product\Edit\Tab;

/**
 * @api
 * @since 100.0.2
 */
class Options extends \Magento\Catalog\Block\Adminhtml\Product\Edit\Tab\Options
{
    /**
     * @var string
     */
    protected $_template = 'catalog/product/edit/options.phtml';

    /**
     * @return Widget
     */
    protected function _prepareLayout()
    {
        die("DIE");

        return parent::_prepareLayout();
    }
}
