The «[**Price Format**](https://mage2.pro/c/extensions/price-format)» module for Magento 2 allows you to setup a custom display format for the prices and other currency values (discounts, taxes, sales amounts, etc.) both for the frontend and the backend areas.
The price display settings are individual per currency and per store.  
The module supports unlimited number of currencies.
The module is **free** and **open source**.

**Demo video**: https://www.youtube.com/watch?v=0Edmk8uKn0Q

## Screenshots
### Example 1 
![](https://mage2.pro/uploads/default/original/1X/a9bfd57e4e768b57bce80d1c2fbf865a01a57a54.png)

### Example 2
![](https://mage2.pro/uploads/default/original/1X/a4e92883b81a39ab44f5973f0f3850afad59237e.png)

### Example 3
![](https://mage2.pro/uploads/default/original/1X/95a07446619cabca915135dde374b2f13403a2f3.png)

### Example 4
![](https://mage2.pro/uploads/default/original/1X/6d31c158c2d9b2b9811f8734e43ed348178b08e5.png)

### 5. Extension settings
![](https://mage2.pro/uploads/default/original/1X/67fb379924165b2c8086d54de415596b4f5c0eb5.png)

## How to install
[Hire me in Upwork](https://upwork.com/fl/mage2pro), and I will: 
- install and configure the module properly on your website
- answer your questions
- solve compatiblity problems with third-party checkout, shipping, marketing modules
- implement new features you need 

### 2. Self-installation
```
bin/magento maintenance:enable
rm -f composer.lock
composer clear-cache
composer require mage2pro/currency-format:*
bin/magento setup:upgrade
bin/magento cache:enable
rm -rf var/di var/generation generated/code
bin/magento setup:di:compile
rm -rf pub/static/*
bin/magento setup:static-content:deploy -f en_US <additional locales>
bin/magento maintenance:disable
```

## How to update
```
bin/magento maintenance:enable
composer remove mage2pro/currency-format
rm -f composer.lock
composer clear-cache
composer require mage2pro/currency-format:*
bin/magento setup:upgrade
bin/magento cache:enable
rm -rf var/di var/generation generated/code
bin/magento setup:di:compile
rm -rf pub/static/*
bin/magento setup:static-content:deploy -f en_US <additional locales>
bin/magento maintenance:disable
```