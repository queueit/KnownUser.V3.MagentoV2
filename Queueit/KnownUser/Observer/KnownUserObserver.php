<?php
namespace Queueit\KnownUser\Observer;
require_once( __DIR__ .'/../Model/IntegrationInfoProvider.php');
use Magento\Framework\Event\ObserverInterface;



class KnownUserObserver implements ObserverInterface
{


  private $scopeConfig;
  private $state;
  private $request;
  const CONFIG_ENABLED = 'queueit_knownuser/configuration/enable';
  const CONFIG_SECRETKEY = 'queueit_knownuser/configuration/secretkey';
  const CONFIG_CUSTOMERID = 'queueit_knownuser/configuration/customerid';
  public function __construct(
  \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
  \Magento\Framework\App\State $state,
  \Magento\Framework\App\RequestInterface $request)
  {
  $this->scopeConfig = $scopeConfig;
    $this->state= $state;
	$this->request = $request;

  }

  public function execute(\Magento\Framework\Event\Observer $observer)
  {

      if( $this->state->getAreaCode()== \Magento\Framework\App\Area::AREA_ADMINHTML)
      {
        //not any queueing logic for admin pages
        return $this;  
      }
	  
	  if(stripos($this->request->getOriginalPathInfo(), '/swagger')!==false)
	  {
			return $this;
	  }

        $enable = $this->scopeConfig->getValue(
            self::CONFIG_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $customerId = $this->scopeConfig->getValue(
            self::CONFIG_CUSTOMERID,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
         $secretKey = $this->scopeConfig->getValue(
            self::CONFIG_SECRETKEY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if(is_null($secretKey) ||  is_null($customerId) || is_null($enable))
        {
          //if config is not set return
            return $this;
        }
      if($enable)
      {
        //if module is enable and request is not ajax do queue logic
            $knownUserHandler = new \Queueit\KnownUser\KnownUserHandler();
            $knownUserHandler->handleRequest($customerId,$secretKey, $observer);
	 }
	 return $this;
  }
}
