1.3.2
=============
* Improvements:
    * Implement default values. Admin can set default simple product on configurable product edit form.

* Fixed bugs:
    * Fixed an issue with swatch and configurable renderer.
    * Fixed an issue with identical cache on simple product pages.
    * Fixed an issue with swatches on category pages.

* Code quality improvements:
    * Fixed code violations according to Magento Extension Quality Program

1.4.0
=============
* Improvements:
    * Add matrix grid for configurable products.
    * Add new config in admin panel for options matrix.
    * Add tier price for matrix grid.
    * Disable default magento qty field and stock status for products.
    * Now when you add to the cart add all products that have been specified field qty in matrix.
    * Grid displaying columns: option, Available qty, Stock Status, Price, Tier Price(if enable in config.), Qty.
    * Add configurable product from category page.
    * If disable Manage Stock in magento, Available qty column hide.
    * Add custom shipping logic with displaying attributes in text input.
* Fixed bugs:
    * Stable work with module Magenerds_BasePrise.
    * Added scrolling for matrix grid when table more than display.
    * Remove QTY field if option not available.
    * "in Stock" text change to "In Stock".
    * Validate qty fields in matrix.
    * Fixed Custom content options value in frontend.
    * Fixed issue with cacheable block.
    * Fixed style for matrix.

1.4.4
=============
* Improvements:
    * Added compatibility with Mirasvit_GiftRegistry.
    * Added new fields in edit product page for custom attributes.
* Fixed bugs:
    * Fixed bugs with any custom attributes types.
    * Fixed issue with the same attribute type in frontend.

1.4.6
=============
* Improvements:
    * Added the ability to link configurable products to the same attributes on the product bundle page.
    * Increased speed of loading the product page
    * Added changing some metadata when changing a variation of configurable product
    * Added the ability to choose whether custom content will be added existing content or whether existing content will be replaced
