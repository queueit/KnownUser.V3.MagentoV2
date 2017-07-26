<?php
namespace Queueit\KnownUser;
 
interface IntegrationInfoProviderInterface
{
    
    /**
     * Update IntegraionInfo.
     *@api
     * @param string $integrationInfo
     * @param string $hash
     * @return 
     */
   public function updateIntegrationInfo($integrationInfo,$hash);
}