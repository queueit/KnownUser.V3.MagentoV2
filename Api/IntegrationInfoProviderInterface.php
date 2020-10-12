<?php
namespace Queueit\KnownUser\Api;
 
interface IntegrationInfoProviderInterface
{
    
    /**
     * Update IntegraionInfo.
     *@api
     * @param string $integrationInfo
     * @param string $hash
     * @return string
     */
   public function updateIntegrationInfo($integrationInfo,$hash);
}