<?php
    namespace MiltonBayer\General\Model\Product;

    use MiltonBayer\General\Api\Data\ProductCustomOptionInterface;
    use MiltonBayer\General\Api\Data\ProductCustomOptionValuesInterface;

    class Option extends \Magento\Catalog\Model\Product\Option implements ProductCustomOptionInterface
    {
        /**
         * @var array
         */
        protected $colours = null;

        /**
         * Whether or not the option type contains sub-values
         *
         * @param string $type
         * @return bool
         * @since 101.1.0
         */
        public function hasValues($type = null)
        {
            return $this->getGroupByType($type) == self::OPTION_GROUP_SELECT;
        }

        /**
         * Whether or not the option type contains sub-colours
         *
         * @param string $type
         * @return bool
         * @since 101.1.0
         */
        public function hasColours($type = null)
        {
            return $this->getGroupByType($type) == self::OPTION_GROUP_COLOUR;
        }

        /**
         * Get group name of option by given option type
         *
         * @param string $type
         * @return string
         */
        public function getGroupByType($type = null)
        {
            if ($type === null) {
                $type = $this->getType();
            }
            $optionGroupsToTypes = [
                self::OPTION_TYPE_FIELD => self::OPTION_GROUP_TEXT,
                self::OPTION_TYPE_AREA => self::OPTION_GROUP_TEXT,
                self::OPTION_TYPE_FILE => self::OPTION_GROUP_FILE,
                self::OPTION_TYPE_DROP_DOWN => self::OPTION_GROUP_SELECT,
                self::OPTION_TYPE_RADIO => self::OPTION_GROUP_SELECT,
                self::OPTION_TYPE_CHECKBOX => self::OPTION_GROUP_SELECT,
                self::OPTION_TYPE_MULTIPLE => self::OPTION_GROUP_SELECT,
                self::OPTION_TYPE_DATE => self::OPTION_GROUP_DATE,
                self::OPTION_TYPE_DATE_TIME => self::OPTION_GROUP_DATE,
                self::OPTION_TYPE_TIME => self::OPTION_GROUP_DATE,
                self::OPTION_TYPE_COLOUR_SWATCH => self::OPTION_GROUP_COLOUR
            ];

            return isset($optionGroupsToTypes[$type]) ? $optionGroupsToTypes[$type] : '';
        }

        /**
         * {@inheritdoc}
         * @SuppressWarnings(PHPMD.CyclomaticComplexity)
         * @since 101.0.0
         */
        public function beforeSave()
        {
            parent::beforeSave();
            if ($this->getData('previous_type') != '') {
                $previousType = $this->getData('previous_type');

                /**
                 * if previous option has different group from one is came now
                 * need to remove all data of previous group
                 */
                if ($this->getGroupByType($previousType) != $this->getGroupByType($this->getData('type'))) {
                    switch ($this->getGroupByType($previousType)) {
                        case self::OPTION_GROUP_SELECT:
                        case self::OPTION_GROUP_COLOUR:
                            $this->unsetData('values');
                            if ($this->getId()) {
                                $this->getValueInstance()->deleteValue($this->getId());
                            }
                            break;
                        case self::OPTION_GROUP_FILE:
                            $this->setData('file_extension', '');
                            $this->setData('image_size_x', '0');
                            $this->setData('image_size_y', '0');
                            break;
                        case self::OPTION_GROUP_TEXT:
                            $this->setData('max_characters', '0');
                            break;
                        case self::OPTION_GROUP_DATE:
                            break;
                    }
                    if ($this->getGroupByType($this->getData('type')) == self::OPTION_GROUP_SELECT) {
                        $this->setData('sku', '');
                        $this->unsetData('price');
                        $this->unsetData('price_type');
                        if ($this->getId()) {
                            $this->deletePrices($this->getId());
                        }
                    }
                }
            }
            return $this;
        }
    }
