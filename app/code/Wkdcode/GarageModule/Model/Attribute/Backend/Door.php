<?php
    namespace Wkdcode\GarageModule\Model\Attribute\Backend;

    use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;

    class Door extends AbstractBackend
    {
       /**
        * Validate
        * @param \Magento\Catalog\Model\Product $object
        * @throws \Magento\Framework\Exception\LocalizedException
        * @return bool
        */
       public function validate($object)
       {
           $value = $object->getData($this->getAttribute()->getAttributeCode());
           if ( ($object->getAttributeSetId() == 10) && ($value == 'wool')) {
               throw new \Magento\Framework\Exception\LocalizedException(
                   __('Bottom can not be wool.')
               );
           }
           return true;
       }
    }
