# Mage2 Module Elevate LayoutSelector

    ``elevate/module-layoutselector``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Select Layout For Pages 

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Elevate`
 - Enable the module by running `php bin/magento module:enable Elevate_LayoutSelector`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require elevate/module-layoutselector`
 - enable the module by running `php bin/magento module:enable Elevate_LayoutSelector`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration




## Specifications

 - Observer
	- layout_load_before > Elevate\LayoutSelector\Observer\Frontend\Layout\LoadBefore

 - Plugin
	- afterInitProductLayout - Magento\Catalog\Helper\Product\View > Elevate\LayoutSelector\Plugin\Frontend\Magento\Catalog\Helper\Product\View


## Attributes



