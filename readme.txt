=== CDEK for WooCommerce ===
Contributors: artemkomarov
Tags: woocommerce, woocommerce shipping, ecommerce, shipping, cdek
Requires at least: 5
Tested up to: 5.5
Stable tag: 1.0.6
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Automatically calculate the shipping cost for CDEK tariffs

== Description ==

The plugin allows you to automatically calculate shipping costs for CDEK tariffs using official API.

* Contains all CDEK shipping tariffs
* Calculate shipping costs based selected tariff and the weight of the cart
* Display time of delivery
* Specify an additional cost and delivery time
* Specify custom package dimensions

= PRO Extension  =

The extension adds single-click actions to synchronize orders with CDEK’s personal dashboard and sends emails with tracking codes. Also, it synchronizes order status with shipment status, so your orders will automatically be marked “Complete” once the recipient receives their package.

* Synchronizes orders with the CDEK dashboard
* Generates and prints order invoices and barcode
* Displays the current order shipment status
* Synchronizes orders with tracking status
* Emails tracking numbers to customers
* Display tracking numbers for customer's account page
* Displays a map of pick-up points
* Hides delivery methods based on custom conditions
* Uses custom package dimensions
* Registration of an application for a courier call
* Support several shipment points (warehouses)
* Allows you to create a free shipping option based on the CDEK tariff
* Allows you to calculate shipping coast via admin panel

[Demo website](https://yumecommerce.com/cdek/). PRO extension can be purchased on the [official WooCommerce marketplace](https://woocommerce.com/products/cdek-pro-for-woocommerce/)

== Installation ==

= From your WordPress dashboard =

Visit 'Plugins > Add New'
Search for 'CDEK for WooCommerce'
Install and activate CDEK for WooCommerce from your Plugins page.

Then create new Shipping Zone and add CDEK as a method.

== Frequently Asked Questions ==

= How accurate is this plugin? =

The plugin by itself has no methods to calculate the shipping price. All data comes from official CDEK API

== Screenshots ==

1. Basic settings
2. PRO Extension: choosing shipping state and city from database
3. PRO Extension: choosing pick-up points on the map
4. PRO Extension: automatically track shipments and sending tracking code

== Changelog ==

= 1.0.6 =

* Tweak - calculate shipping rate via package dimensions if provided
* Added - WooCommerce 4.4 support
* Added for PRO - options to separate shipment points (warehouses)
* Added for PRO - registration of an application for a courier call

= 1.0.5 =

* Fix CDEK API change

= 1.0.4 =

* Tweak - Sort pickup points in alphabetical order
* Tweak for PRO - Open selected pickup point on the map

= 1.0.3 =

Added option to remove ordered value
Added percentage fee cost options
Tweak select pick-up point width

= 1.0.2 =

Fix shipping calculation from abroad

= 1.0.1 =

Add new seller options
Fix international delivery point select.

= 1.0.0 =

Initial Release.

