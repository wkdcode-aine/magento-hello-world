<?php
namespace Wkdcode\GarageModule\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;

use Wkdcode\GarageModule\Model\ResourceModel\Door\Collection as DoorCollection;

class CustomModel extends AbstractModifier
{

    private $doorCollection;

    public function __construct(DoorCollection $garageDoorCollection){
        $this->doorCollection = $garageDoorCollection;
    }

    public function modifyMeta(array $meta)
    {
        $meta['product-details']['children']['test_fieldset_name'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' =>  'container',
                        'sortOrder' => 1,
                        'required' => true,
                        'collapsible' => false
                    ]
                ]
            ],
            'children' => [
                'test_field_name' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => 'select',
                                'componentType' => 'field',
                                'options' => $this->doorCollection->toOptionArray(),
                                'visible' => 1,
                                'required' => true,
                                'label' => __('Door Type'),
                                'globalScope' => true,
                                'scopeLabel' => __('[global]')
                            ]
                        ]
                    ]
                ]
            ]
        ];

        unset($meta['product-details']['children']['container_color']);
        unset($meta['product-details']['children']['container_country_of_manufacture']);
        unset($meta['product-details']['children']['container_news_from_date']);

        unset($meta['gift-options']);
        unset($meta['attributes']);
        unset($meta['downloadable']);
        unset($meta['review']);
        unset($meta['related']);
        unset($meta['configurable_associated_product_modal']);
        unset($meta['configurable_attribute_set_handler_modal']);
        unset($meta['custom_options']);
        unset($meta['import_options_modal']);
        unset($meta['configurable']);

        return $meta;
    }

    public function modifyData(array $data)
    {
        return $data;
    }

}
