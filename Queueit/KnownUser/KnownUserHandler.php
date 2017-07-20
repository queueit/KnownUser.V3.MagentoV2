<?php
namespace Queueit\KnownUser;
error_reporting(E_ALL);
require_once( __DIR__ .'\IntegrationInfoProvider.php');
require_once( __DIR__ .'\lib\knownuser\Models.php');
require_once( __DIR__ .'\lib\knownuser\KnownUser.php');


class KnownUserHandler
{


    public function handleRequest($customerId, $secretKey)
    {
        $queueittoken = isset( $_GET["queueittoken"] )? $_GET["queueittoken"] :'';
        $configProvider = new IntegrationInfoProvider();
        $configText =  $configProvider->getIntegrationInfo(true);
        try
        {
            $fullUrl =$this->getFullRequestUri();
            $result = \QueueIT\KnownUserV3\SDK\KnownUser::validateRequestByIntegrationConfig($fullUrl, 
              $queueittoken, $configText,$customerId, $secretKey);
          //var_dump($result);
            if($result->doRedirect())
            {
                //Send the user to the queue - either becuase hash was missing or becuase is was invalid
            header('Location: '.$result->redirectUrl);
                die();
            }
            if(!empty($queueittoken))
            {
                
            //Request can continue - we remove queueittoken form querystring parameter to avoid sharing of user specific token
                if(strpos($fullUrl,"&queueittoken=")!==false)
                {
            header('Location: '.str_replace("&queueittoken=".$queueittoken,"",$fullUrl));
                }
                else if(strpos($fullUrl,"?queueittoken=".$queueittoken."&")!==false)
                {
            header('Location: '.str_replace("queueittoken=".$queueittoken,"",  $fullUrl));
                }
                else if(strpos($fullUrl,"?queueittoken=".$queueittoken)!==false)
                {
            header('Location: '.str_replace("?queueittoken=".$queueittoken,"",  $fullUrl));
                }
            die();
            }
        }
        catch(\Exception $e)
        {
            var_dump($e);
          //log the exception
        }
    }
        private function getFullRequestUri()
    {
        // Get HTTP/HTTPS (the possible values for this vary from server to server)
        $myUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && !in_array(strtolower($_SERVER['HTTPS']),array('off','no'))) ? 'https' : 'http';
        // Get domain portion
        $myUrl .= '://'.$_SERVER['HTTP_HOST'];
        // Get path to script
        $myUrl .= $_SERVER['REQUEST_URI'];
        // Add path info, if any
        if (!empty($_SERVER['PATH_INFO'])) $myUrl .= $_SERVER['PATH_INFO'];
        return $myUrl; 
    }
      

}