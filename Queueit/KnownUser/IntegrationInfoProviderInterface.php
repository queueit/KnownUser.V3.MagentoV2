<?php
namespace Queueit\KnownUser;
 
interface IntegrationInfoProviderInterface
{
    
    /**
     * Update IntegraionInfo.
     *@api
     * @param string $integrationinfo
       * @param string $hash
    * @return 
     */
   public function updateIntegrationInfo($integrationinfo,$hash);
}