* Fixed bugs:
    * Bug when bundle products disappear after placing an order.
    * Bug when to add a configurable product with two or more attributes to the cart (Error: "You need to choose options for your item.").
    * Issue with incorrect currency display in bundle products
    * Issue with incorrect price update in the matrix
    * Issue on configurable product page on magento version 2.2.7
    * Wrong image order when changing swatches in configurable product
    * Issue with video playback in gallery
    * Price on List page doesn't show correctly when Tax is enabled.
    * Issue where a configurable product was shown as an "Out of stock", when all its variations have required custom options
    * Error in the admin panel when editing a bundle product on the Magento version 2.3
    * Issue with re-index when tables in DB have prefix
    * Issues with different product bundle options
    * Added separation of cart positions when adding a bundle product, which has an option with a configurable product
    * Added information about the selected options of the configurable product that is included in the bundle product in Admin/Sales/Order
    * Fix Bug with default simple product id not getting removed when all the options are deselected and clicked on add to cart.
    * Fixed problem when adding bundle product to the cart when the matrix enabled
    * Fixed display of custom options when the matrix enabled
    * Fixed display of some custom options (date, date_time, time, area, field)
    * Fixed display of the matrix, if the option of the visual swatch is not set
    * Fixed issue with configurable product options when added to bundle product
    * Fixed an issue that occurred when a user parameter has a price in percent, and the product is included in the package
    * Fixed problem with placing an order on M2.3.1
    * Hide price if product out of stock(matrix)
    * Added a decrease quantity of products, that are included in the configurable product that is included in the bundle product when the order is placed
    * Displaying selected custom options in the cart, when the bundle product added to the cart
    * Fixed display of custom options for checkbox option of bundle product
    * Fixed problem with disabled option “dynamic price” in the bundle product
    * Fixed issue with special price in bundle products.

    1.5.0
    =============
    * Improvements:
        * QTY of products in stock is now displayed at the product page
        * Matrix grid can now be enabled per product
        * Added mass actions for configurable product attributes used in matrix grid
        * Matrix grid can now be enabled per store view
        * URL rewrites extension command can now be applied products with all visibility settings, not only ‘not visible individually’
        * Added an option to enable/disable breadcrumb updates
        * Added an option to display a drop-down (attribute type) on the product pages with matrix grid enabled
        * Added possibility to hide prices for ‘Not Logged In’ customers
        * Added automatic updates of the product name in the summary block at bundle product pages
        * Added automatic updates of the images of the bundle product options
        * Full Magento 2.3 compatibility
    * Fixed bugs:
        * Fixed issue with the recalculation of the price of the bundle product in which there is a configurable product in decimal
        * Fixed issue with escaping characters in canonical URL
        * Fixed issue with setting up area code in the command line
        * Fixed issue with di:compile for Magento 2.2.*
        * Fixed issue with di:compile for Magento 2.2.8
        * Fixed issue with recalculating the tier prices when the qty the product option uses decimals
        * Fixed issue with displaying tier prices in the matrix of a configurable product
        * Fixed the “detail tab” improperly displaying for Magento 2.3.x
        * Fixed issue when the variation of the configurable product has only one custom option with the type ‘file’
        * Fixed issue with custom options when a configurable product has only a drop-down for choosing variations
        * Fixed issue when the variation of the configurable product has custom options with the type ‘select’
        * Fixed issue with default Magento 2 placeholder images displayed for out of stock simple products
        * Fixed issue with price range applied to the wrong products at the product details page
        * Fixed issue when customers aren't allowed to change a previous selection in some cases
        * Fixed issue with editing products in the shopping cart
        * Fixed issue with adding the bundle product to the cart on Magento 2.3.1
        * Fixed issue with tier price display for the variations of configurable products in a bundle
        * Fixed issue with displaying regular price at the bundle product page. Regular price will now be displayed only for the ‘fixed’ price type
        * Fixed issue with 'Maximum function nesting level'
        * Fixed a broken bundle product page on Magento 2.3.2
        * Fixed issue with display the default price when loading a page of a configurable product
        * Fixed issue with duplicating custom options in the cart
        * Fixed issue with 'As low as' label not being properly removed
        * Fixed issue with displaying image swatches
        * Removed custom image sorting in the product gallery
        * Fixed issue with price recalculation when using custom options of ‘checkbox’ type
        * Fixed issue at the wish list page
        * Fixed issue with adding a bundle product with options that have configurable product in the wish list
        * Added a condition to display only ‘From’ price if the min price is equal to the max price
        * Fixed issue with the wrong size of the main image at configurable product pages
        * Fixed issue with video playback at configurable product pages
        * Price "From" of the product bundle option now properly considers child configurable product prices
        * Fixed issue with automatically updating description block at the product pages
        * Fixed issue at the checkout page when choosing shipping method for a bundle product with a configurable products attached
        * Fixed issue with removing the variations of a custom products, when the variation has a required custom option
        * Fixed issue where the breadcrumbs and URLs were not properly updated when removing selection on the child products

    1.5.1
    =============
    * Improvements:
        * Added the ability to disable the "Custom options for variations of configurable products" functionality
    * Fixed bugs:
        * Fixed issue with the cart edit page
        * Fixed issue with displaying of tier prices for different user groups
        * Fixed issue with adding a bundle product with options that have configurable product with custom options in the wish list
        * Fixed URL update issue
        * Fixed issue with displaying drop-down(attribute type) on the product pages with matrix grid
        * Fixed a problem when changing variations when a minimum and maximum price equal to each other appeared in the price range
        * Fixed issue with updating the price of custom option in the summary block of a bundle product
        * Fixed issue where the price range was hiding when changing variation of the configurable product
        * Fixed issue with displaying bundle products in the cart
        * Fixed issue with updating tier price block when changing the variation of a configurable product
        * Fixed issue when the 'From - To' price disappears when changing variations of configurable product
        * Fixed issue where the tier price was not consider into in 'From - To' price
        * Fixed a matrix issue where custom options were added to products that do not have them
        * Fixed issue where the 'From - To' price was not updated on the categories page
        * Fixed issue with showing/hiding prices, tier prices block and price range for unregistered users on the category page
        * Added ability to disable adding products with custom options to options of bundle product
        * Fixed issue due to which the price of related products changed when using the default variation
        * Fixed issue with adding several product variations to the cart (matrix grid) at once, which has a custom option of file type
        * Fixed a problem where the regular price did not displaying when loading the page (matrix)
        * Fixed matrix grid issue when only one attribute is set
        * Fixed issue when configurable product and bundle product aren't ordered together
        * Fixed displaying product details in the shopping cart and wish list
        * Fixed issue with an empty order when placing an order with a bundle product that has checkbox options
        * Fixed date formatting in the cart (custom option)
        * Fixed issue with adding a configurable product with a custom file option to the wishlist
        * Fixed issue with adding a configurable product with a custom  option to the cart when the matrix is ​​disabled at the product level
        * Fixed issue where it was impossible to add a bundle product to a wish list without specifying configurable product options
        * Fixed issue with updating the price in the bundle product summary block when changing the qty of options
        * Fixed issue with updating product link on category page
        * Image display for a bundle option, in which there only one product
        * Fixed an issue where swatches of a configurable product did not match the product when loading the bundle product page
        * Fixed incorrect text formatting on the product edit page in Magento 2.3.4
        * Fixed issue where the link to the reviews tab did not work on the configurable  product page (Magento 2.3.4)
        * Fixed issue with hide prices of bundle product for ‘Not Logged In’ customers

