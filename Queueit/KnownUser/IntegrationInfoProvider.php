<?php
namespace Queueit\KnownUser;
require_once( __DIR__ .'\IntegrationInfoProviderInterface.php');
        /**
     * Update IntegraionInfo.
     *@api
     */                                     
class IntegrationInfoProvider implements IntegrationInfoProviderInterface
{
const CACHE_KEY = "_queueit_integrationinfo";
const CONFIG_SECRETKEY = 'queueit_knownuser/configuration/secretkey';


   public function getIntegrationInfo()
   {
    $om = \Magento\Framework\App\ObjectManager::getInstance();
    $cacheInterface = $om->get('Magento\Framework\App\CacheInterface');
     if($integrationConfig = $cacheInterface->load(self::CACHE_KEY))
     {
        $result = $integrationConfig;
     }
     else
     {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('queueit_integrationinfo'); //gives table name with prefix
        
        //Select Data from table
        $sql = "Select * FROM " . $tableName . ' order by id desc limit 1' ;
        $result = $connection->fetchAll($sql);
        $cacheInterface->save($result[0]['info'], self::CACHE_KEY, array(),5*60);
     }
      return hex2bin($result);     
   }

    /**
     * Update IntegraionInfo.
     *@api
     * @param string $integrationinfo
     * @param string $hash
    * @return 
     */
   public function updateIntegrationInfo($integrationInfo,$hash)
   {
    if($this->isValidRequest($integrationInfo,$hash))
      {
       
            //return;
          
 $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
          $connection = $resource->getConnection();
          $tableName = $resource->getTableName('queueit_integrationinfo'); //gives table name with prefix
          //Insert Data into table
          $sql = "Insert Into " . $tableName . " (info) Values ('" . $integrationInfo ."')";
          $connection->query($sql);
          return $sql;
      }
   }

   private function isValidRequest($integrationInfo,$hash)
   {
      $om = \Magento\Framework\App\ObjectManager::getInstance();
      $scopeConfig = $om->get('\Magento\Framework\App\Config\ScopeConfigInterface');
      $secretKey = $scopeConfig->getValue(
          self::CONFIG_SECRETKEY,
          \Magento\Store\Model\ScopeInterface::SCOPE_STORE
      );
      $calculatedHash = hash_hmac('sha256', $integrationInfo, $secretKey);
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
      $logger = $objectManager->get("Psr\Log\LoggerInterface");
           $logger->debug($integrationInfo);
      $logger->debug($calculatedHash);
      $logger->debug($hash);
      return $calculatedHash == $hash;

   }
}