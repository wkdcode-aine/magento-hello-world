<?php
    namespace MiltonBayer\General\Api\Data;

    interface ProductCustomOptionValuesInterface
    {
        /**
         * Set Option type id
         *
         * @param int $conditional_on_id
         * @return int|null
         */
        public function setConditionalOn($conditional_on_id);
    }
