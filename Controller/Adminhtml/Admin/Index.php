<?php
namespace Queueit\KnownUser\Controller\Adminhtml\Admin;

use \DateTime;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Queueit\KnownUser\IntegrationInfoProvider
     */
    protected $configProvider;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Queueit\KnownUser\IntegrationInfoProvider $configProvider
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Queueit\KnownUser\IntegrationInfoProvider $configProvider
    ) {

        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->configProvider = $configProvider;
    }

    /**
     * Load the page defined in view/adminhtml/layout/exampleadminnewpage_helloworld_index.xml
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $configText =  $this->configProvider->getIntegrationInfo(false);
        $customerIntegration = json_decode($configText, true);
        $resultPage = $this->resultPageFactory->create();
        $layout = $resultPage->getLayout();
        $block = $layout->getBlock('main_panel');
        $block->setAccountId($customerIntegration["AccountId"]);
        $block->setVersion($customerIntegration["Version"]);
        $block->setPublishDate($customerIntegration["PublishDate"]);
        $block->setUploadUrl($this->getUrl('knownuser/admin/UploadConfig'));
        $block->setIntegrationConfig($configText);
        return $resultPage;
    }
}