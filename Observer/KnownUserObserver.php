<?php

namespace Queueit\KnownUser\Observer;

use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\State;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\ScopeInterface;
use Queueit\KnownUser\KnownUserHandler;


class KnownUserObserver implements ObserverInterface
{
    const CONFIG_ENABLED = 'queueit_knownuser/configuration/enable';
    const CONFIG_SECRETKEY = 'queueit_knownuser/configuration/secretkey';
    const CONFIG_CUSTOMERID = 'queueit_knownuser/configuration/customerid';
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var State
     */
    private $state;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var ResponseInterface
     */
    private $response;
    /**
     * @var KnownUserHandler
     */
    private $knownUserHandler;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        State $state,
        RequestInterface $request,
        ResponseInterface $response,
        KnownUserHandler $knownUserHandler
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->state = $state;
        $this->request = $request;
        $this->response = $response;
        $this->knownUserHandler = $knownUserHandler;
    }

    private function isSystemPath()
    {
        if ($this->state->getAreaCode() == Area::AREA_ADMINHTML) {
            //not any queueing logic for admin pages
            return true;
        }

        if (stripos($this->request->getOriginalPathInfo(), '/swagger') !== false) {
            return true;
        }
        return false;
    }

    public function execute(Observer $observer)
    {
        if ($this->isSystemPath()) {
            return $this;
        }
        $enable = $this->scopeConfig->getValue(
            self::CONFIG_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
        $customerId = $this->scopeConfig->getValue(
            self::CONFIG_CUSTOMERID,
            ScopeInterface::SCOPE_STORE
        );
        $secretKey = $this->scopeConfig->getValue(
            self::CONFIG_SECRETKEY,
            ScopeInterface::SCOPE_STORE
        );
        if (is_null($secretKey) || is_null($customerId) || is_null($enable)) {
            //if config is not set return
            return $this;
        }
        if ($enable) {
            //if the module is enabled
            $this->knownUserHandler->handleRequest($customerId, $secretKey, $this->request, $this->response);
        }
        return $this;
    }
}
