<?php
namespace Queueit\KnownUser;
        /**
     * Update IntegraionInfo.
     *@api
     */                                     
class IntegrationInfoProvider implements IntegrationInfoProviderInterface
{
const CACHE_KEY = "_queueit_integrationinfo";
const CONFIG_SECRETKEY = 'queueit_knownuser/configuration/secretkey';

    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $cache;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\CacheInterface $cache,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->cache = $cache;
        $this->resourceConnection = $resourceConnection;
        $this->scopeConfig = $scopeConfig;
    }

   public function getIntegrationInfo($useCache)
   {
    $cacheInterface = $this->cache;
    $integrationConfig = $cacheInterface->load(self::CACHE_KEY);
     if($integrationConfig && $useCache)
     {
        $result = $integrationConfig;
     }
     else
     {
        $resource = $this->resourceConnection;
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('queueit_integrationinfo'); //gives table name with prefix
        
        //Select Data from table
        $sql = "Select * FROM " . $tableName . ' order by id desc limit 1' ;
		$result="";
		$rows  = $connection->fetchAll($sql);
		if($rows)
		{
			$result = $rows[0]['info'];
			$cacheInterface->save($result, self::CACHE_KEY, array(),5*60);
		}
     }
      return hex2bin($result);     
   }

    /**
     * Update IntegraionInfo.
     * @api
     * @param string $integrationInfo
     * @param string $hash
     * @return 
     */
   public function updateIntegrationInfo($integrationInfo,$hash)
   {
        if($this->isValidRequest($integrationInfo,$hash))
        {
            $resource = $this->resourceConnection;
            $connection = $resource->getConnection();
            $tableName = $resource->getTableName('queueit_integrationinfo'); //gives table name with prefix
            //Insert Data into table
            $sql = "Insert Into " . $tableName . " (info) Values ('" . $integrationInfo ."')";
            $connection->query($sql);
        }
        else
		{
			 $response->setStatusCode(\Magento\Framework\App\Response\Http::STATUS_CODE_401);
			 $response->sendResponse();
		}
   }

   private function isValidRequest($integrationInfo,$hash)
   {
      $scopeConfig = $this->scopeConfig;
      $secretKey = $scopeConfig->getValue(
          self::CONFIG_SECRETKEY,
          \Magento\Store\Model\ScopeInterface::SCOPE_STORE
      );
      $calculatedHash = hash_hmac('sha256', $integrationInfo, $secretKey);
      return $calculatedHash == $hash;
   }
}