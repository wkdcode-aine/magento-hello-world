<?php
    namespace MiltonBayer\General\Controller\Designadoor;

    use Magento\Catalog\Api\CategoryRepositoryInterface;
    use Magento\Catalog\Model\Layer\Resolver;
    use Magento\Framework\App\Action\Action;
    use Magento\Framework\App\Action\Context;
    use Magento\Framework\View\Result\PageFactory;

    class Doorlist extends Action
    {
        /**
         * Core registry
         *
         * @var \Magento\Framework\Registry
         */
        protected $_coreRegistry = null;

        /**
         * Catalog session
         *
         * @var \Magento\Catalog\Model\Session
         */
        protected $_catalogSession;

        /**
         * @var JsonHelper
         */
        protected $jsonHelper;

        /**
         * @var JsonFactory
         */
        protected $jsonFactory;

        /**
         * Catalog Layer Resolver
         *
         * @var Resolver
         */
        private $layerResolver;

        /**
         * @var \Magento\Framework\View\Result\PageFactory
         */
        protected $_resultPageFactory;

        /**
         * @var \Magento\Store\Model\StoreManagerInterface
         */
        protected $_storeManager;

        /**
         * @var CategoryRepositoryInterface
         */
        protected $categoryRepository;

        /**
         * Index constructor.
         *
         * @param Context $context
         * @param PageFactory $resultPageFactory
         * @param \Magento\Catalog\Model\Session $catalogSession
         * @param JsonHelper $jsonHelper
         * @param JsonFactory $jsonFactory
         * @param \Magento\Framework\Registry $coreRegistry
         * @param \Magento\Store\Model\StoreManagerInterface $storeManager
         * @param Resolver $layerResolver
         * @param CategoryRepositoryInterface $categoryRepository
         */
        public function __construct(
            Context $context,
            PageFactory $resultPageFactory,
            \Magento\Catalog\Model\Session $catalogSession,
            \Magento\Framework\Json\Helper\Data $jsonHelper,
            \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
            \Magento\Framework\Registry $coreRegistry,
            \Magento\Store\Model\StoreManagerInterface $storeManager,
            Resolver $layerResolver,
            CategoryRepositoryInterface $categoryRepository
        ) {
            parent::__construct($context);
            $this->_storeManager = $storeManager;
            $this->_catalogSession = $catalogSession;
            $this->_coreRegistry = $coreRegistry;
            $this->_resultPageFactory = $resultPageFactory;
            $this->jsonHelper = $jsonHelper;
            $this->jsonFactory = $jsonFactory;
            $this->layerResolver = $layerResolver;
            $this->categoryRepository = $categoryRepository;

        }

        /**
         * Initialize requested category object
         *
         * @return \Magento\Catalog\Model\Category
         */
        protected function _initCategory()
        {
            $categoryId = (int)$this->getRequest()->getParam('cat', false);
            if (!$categoryId)  return false;

            try {
                $category = $this->categoryRepository->get($categoryId, $this->_storeManager->getStore()->getId());
            } catch (NoSuchEntityException $e) {
                return false;
            }
            if (!$this->_objectManager->get(\Magento\Catalog\Helper\Category::class)->canShow($category))  return false;

            $this->_catalogSession->setLastVisitedCategoryId($category->getId());
            $this->_coreRegistry->register('current_category', $category);

            try {
                $this->_eventManager->dispatch(
                    'catalog_controller_category_init_after',
                    ['category' => $category, 'controller_action' => $this]
                );
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
                return false;
            }

            return $category;
        }

        public function execute()
        {
            $httpBadRequestCode = 400;
            //Check isXmlHttpRequest
            // if (!$this->getRequest()->isXmlHttpRequest()) {
            //     return $this->jsonFactory->create()->setHttpResponseCode($httpBadRequestCode);
            // }

            $category = $this->_initCategory();
            if ($category) {
                $this->layerResolver->create(Resolver::CATALOG_LAYER_CATEGORY);
                $this->_view->loadLayout();
                $this->_view->renderLayout();
                return;
            }
        }
    }
