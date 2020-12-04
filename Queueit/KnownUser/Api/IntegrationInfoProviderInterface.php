<?php

namespace Queueit\KnownUser\Api;

interface IntegrationInfoProviderInterface
{

    /**
     * Update IntegraionInfo.
     * @param string $integrationInfo
     * @param string $hash
     * @return string
     * @api
     */
    public function updateIntegrationInfo($integrationInfo, $hash);
}
