<?php

namespace Queueit\KnownUser\Controller\Adminhtml\Admin;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Queueit\KnownUser\Model\IntegrationInfoProvider;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var IntegrationInfoProvider
     */
    protected $configProvider;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param IntegrationInfoProvider $configProvider
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        IntegrationInfoProvider $configProvider
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->configProvider = $configProvider;
    }

    /**
     * Load the page defined in view/adminhtml/layout/exampleadminnewpage_helloworld_index.xml
     *
     * @return Page
     */
    public function execute()
    {
        $configText = $this->configProvider->getIntegrationInfo(false);
        $customerIntegration = json_decode($configText, true);
        $resultPage = $this->resultPageFactory->create();
        $layout = $resultPage->getLayout();
        $block = $layout->getBlock('main_panel');
        if(isset($customerIntegration["AccountId"])){
            $block->setAccountId($customerIntegration["AccountId"]);
            $block->setVersion($customerIntegration["Version"]);
            $block->setPublishDate($customerIntegration["PublishDate"]);
        }
        $block->setUploadUrl($this->getUrl('knownuser/admin/UploadConfig'));
        $block->setIntegrationConfig($configText);
        return $resultPage;
    }
}
