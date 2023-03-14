# Dotdigital for Magento Commerce
[![Packagist Version](https://img.shields.io/packagist/v/dotdigital/dotdigital-magento2-extension-enterprise?color=green&label=stable)](https://github.com/dotmailer/dotmailer-magento2-extension-enterprise/releases)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](LICENSE.md)

## Description
This extension provides additional features for merchants running Adobe Commerce (previously Magento Enterprise Edition). It must be used alongside the main [Dotdigital for Magento 2 extension](https://github.com/dotmailer/dotmailer-magento2-extension). 

## Requirements
The latest version of this module is compatible with:
- Magento v2.3.1+
- `Dotdigitalgroup_Email` v4.19.0+

## Installation
We encourage merchants to install our core modules via our combined **Dotdigital - Marketing Automation** extension, available on the [Magento Marketplace](https://marketplace.magento.com/dotdigital-dotdigital-magento2-os-package.html).

**Steps:**
1. First, ‘purchase’ the [core extension](https://marketplace.magento.com/dotdigital-dotdigital-magento2-os-package.html).
2. Any existing `require` instructions in your composer.json relating to `dotmailer/*` packages must be removed.
3. Now, require the correct packages.
```
composer require dotdigital/dotdigital-magento2-os-package
composer require dotdigital/dotdigital-magento2-extension-enterprise
```

- [Full support documentation and setup guides](https://support.dotdigital.com/hc/en-gb/articles/216701227-Enterprise-data-sync-options).

## Changelog

### 1.10.5

##### Improvements
- Missing tables or other exceptions thrown in the Enterprise CustomerPlugin will no longer prevent other data fields being synced in customer sync.

##### Bug fixes
- We fixed a Page Builder issue with embedding pages or forms that did not have headings when their links were generated.

### 1.10.4

##### Bug fixes
- Merchants using Basic Auth to secure their Magento admin can now use our Page Builder content type as expected.

### 1.10.3

##### Improvements
- Our Page Builder content type now uses a more specific selector for targeting its `script` element.

### 1.10.2

##### Bug fixes
- Pages and forms embedded via Page Builder now once again respect the ‘Stop displaying after a number of appearances’ value.

### 1.10.1

##### Improvements
- We added a `disabled` foreign key for email_catalog to the module, to resolve an error reported in Adobe Commerce.

### 1.10.0

##### Improvements
- We updated some class references in line with the contact sync refactor in the Email module.

### 1.9.1

##### Improvements
- Only active segments will now affect contacts (creating a new segment no longer resets all contacts if the segment status is set to Inactive).

##### Bug fixes
- Contacts were not being reset when matching customers were removed from a segment; this has been fixed.
- We fixed a problem with inaccurate values for the REWARD_AMOUNT data field.

### 1.9.0

##### What’s new

- We’ve updated our PageBuilder content type to ensure form display is not affected when JS scripts are deferred.

### 1.8.0

##### What's new
- This module has been renamed `dotdigital/dotdigital-magento2-extension-enterprise`.

##### Improvements
- We've added a new plugin to provide additional configuration values to our integration insight data cron.
- `setup_version` has been removed from module.xml; in the Dashboard, we now use composer.json to provide the current active module version.

### 1.7.1

##### Bug fixes
- Dotdigital forms in pop-over mode now display in mobile viewports.

### 1.7.0

###### Bug fixes
- We fixed a bug that could prevent loading or editing of Dotdigital pages and forms in Page Builder, if the required access token had expired. 

### 1.5.0

###### Improvements
- This release includes a number of minor updates bundled with Magento 2.4.2, mainly fixes for static and MFTF tests.

###### Bug fixes
- We now retrieve the reward points balance from a different table, so an accurate balance is shown in Dotdigital.
- We've added a plugin to fetch product entity ids for grouped product children. This fixes a bug in Magento Commerce to do with linking from a grouped product to its parent product, where the wrong URL or image path was returned in catalog sync and cart insight.
- Data field mappings are now retrieved in website scope, instead of store scope.

### 1.3.1

###### Bug fixes
- We’ve added some additional hidden fields to our `dotdigital_form` UI component for Page Builder. This fixes a problem with dragging our block to the stage in 2.3.6.

### 1.3.0

###### Improvements
- We've updated our composer dependencies to include `magento/page-builder-commerce`.

### 1.2.0

###### What's new
- Merchants can now use Page Builder to embed pages and forms from Dotdigital. Form submissions can be captured and stored as Magento newsletter subscribers. 

_v1.2.0 of this Enterprise module is no longer compatible with Magento 2.2._

### 1.0.7 

###### Fixes
- We've optimised the plugin that is triggered when creating new customer segments, to resolve possible 'out of memory' errors for merchants with large customer databases.

### 1.0.6

###### Fixes
- We've fixed a regression introduced in 1.0.5, which could cause an error when running the syncs for merchants who had not mapped enterprise data fields.

### 1.0.5

###### What's new
- We've refactored the plugin that sends enterprise data fields to Dotdigital, in line with improvements made to our core module.
- We've made some minor improvements to the code, as per Magento coding standards. 

### 1.0.4

###### Bug fix
- We've fixed a bug with the syncing of customer reward points and segment data.
- We've updated a misspelled config key. Merchants are advised to auto-map data fields again following this change.

### V1.0.3

###### Improvements
- We've added support for Magento 2.3.1

### V1.0.2

###### Improvements
- We've added the Magento_Store module as a new dependency
- We've added a foreign key to the email_order entity using Magento's foreign key framework

###### Fixes
- We've fixed a method name that had been changed on the Community version

### V1.0.1

###### Bug fix
- We've fixed the method name that had been changed on the Community version

### V1.0.0

Available additional data fields to be mapped: 

- Reward Points
- Reward Amount
- Reward Expiration Date 		
- Reward Last Used Date 
- Customer Segments
