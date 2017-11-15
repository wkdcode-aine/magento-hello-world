<?php
    namespace Wkdcode\GarageModule\Controller\Adminhtml\Door;

    use Magento\Framework\Controller\ResultFactory;
    use Magento\Backend\App\Action\Context;
    use Magento\Ui\Component\MassAction\Filter;
    use Wkdcode\GarageModule\Model\ResourceModel\Door\Collection as CollectionFactory;
    use Wkdcode\GarageModule\Api\DoorRepositoryInterface;

    class MassDelete extends \Magento\Backend\App\Action
    {
        /**
         * Massactions filter
         *
         * @var Filter
         */
        protected $filter;

        /**
         * @var CollectionFactory
         */
        protected $collectionFactory;

        /**
         * @var DoorRepositoryInterface
         */
        private $doorRepository;

        /**
         * @param Context $context
         * @param Builder $doorBuilder
         * @param Filter $filter
         * @param CollectionFactory $collectionFactory
         * @param DoorRepositoryInterface $doorRepository
         */
        public function __construct(
            Context $context,
            // Builder $doorBuilder,
            Filter $filter,
            CollectionFactory $collectionFactory,
            DoorRepositoryInterface $doorRepository = null
        ) {
            $this->filter = $filter;
            $this->collectionFactory = $collectionFactory;
            $this->doorRepository = $doorRepository
                ?: \Magento\Framework\App\ObjectManager::getInstance()->create(DoorRepositoryInterface::class);
            parent::__construct($context, null);
        }

        /**
         * @return \Magento\Backend\Model\View\Result\Redirect
         */
        public function execute()
        {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $doorDeleted = 0;
            /** @var \Magento\Catalog\Model\Product $door */
            foreach ($collection->getItems() as $door) {
                $this->doorRepository->delete($door);
                $doorDeleted++;
            }
            $this->messageManager->addSuccess(
                __('A total of %1 record(s) have been deleted.', $doorDeleted)
            );

            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('wkdcode/*/index');
        }
    }
