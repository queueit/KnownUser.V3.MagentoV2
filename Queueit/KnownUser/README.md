

# Functionality

Server-side implementation of the QueueIT queueing system. This will put customers into a queue before they can enter the site.
**FPC modules could prevent this module from working properly.**(Please contact queue-it for the practices of handling FPC scenario.)

# Installation
How to install the module using different methods.

## Magento Marketplace
You can find QueueIT Knownuser extension in Magento Marketplace [here](https://marketplace.magento.com/queueit-knownuser.html).
After adding QueueIT extension, you should follow instruction from the official Magento website to install it for your shop [here](https://docs.magento.com/marketplace/user_guide/buyers/install-extension.html).

## Manual
First make a folder for QueueIT extension in your Magento2 app/code folder as below.
```
$ cd <your Magento install dir>/app/code
$ mkdir -p QueueIT/knownuser
```
Download code from [QueueIT Magent2 extension](https://github.com/queueit/KnownUser.V3.MagentoV2) and paste there.
After that:
```
$ cd <your Magento install dir>/app/code/QueueIT
$ mkdir knownuserv3
```
Download code from [QueueIT PHP SDK](https://github.com/queueit/KnownUser.V3.PHP) and paste there.
Afterwards install it with
```
$ php bin/magento setup:upgrade
```
Doing so you can run the below command it should show *Queueit_KnownUser* as an installed module 
```
$ bin/magento module:status
```

## Composer

Install queueit module using composer.js
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
