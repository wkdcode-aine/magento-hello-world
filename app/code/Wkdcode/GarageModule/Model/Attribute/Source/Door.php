<?php
    namespace Wkdcode\GarageModule\Model\Attribute\Source;

    use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

    use Wkdcode\GarageModule\Model\ResourceModel\Door\Collection as DoorCollection;

    class Door extends AbstractSource
    {
        private $doorCollection;

        public function __construct(DoorCollection $garageDoorCollection){
            $this->doorCollection = $garageDoorCollection;
        }
        /**
         * Get all options
         * @return array
         */
        public function getAllOptions()
        {
            return $this->doorCollection->toOptionArray();
        }
    }
