<?php

namespace Wkdcode\GarageModule\Model\Config\Source\Product\Options;

/**
 * Product option types mode source
 */
class Options implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * [private description]
     * @var [type]
     */
    private $optionCollection;

    /**
     *
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\Option\Collection $optionCollection
    ) {
        $this->optionCollection = $optionCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $groups = [['value' => '', 'label' => __('-- Please select --')]];

        foreach($this->optionCollection->addTitleToResult(0)->addValuesToResult(0)->getItems() as $item) {
            $types = [];

            foreach($item->getValues() as $value) $types[] = ['label' => $value->getData('title'), 'value' => $value->getData('option_type_id')];

            if( count($types) > 0 )  $groups[] = ['label' => __($item->getData('title')), 'value' => $types, 'optgroup-name' => $item->getData('title')];
        }

        return $groups;
    }
}
