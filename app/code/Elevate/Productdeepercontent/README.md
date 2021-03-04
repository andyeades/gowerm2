# Mage2 Module Elevate Productdeepercontent

    ``elevate/module-productdeepercontent``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities


## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Elevate`
 - Enable the module by running `php bin/magento module:enable Elevate_Productdeepercontent`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require elevate/module-productdeepercontent`
 - enable the module by running `php bin/magento module:enable Elevate_Productdeepercontent`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

 - Enable Random Display (elevate_deepercontent/general/enable_random_display)

 - Random Number to Display (elevate_deepercontent/general/random_number_to_display)


## Specifications

 - Helper
	- Elevate\Productdeepercontent\Helper\Data

 - Block
	- Output > output.phtml

 - Model
	- Deepercontent


## Attributes

 - Product - Product Deeper Content Ids to Show (product_deepercontent_ids)

