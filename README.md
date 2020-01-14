 Engagement Cloud for Magento Commerce
 ======
 
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](LICENSE.md)

## Description

This extension provides additional features for merchants running Magento Commerce (previously Magento Enterprise Edition). It must be used alongside the main [Engagement Cloud for Magento 2 extension](https://github.com/dotmailer/dotmailer-magento2-extension). 

- [Full support documentation and setup guides](https://support.dotmailer.com/hc/en-gb/articles/216701227-Enterprise-data-sync-options)

## Compatibility

- Magento >= 2.1.6
- Dotdigitalgroup Email >= 2.5.4 

## 1.0.5

###### What's new
- We've refactored the plugin that sends enterprise data fields to Engagement Cloud, in line with improvements made to our core module.
- We've made some minor improvements to the code, as per Magento coding standards. 

## 1.0.4

###### Bug fix
- We've fixed a bug with the syncing of customer reward points and segment data.
- We've updated a misspelled config key. Merchants are advised to auto-map data fields again following this change.

## V1.0.3

###### Improvements
- We've added support for Magento 2.3.1

## V1.0.2

###### Improvements
- We've added the Magento_Store module as a new dependency
- We've added a foreign key to the email_order entity using Magento's foreign key framework
Fixes
- We've fixed a method name that had been changed on the community version

## V1.0.1

###### Bug fix
- We've fixed the method name that had been changed on the Community version

## V1.0.0

Available additional data fields to be mapped : 

- Reward Points
- Reward Amount
- Reward Expiration Date 		
- Reward Last Used Date 
- Customer Segments
