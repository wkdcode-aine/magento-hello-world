<?php
    namespace MiltonBayer\General\Controller\Designadoor;

    use Magento\Framework\App\Action\Action ;
    use Magento\Framework\App\Action\Context;

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
         * Index constructor.
         *
         * @param Context $context
         * @param JsonHelper $jsonHelper
         * @param JsonFactory $jsonFactory
         */
        public function __construct(
            Context $context,
            \Magento\Framework\Json\Helper\Data $jsonHelper,
            \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
        ) {
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
            /** @var \Magento\Framework\Controller\Result\Json $resultJson */
            $resultJson = $this->jsonFactory->create();
            return $resultJson->setData(['html' => $html]);
        }
    }
