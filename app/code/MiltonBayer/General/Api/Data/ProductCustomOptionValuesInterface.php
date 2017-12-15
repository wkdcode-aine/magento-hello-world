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

        /**
         * Get colour code
         *
         * @return float
         */
        public function getColourCode();

        /**
         * Set colour code
         *
         * @param string $colour_code
         * @return $this
         */
        public function setColourCode($colour_code);
    }
