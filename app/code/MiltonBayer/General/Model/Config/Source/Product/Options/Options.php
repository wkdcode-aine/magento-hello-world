<?php

namespace MiltonBayer\General\Model\Config\Source\Product\Options;

/**
 * Product option types mode source
 */
class Options implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Option collections
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\Option\Collection
     */
    protected $_optionCollection;

    /**
     * @var \Magento\Catalog\Model\Locator\LocatorInterface
     */
    protected $_locator;


    /**
     * @param \Magento\Catalog\Model\Locator\LocatorInterface $locator
     * @param \Magento\Catalog\Model\ResourceModel\Product\Option\Collection $optionCollection
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Catalog\Model\Locator\LocatorInterface $locator,
        \Magento\Catalog\Model\ResourceModel\Product\Option\Collection $optionCollection
    ) {
        $this->_locator = $locator;
        $this->_optionCollection = $optionCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $product = $this->_locator->getProduct();
        $product_id = $product->getData('entity_id');
        $store_id = $product->getStoreId();

        $groups = [['value' => '', 'label' => __('-- Please select --')]];

        $items = $this->_optionCollection
            ->addProductToFilter($product_id)
            ->addTitleToResult($store_id)
            ->addValuesToResult($store_id)
            ->getItems();

        foreach( $items as $item ) {
            $types = [];

            if( in_array($item->getData('type'), ['drop_down', 'radio', 'checkbox']) ) {

                foreach($item->getValues() as $value) $types[] = ['label' => $value->getData('title'), 'value' => $value->getData('option_type_id')];
            }

            if( count($types) > 0 )  $groups[] = ['label' => __($item->getData('title')), 'value' => $types, 'optgroup-name' => $item->getData('title')];
        }

        return $groups;
    }
}
