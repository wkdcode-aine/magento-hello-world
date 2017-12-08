<?php
    namespace MiltonBayer\General\Controller\Designadoor;

    use Magento\Framework\App\Action\Action;
    use Magento\Framework\App\Action\Context;
    use Magento\Framework\View\Result\PageFactory;

    class Doorlist extends Action
    {
        /**
         * @var JsonHelper
         */
        protected $jsonHelper;

        /**
         * @var JsonFactory
         */
        protected $jsonFactory;

        /**
         * @var \Magento\Framework\View\Result\PageFactory
         */
        protected $_resultPageFactory;

        /**
         * Index constructor.
         *
         * @param Context $context
         * @param JsonHelper $jsonHelper
         * @param JsonFactory $jsonFactory
         */
        public function __construct(
            Context $context,
            PageFactory $resultPageFactory,
            \Magento\Framework\Json\Helper\Data $jsonHelper,
            \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
        ) {
            $this->_resultPageFactory = $resultPageFactory;
            $this->jsonHelper = $jsonHelper;
            $this->jsonFactory = $jsonFactory;
            parent::__construct($context);
        }

        public function execute()
        {
            $httpBadRequestCode = 400;
            //Check isXmlHttpRequest
            // if (!$this->getRequest()->isXmlHttpRequest()) {
            //     return $this->jsonFactory->create()->setHttpResponseCode($httpBadRequestCode);
            // }

            $this->_view->loadLayout();
            $html = $this->_view->getLayout()->createBlock(
                'MiltonBayer\General\Block\Designadoor\Doorlist'
            )->toHtml();

            echo $html;
            return;


            /** @var \Magento\Framework\Controller\Result\Json $resultJson */
            $resultJson = $this->jsonFactory->create();
            return $resultJson->setData(['html' => $html]);
            // $resultPage = $this->_resultPageFactory->create();
            // // $resultPage->addHandle('ajax_designadoor_doorlist'); //loads the layout of module_custom_customlayout.xml file with its name
            // return $resultPage;
        }
    }
