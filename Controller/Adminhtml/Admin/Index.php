<?php
namespace Queueit\KnownUser\Controller\Adminhtml\Admin;
require_once( __DIR__ .'../../../../IntegrationInfoProvider.php');
use \DateTime;

      class Index extends \Magento\Backend\App\Action
      {
        /**
        * @var \Magento\Framework\View\Result\PageFactory
        */
        protected $resultPageFactory;


        /**
         * Constructor
         *
         * @param \Magento\Backend\App\Action\Context $context
         * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
         */
        public function __construct(
            \Magento\Backend\App\Action\Context $context,
            \Magento\Framework\View\Result\PageFactory $resultPageFactory
        ) {
         
             parent::__construct($context);
             $this->resultPageFactory = $resultPageFactory;

        }

        /**
         * Load the page defined in view/adminhtml/layout/exampleadminnewpage_helloworld_index.xml
         *
         * @return \Magento\Framework\View\Result\Page
         */
        public function execute()
        {
            $configProvider = new \Queueit\KnownUser\IntegrationInfoProvider();
            $configText =  $configProvider->getIntegrationInfo(true);
            $customerIntegration = json_decode($configText, true);
            
            $resultPage = $this->resultPageFactory->create();
            $layout = $resultPage->getLayout();
            $block = $layout->getBlock('main_panel');
            $block->setAccountId($customerIntegration["AccountId"]);
            $block->setVersion($customerIntegration["Version"]);
            $block->setPublishDate($customerIntegration["PublishDate"]);
            $block->setIntegrationConfig( $configText);
            return $resultPage;

        }
      }  
  