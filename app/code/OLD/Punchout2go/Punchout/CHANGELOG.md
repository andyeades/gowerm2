Punchout2Go - PunchOut
===========

###Version 2.1.25
* Add Return Link feature
* Fix JS session check to point to correct base url

###Version 2.1.24
* Fix for amazon module conflict
* New User data mapping
* Transfer cart CSS class configuration

###Version 2.1.23
* Cart Mapping
* Order Mapping


###Version 2.1.22
* Added version number display in admin configuration.
* Added route for version number display
* Level 2
* PunchOut Edit
* 2.2.3 Compatibility
* Removed context debugging introduced in 2.1.21
* Support serialized and JSON buyer request values

### 2.1.21 (2018-07-17)

* Get final price for particular quantity of parent item
* Context debugging on cart transfer script (temporary code; not for production release)

### 2.1.20 (2018-07-17)

* Added parent and child product pricing
* Set default values to clear localdata and session
* JavaScript bug fix; use strict version

### 2.1.19 (2018-07-06)

* Added category, manufacturer, manufacturer ID, and custom line item map data to the cart distiller

### 2.1.18 (2018-07-04)

* Bug fixes and refactoring

### 2.1.17 (2018-07-02)

* Support splittable products
* Support product custom options
* Support parent/child product relationships
* Added "Delay JS Load", "Exclude POSID in Redirect", and "Separate Customized Skus" configuration options
* Skip products without a line ID value

### 2.1.16 (2018-07-02)

* Added "JS Logging", "Clear Local Data", "Call Session Cleanup", "Reload Sections (After Clean)", and "JS Redirection Timeout" configuration options

### 2.1.15 (2018-07-02)

* Added "Start L2 Item Redirect", "Edit Redirect Message", "L2 Redirect Message", and "Use JS Redirection" configuration options
* Fixed Level2 bugs
* Rewrote transfer logic
* Rewrote /session/data controller
* Simplified session/data
* Added /session/clean controller
* Added "is_punchout_session" layout update handler
* Removed punchout_style.css and footer layout reference
* Moved cookie storage JavaScript handling to cacheable frontend script

### 2.1.14 (2018-07-02)

* Will not save cart until the end of the session's actions

### 2.1.13 (2018-06-11)

* Added debugging
* Changed cart save method

### 2.1.12 (2018-07-02)

* Support Level2
* Support Magento's CustomerData\SectionPool
* Added "Ignore Selected Item" configuration option
* Added "Current Version" label in backend
* Added /version controller in frontend
* Added "telephone" to customer address
* Inject shipping data into session customer

### 2.1.8 (2018-07-02)

* Dispatch punchout_new_customer_before_save and punchout_new_customer_after_save events
* Fixed user session and checkout session destroy()

### 2.1.7 (2018-07-02)

* Destroy session data on transfer
* Added "Attach ShipTo to Cart" configuration option
* Added Magento_Checkout, Magento_Sales, and Magento_Quote to the module sequence
* Added comparison between cart quote and session quote for validation
* Destroying and closing preview session information
* Supporting "login" session type
* Clearing local storage and session storage on cart transfer

### 2.1.5 (2018-03-20)

* Added edit-cart support
* Added Magento_Customer module to the module sequence
* Fixed cart injection code for edit-cart requests
* Defaulted "US" country code into injected address
* Fixed update shipping address code to split first and last names
* Added support to use the info_buyRequest in adding items to the cart
* Inject cart to the session _after_ injecting the address
* Added support to check if the session is an edit-cart

### 2.1.4 (2018-01-31)

* Cleaned up header output, removed filter IP validation check

### 2.1.3 (2017-12-15)

* Fixed totals, tax, and shipping collection code
* Fixed quote shipping rates code
* Added region support to helper object
* Fixed name handling, can now accept FirstName/LastName or as a single Name
* Simplified P3P header code

### 2.1.2 (2017-08-14)

* Added cart total, tax, and shipping calculations
* Added "Include Shipping", "Include Tax", and "Include Discount" configuration options
* Now returning shipping rate information in the cart
* Added headers to cart transfer page
* Now injecting shipping addresses into PunchOut session

### 2.1.1 (2017-06-30)

* Refactored extensively
* Fixed Return Link Label display
* Removed unreachable and commented code
* Added lineItem, product, and stashItem to the distiller
* Now throwing \Exception rather than Exception

### 2.1.0 (2017-03-27)

* Added "Allow Frames" configuration option
* Added "Allow PO2Go Frame Ancestors" configuration option, served in the Content-Security-Policy response header
* Added P3P headers
* Added element ID to the transfer link

### 2.0.3 (2017-03-27)

* Initial beta release