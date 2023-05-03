<?php

namespace Queueit\KnownUser\Controller\Adminhtml\Admin;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Data\Form\FormKey;
use Queueit\KnownUser\Model\IntegrationInfoProvider;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\MediaStorage\Model\File\Uploader;

class UploadConfig extends Action
{
    /**
     * @var IntegrationInfoProvider
     */
    protected $configProvider;

    /**
     * Constructor
     *
     * @param Context $context
     * @param IntegrationInfoProvider $configProvider
     */
    public function __construct(
        Context $context,
        IntegrationInfoProvider $configProvider)
    {
        parent::__construct($context);
        $this->configProvider = $configProvider;
        // CsrfAwareAction Magento2.3+ compatibility
        if (interface_exists(CsrfAwareActionInterface::class)) {
            $request = $this->getRequest();
            if ($request instanceof Http && $request->isPost() && empty($request->getParam('form_key'))) {
                $formKey = $this->_objectManager->get(FormKey::class);
                $request->setParam('form_key', $formKey->getFormKey());
            }
        }
    }

    public function execute()
    {
        $configProvider = $this->configProvider;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        /** @var Uploader $uploader */
        $uploader = $this->_objectManager->create(Uploader::class, ['fileId' => 'files']);
        $uploader->setAllowedExtensions(['json']);
        $file = $uploader->validateFile();

        print_r('{');
        $errors = "";

        if (isset($file)) {
            $file_tmp = $file['tmp_name'];

	    $strConfig = file_get_contents($file_tmp) ?? '';
            $objectConfig = json_decode($strConfig, false);
            $configProvider->updateIntegrationInfo($objectConfig->integrationInfo, $objectConfig->hash);
            print_r("\"stat\" : \"Successful\",");
            $configText = $configProvider->getIntegrationInfo(false);
            print_r("\"configText\" : " . $configText);
        } else {
            $errors = 'Config file is not found in your request!';
        }
        if ($errors) {
            print_r("\"errors\" : \"");
            print_r($errors);
            print_r("\"");
        }
        print_r('}');
    }
}
