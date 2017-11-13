<?php
    namespace Wkdcode\GarageModule\Ui\DataProvider\Product\Modifier;

    class GiftMessage extends \Magento\GiftMessage\Ui\DataProvider\Product\Modifier\GiftMessage
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
