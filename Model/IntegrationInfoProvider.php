<?php

namespace Queueit\KnownUser\Model;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\ScopeInterface;

/**
 * Update IntegraionInfo.
 * @api
 */
class IntegrationInfoProvider
{
    const CACHE_KEY = "_queueit_integrationinfo";
    const CONFIG_SECRETKEY = 'queueit_knownuser/configuration/secretkey';

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        CacheInterface $cache = null,
        ResourceConnection $resourceConnection = null,
        ScopeConfigInterface $scopeConfig = null)
    {
        $om = ObjectManager::getInstance();
        if ($cache == null) {
            $cache = $om->get(CacheInterface::class);
        }
        $this->cache = $cache;

        if ($resourceConnection == null) {
            $resourceConnection = $om->get(ResourceConnection::class);
        }
        $this->resourceConnection = $resourceConnection;

        if ($scopeConfig == null) {
            $scopeConfig = $om->get(ScopeConfigInterface::class);
        }
        $this->scopeConfig = $scopeConfig;
    }

    public function getIntegrationInfo($useCache)
    {
        $cacheInterface = $this->cache;
        $integrationConfig = $cacheInterface->load(self::CACHE_KEY);
        if ($integrationConfig && $useCache) {
            $result = $integrationConfig;
        } else {
            $resource = $this->resourceConnection;
            $connection = $resource->getConnection();
            $tableName = $resource->getTableName('queueit_integrationinfo'); //gives table name with prefix

            //Select Data from table
            $sql = "Select * FROM " . $tableName . ' order by id desc limit 1';
            $result = "";
            $rows = $connection->fetchAll($sql);
            if ($rows) {
                $result = $rows[0]['info'];
                $cacheInterface->save($result, self::CACHE_KEY, array(), 5 * 60);
            }
        }
        return hex2bin($result);
    }

    /**
     * Update IntegraionInfo.
     * @param string $integrationInfo
     * @param string $hash
     * @return string
     * @throws \Exception
     * @api
     */
    public function updateIntegrationInfo($integrationInfo, $hash)
    {
        if ($this->isValidRequest($integrationInfo, $hash)) {
            $resource = $this->resourceConnection;
            $connection = $resource->getConnection();
            $tableName = $resource->getTableName('queueit_integrationinfo'); //gives table name with prefix
            //Insert Data into table
            $sql = "Insert Into " . $tableName . " (info) Values ('" . $integrationInfo . "')";
            $connection->query($sql);
            return "success!";
        } else {
            throw new \Exception('Invalid Request');
        }
    }

    private function isValidRequest($integrationInfo, $hash)
    {
        $scopeConfig = $this->scopeConfig;
        $secretKey = $scopeConfig->getValue(
            self::CONFIG_SECRETKEY,
            ScopeInterface::SCOPE_STORE
        );
        $calculatedHash = hash_hmac('sha256', $integrationInfo, $secretKey);
        return $calculatedHash == $hash;
    }
}
