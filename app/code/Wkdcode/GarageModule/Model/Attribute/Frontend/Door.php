<?php
    namespace Wkdcode\GarageModule\Model\Attribute\Frontend;

    use \Magento\Eav\Model\Entity\Attribute\Frontend\AbstractFrontend;

    class Material extends AbstractFrontend
    {
        public function getValue(\Magento\Framework\DataObject $object)
        {
            $value = $object->getData($this->getAttribute()->getAttributeCode());
            return "<b>$value</b>";
        }
    }
