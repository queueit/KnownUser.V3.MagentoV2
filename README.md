

# Functionality

Server-side implementation of the QueueIT queueing system. This will put customers into a queue before they can enter the site.
**FPC modules could prevent this module from working properly.**(Please contact queue-it for the practices of handling FPC scenario.)

# Installation
How to install the module using different methods.


## Manual
First clone this repository inside your Magento2 `app/code` folder like below:

```bash
cd <your Magento 2 install dir>/app/code
git clone https://github.com/queueit/KnownUser.V3.MagentoV2.git Queueit/KnownUser
```
Afterwards install the extension:
```
cd <your Magento 2 install dir>
php bin/magento setup:upgrade
```
Doing so you can run the below command. It should show *Queueit_KnownUser* as an installed module:
 
```bash
bin/magento module:status
```
If Queueit_KnownUser was listed as Disabled module run the below command to enable it
```
php bin/magento module:enable Queueit_KnownUser
php bin/magento setup:upgrade
```

## Composer

Install queueit module using composer
```
$ composer require queueit/knownuser
```
*Enter your [`authentication`](https://devdocs.magento.com/guides/v2.3/install-gde/prereq/connect-auth.html) keys. Your public key is your username; your private key is your password.*
Afterwards install it with
```
$ php bin/magento setup:upgrade
```
Doing so you can run the below command it should show *Queueit_KnownUser* as an installed module 
```
$ bin/magento module:status
```
You can find an official help about how to install a Magento extension [here](https://devdocs.magento.com/extensions/install/).
# Configuration
After installing the module, go to the below menu and enable the module, enter your CustomerId and Secret Key which you have got from QueueIT.
`Stores -> Configuration -> Services -> Queueit KnownUser`

In here 

- **Enabled**: Enable/disable module execution. If enabled this will generally give a redirect on every request. So it is advanced to enable it only before an event is starting.
- **CustomerID**: This is usually your account name
- **Secret Key**: This is the secret key found in QueueIt: `Account -> Security -> Known User (tab) -> Default secret key`
- **How are the configs updated**: The way changes on QueueIT's side are requested
  - Push: Configure url in QueueIT. Postback url is `{{store_url}}/rest/V1/queueit/integrationinfo/`
  - Manual: To Update configuration manually or to see the current configuration: `Content -> QueueIt KnownUser -> Admin`. You can see the current config at this page and also upload confugartion file for updating it. 


# FPC  (Protecting ajax calls on static pages)
If you have Full Page Cache enabled you should add queue-it javascript to your pages as below :
1. Make sure KnownUser code will not run on static pages (by ignoring those URLs in your integration configuration).
2. Add below JavaScript tags to all static pages : 
    You can add this tag in the header files : `...\vendor\magento\module-theme\view\frontend\templates\html\header.phtml`
    ```
    <script type="text/javascript" src="//static.queue-it.net/script/queueclient.min.js"></script>
    <script
     data-queueit-intercept-domain="{YOUR_CURRENT_DOMAIN}"
       data-queueit-intercept="true"
      data-queueit-c="{YOUR_CUSTOMER_ID}"
      type="text/javascript"
      src="//static.queue-it.net/script/queueconfigloader.min.js">
    </script>
    ```
3. Add some triggers for your dynamic ajax calls you want to queue users on.

