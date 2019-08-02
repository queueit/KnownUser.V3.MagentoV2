<?php

namespace Queueit\KnownUser\Controller\Adminhtml\Admin;

class UploadConfig extends \Magento\Framework\App\Action\Action
{

	public function execute()
	{
		$configProvider = new \Queueit\KnownUser\IntegrationInfoProvider();
			if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			print_r('{');
				$errors = "";
				$extensions = ['json'];
				$uploads = new \Zend_File_Transfer_Adapter_Http();
				$files  = $uploads->getFileInfo();

				$file_name= '';

				$file_tmp ='';
				foreach ($files as $file => $fileInfo) {
					if ($uploads->isUploaded($file)) {
						if ($uploads->isValid($file)) {
							if ($uploads->receive($file)) {
								$info = $uploads->getFileInfo($file);
								$file_name = $info[$file]['name'];
								$file_type = $info[$file]['type'];
								$file_tmp  = $info[$file]['tmp_name'];

								$file_ext1 = explode('.', $file_name);
								$file_ext2 = end($file_ext1);
								$file_ext = strtolower($file_ext2);

								if (!in_array($file_ext, $extensions)) {
									$errors = 'extension not allowed: ' . $file_name . ' ' . $file_type;
								}
							}
						}
					}
					break;
				}

				$strConfig = "";
				if ($file_name != "") {

					if ($errors == "") {
						$strConfig = file_get_contents($file_tmp);
						$objectConfig = json_decode($strConfig);
						$configProvider->updateIntegrationInfo($objectConfig->integrationInfo, $objectConfig->hash);
						print_r("\"stat\" : \"Successful\",");
						$configText =  $configProvider->getIntegrationInfo(false);
						print_r("\"configText\" : " . $configText);
					}
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
}