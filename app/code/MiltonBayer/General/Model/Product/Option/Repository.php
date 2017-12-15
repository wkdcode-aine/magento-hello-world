<?php
    namespace MiltonBayer\General\Model\Product\Option;

    use Magento\Catalog\Api\Data\ProductInterface;
    use Magento\Framework\Exception\CouldNotSaveException;
    use Magento\Framework\Exception\NoSuchEntityException;

    class Repository extends \Magento\Catalog\Model\Product\Option\Repository
    {
        /**
         * {@inheritdoc}
         */
        public function save(\Magento\Catalog\Api\Data\ProductCustomOptionInterface $option)
        {
            $productSku = $option->getProductSku();
            if (!$productSku) {
                throw new CouldNotSaveException(__('ProductSku should be specified'));
            }
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $this->productRepository->get($productSku);
            $metadata = $this->metadataPool->getMetadata(ProductInterface::class);
            $option->setData('product_id', $product->getData($metadata->getLinkField()));
            $option->setData('store_id', $product->getStoreId());

            if ($option->getOptionId()) {
                $options = $product->getOptions();
                if (!$options) {
                    $options = $this->getProductOptions($product);
                }

                $persistedOption = array_filter($options, function ($iOption) use ($option) {
                    return $option->getOptionId() == $iOption->getOptionId();
                });
                $persistedOption = reset($persistedOption);

                if (!$persistedOption) {
                    throw new NoSuchEntityException();
                }
                $originalValues = $persistedOption->getValues();
                $newValues = $option->getData('values');
                if( empty($newValues) && !empty($option->getData('colours')) ) {
                    $newValues = $option->getData('colours');
                }
                if ($newValues) {
                    if (isset($originalValues)) {
                        $newValues = $this->markRemovedValues($newValues, $originalValues);
                    }
                    $option->setData('values', $newValues);
                }
            }

            $option->save();
            return $option;
        }
    }
