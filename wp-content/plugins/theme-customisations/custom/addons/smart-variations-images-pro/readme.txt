=== Smart Variations Images PRO ===
Contributors: drosendo
Tags: WooCommerce, images variations, gallery, woocommerce variations, woocommerce variations images, woocommerce images
Requires at least: 4.0.0
Tested up to: 4.5.3
Stable tag: 3.0.5

This is a WooCommerce extension plugin, that allows the user to add any number of images to the product images gallery and be used as variable product variations images in a very simple and quick way, without having to insert images p/variation.


== Installation ==

1. Upload the entire `smart-variations-images` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. On your product assign the product attributes and save
4. Go to Product Gallery and upload/choose your images
5. Assing the slugs to be used for the variation images for each of the image and save.
6. Good luck with sales :)


== Frequently Asked Questions ==

= The plugin doesnt work with my theme =

Themes that follow the default WooCommerce implementation will usually work with this plugin. However, some themes use an unorthodox method to add their own lightbox/slider, which breaks the hooks this plugin needs.

= The plugin works but messes up the styling of the images =

You can try several options here.

1. Go to WooCommerce > SVI (Smart Variations Images) and activate or deactivate the option "Enable WooCommerce default product image"
2. Disable other plugins that change the Product image default behavior.
3. Read the Support Threads.


= How do I configure it to work? =

1. Assign your product Atrributes and click "Save atributes"
2. Create the variations you need, and click "Publish" or "Save as draft"
3. Go to Product Gallery and upload/choose the images you are going to use
4. For each image assing the slugs to be used for the variation images swap
5. Publish you product

You can skip steps 1 and 2 if your product is already setup with Atributes and Variations.

== Screenshots ==

1. Add images to your Product Gallery
2. Choose the images to be used and select the "slug" of the variation in the "Variation Slug" field.
3. Hides all other images that don't match the variation, and show only the default color, if no default is chosen, the gallery is hidden.
4. On change the variation, images in the gallery also change to match the variation. The image in the gallery when click should show in the bigger image(above).
4. Lens Zoom in action (activate it in WooCommerce > SVI (Smart Variations Images)

== Changelog ==

= 3.0.5 =
* Code cleanup

= 3.0.4 =
* Reverted images sizes

= 3.0.3 =
* Minor fix: Plugin requesting update after update (wrong version control)
* Added mobile conditions
* Lens fix - Disabled lens if mobile phones detected
* Slider Thumbnail Position, on mobile phones falls back to horizontal.
* Slider Force Mobile Thumbnail Position, force use of selected Thumbnail position on mobile phones.

= 3.0.2 =
* Added right thumbnail position for Slider
* Minor Lens fix
* Minor slider Fix

= 3.0.1 =
* Force SVI fixed responsive when in mobile
* Fix validation requests being made to frequently
* Fix permission access to SVI settings only allowing Admin users, now Shop managers also have access

= 3.0 =
* Major release
* Added new Back-end options panel now with reduxFramework
* Added pre-loader animation on Magnifier Lens
* Added tweaks for more theme support
* Fixed double Lens images

= 2.4.1 =
* Added more thumbnails display, now max 10.
* Added option to change thumbs immediately after select

= 2.4 =
* Added fallback option for variations with "any kind" of option
* Fixes lens issue fading outside image

= 2.3 =
* Fixed a major error causing resource limit due to many calls for license validation
* Added option for hide thumbnails until variation is selected

= 2.2.9 =
* Fixed bug when there are no images in the product gallery, slider would not load anything.
* Added capability to work with variation even if variations are set to "Any Variation"

= 2.2.8 =
* Fixed minor issue with Ligthbox thumbnails opening ligthbox.

= 2.2.7 =
* Fixed minor bug in type comparison not swapping select

= 2.2.6 =
* Feature added option to Swap Variation select on thumbnail click 
* Feature added option to display chosen variation image in cart/checkout instead of default Product image
* Fix slider Vertical Thumbnails sometimes not loading properly
* Fix Ligthbox not opening properly in some themes
* Organized Select option for variation in product edit

= 2.2.5 =
* Fix compatibility with WooCommerce throwing "Sorry, this product is unavailable. Please choose a different combination." 

= 2.2.4 =
* Fix correct columns display with last image

= 2.2.3 =
* Fix Lens for no conflict
* Make lens load original image
* Fixed notice error showing up in cart

= 2.2.2 =
* Fix automatic update

= 2.2.1 =
* Fix Admin Notice messages

= 2.2 =
* WPML Compatible
* Fix Notice messages
* Automatic Updates

= 2.1 =
* Fix issue with multiple variations being called with ajax not changing
* Added option to force template position
* Added Option to add custom CSS class (normally from theme)

= 2.0 =
* Major release
* Added Vertical Slider
* Added lensZoom extra options
* Better theme compatibility

= 1.2 =
* Fixed issue with some product not showing slides if product is variable and as no variation attributed to images