1.5.2
=============
* Improvements:
    * Fixed template overrides as per Magento 2.3.4 standards.
    * Change configurable js to mixin instead of map.
    * Template override compatible with Magento2 2.3.3 and 2.3.4.
    * HistoryJS duplicate for configurable and swatch-render JS.
    * Price Config doesn't update for Magento2.3.5.
    * Regenerate a product urls for any product_type.
    * Introduce loggers for extension logging.
    * Can restore admin system config back to default.
* Fixed bugs:
    * Fixed issue with attributes(description) label translation.
    * Bug with first load of _setOpenGraph fails if productID not set properly.
    * Fixed issue, Undefined index error when field missing in product data while edit product.
    * Fixed issue with incorrect updating of the summary block on the bundle product page
    * Fix an issue with Requistion list having only simple product and adding that to cart.
    * Fix issue with an update of product short_description if description is disabled.
    * Code Refactor
    * Update of child custom option on edit cart.
    * Product showing special price block even if not applied, remove condition.
    * Custom Option is load by id doesn't load correct product.
    * General phpstan fix.
    * Fix Bundle Product undefined index issue.
    * Fix in-correct roundup of tier-prices with Matrix Render.
    * Fix Bundle with Config product Add to cart issue.
    * Fixed the issue with updating the qty and price in the matrix
    * Fixed the issue with updating short_description and description
    * Fixed minor issues with the matrix on a configurable product page
    * Fixed issue with the updating price row in the summary block
    * Fixed issue with formatting the cart position, when the child configurable product of the bundle product has a file type custom option
    * Fixed a problem with the matrix, in which all options of a configurable product are out of stock (Magento 2.4)
    * Fixed issue with adding a bundle product to the cart (Magento 2.4)
    * Fixed issue with custom shipping logic
    * Fixed price range issue with tier price
    * Fixed issue with adding product to the bundle product option (Magento 2.4)
    * Fixed issue with adding configurable product to the cart (Magento 2.4)
    * Fixed a problem with adding a virtual product to the cart, which is a child of the configurable
    * Fixed issue with displaying the child products of configurable product
    * Fixed not allowing saving the same cache on home page with same default values
    * Fixed a problem with not saving cache on home page
    * Fixed issue with adding a product to the cart from the product list (Magento 2.4)
