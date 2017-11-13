<?php
    namespace Wkdcode\GarageModule\Ui\DataProvider\Product\Form\Modifier;

    class Related extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\Related
    {
        /**
         * {@inheritdoc}
         * @since 101.0.0
         */
        public function modifyMeta(array $meta)
        {
            return $meta;
        }

        /**
         * {@inheritdoc}
         * @since 101.0.0
         */
        public function modifyData(array $data)
        {
            return $data;
        }
    }
