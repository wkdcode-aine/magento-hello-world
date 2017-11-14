<?php

namespace Wkdcode\GarageModule\Model\Config\Source\Product\Options;

/**
 * Product option types mode source
 */
class Options implements \Magento\Framework\Option\ArrayInterface
{
    /**
     *
     */
    public function __construct(

    ) {

    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $groups = [['value' => '', 'label' => __('-- Please select --')]];

        // foreach($this->_productOptionConfig->getAll() as $option) {
        //     $types = [];
        //     foreach ($option['types'] as $type) {
        //         $types[] = ['label' => __($type['label']), 'value' => $type['name']];
        //     }
        //     if (count($types)) {
        //         $groups[] = ['label' => __($option['label']), 'value' => $types, 'optgroup-name' => $option['label']];
        //     }
        // }
        //


        $groups[] = ['label' => 'Canopy', 'value' => 7];
        $groups[] = ['value' => 8, 'label' => 'Retractable'];

        return $groups;
    }
}
