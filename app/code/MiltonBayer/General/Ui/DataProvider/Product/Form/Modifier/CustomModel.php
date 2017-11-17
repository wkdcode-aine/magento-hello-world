<?php
namespace MiltonBayer\General\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;

class CustomModel extends AbstractModifier
{

    public function modifyMeta(array $meta)
    {
        // unset($meta['product-details']['children']['container_color']);
        // unset($meta['product-details']['children']['container_country_of_manufacture']);
        // unset($meta['product-details']['children']['container_news_from_date']);
        //
        // //
        // unset($meta['attributes']);
        // unset($meta['downloadable']);
        // unset($meta['review']);
        // // unset($meta['related']);
        // unset($meta['configurable_associated_product_modal']);
        // unset($meta['configurable_attribute_set_handler_modal']);
        // unset($meta['custom_options']);
        // unset($meta['import_options_modal']);
        // unset($meta['configurable']);

        return $meta;
    }

    public function modifyData(array $data)
    {
        return $data;
    }

}
