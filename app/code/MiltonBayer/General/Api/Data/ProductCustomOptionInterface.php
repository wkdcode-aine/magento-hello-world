<?php
    namespace MiltonBayer\General\Api\Data;

    interface ProductCustomOptionInterface extends \Magento\Catalog\Api\Data\ProductCustomOptionInterface
    {
        /**
         * Product colour options group.
         */
        const OPTION_GROUP_COLOUR = 'colours';

        /**
         * Product colour_swatch option type.
         */
        const OPTION_TYPE_COLOUR_SWATCH = 'colour_swatch';
    }
