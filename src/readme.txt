=== TIVENTS ===
Contributors: willihelwig, aldrahastur, TIVENTS
Tags: events, tickets
Requires at least: 3.0.1
Tested up to: 6.3.1
Stable tag: 1.5.13
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Präsentieren Sie Ihre Produkte von TIVENTS innerhalb Ihrer Website mit einem Shortcode via TIVENTS Public API.

Present your TIVENTS products within your website with a shortcode via TIVENTS Public API.

== Description ==

### DE
Mit TIVENTS können Sie Ihre Veranstaltungen und Gutscheine einfach verkaufen. Über unsere Plattform https://tivents.de können Sie einfach Ihre Produkte anbieten. Kosten entstehen Ihnen nur, wenn auch ein Verkauf getätigt wird. Mit diesem Plugin betten Sie ganz einfach die TIVENTS-Gutscheine und Events auf Ihrer Website ein. Sie entscheiden, ob Sie Gutscheine und Events, oder nur Gutscheine bzw. nur Events präsentieren möchten.
Sie benötigen Ihre Partner-ID. Diese finden Sie in Ihrem Partnerkonto (https://manage.tivents.app).

### EN
With TIVENTS you can easily sell your events and vouchers. You can easily offer your products via our platform https://tivents.de. You only incur costs if a sale is also made. With this plugin you can easily embed the TIVENTS vouchers and events on your website. You decide whether you want to present vouchers and events, or only vouchers or only events.
You need your partner ID. You will find it in your partner account (https://manage.tivents.app).

== Related Links ==

* [TIVENTS Website](https://tivents.info/)
* [TIVENTS Shop](https://tivents.de/)
* [TIVENTS Documentation](https://docs.tivents.info/)
* [Plugin Website](https://docs.tivents.info/books/wordpress-plugin)
* [Support](https://wordpress.org/support/plugin/tivents-products-feed/)
* [Github](https://github.com/tivents/tivents-products-feed/)
* [Term and Conditions](https://docs.tivents.info/books/rechtliches/page/anbieter)
* [Privacy](https://docs.tivents.info/books/wordpress-plugin/page/datenschutz)

== Screenshots ==

1. Admin Bereich
2. Short Code Einbindung
3. Ansicht auf der Seite


== Installation ==
1. Upload `tivents-products-feed` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Was brauche ich um das Plugin zu nutzen? / What do I need to use the plugin? =

### DE
Sie benötigen ein Partnerkonto auf TIVENTS und Ihre Partner-ID. Weitere Informationen erhalten Sie unter https://tivents.info/faq

### EN
You need a partner account on TIVENTS and your partner ID. For more information, please visit https://tivents.info/faq

= Was kann ich anpassen? / What can I adjust? =

### DE
Sie können entscheiden, ob Sie alle Produkte, Gutscheine oder Events anzeigen möchten und die Primär- und Sekundärfarbe anpassen.

### EN
You can decide whether to show all products, vouchers or events and customise the primary and secondary colour.

= Wie werden die Produkte eingebunden? / How are the products integrated? =

### DE
Die Produkte werden durch einen Shortcode auf der gewünschten Seite eingebunden. Dabei wird unsere API aufgerufen, die Produkte zu Ihre Partner ID geholt und dann in einem DIV-Container ausgegeben. Hierbei werden keine Daten der Nutzer oder Ihrer Wordpress Seite an uns übermittelt.

### EN
The products are integrated on the desired page by means of a shortcode. Our API is called, the products are fetched to your partner ID and then displayed in a DIV container. No user data or data from your WordPress page is transmitted to us.


= Werden Daten von den Webseitenbesuchern übertragen? / Is data transferred from website visitors? =

### DE
Es werden keinerlei Daten von Besuchern an TIVENTS übertragen. Der Abruf der Produkte via API erfolgt nur in dem Moment, in der eine Seite mit einem der Shortcodes aufgerufen wird.

### EN
No visitor data is transmitted to TIVENTS. The products via API are only retrieved at the moment a page with one of the shortcodes is called up.

== Changelog ==

= 1.5.13 =
 * decrease php version requirements

= 1.5.12 =
 * small fix in settings controller

= 1.5.11 =
 * fix and sync readme

= 1.5.10 =
 * clarify the readme for the TIVENTS integration

= 1.5.9 =
 * rework plugin, updated fullcalendar

= 1.5.8 =
 * documentation adjust

= 1.5.7 =
 * small fixes, disable footer

= 1.5.6 =
 * fix base url

= 1.5.5 =
 * fix legend presentation
 * fix default date behavior for calendar

= 1.5.4 =
 * added title to calendar
 * added legend to calendar

= 1.5.3 =
 * fix missing default date
 * change event presentation in calendar
 * add specific calendar styles

= 1.5.2 =
 * fix stable tag error

= 1.5.1 =
 * fix error with js adding

= 1.5 =
 * replace bootstrap modals with sweetalert2
 * small fix in api calls
 * added group id for filtering in shortcodes

= 1.4.3 =
 * downgrade php requirements 1.4.4

= 1.4.3 =
 * added list without images

= 1.4.2 =
 * change css for better settings, added id to footer for customizations

= 1.4.1 =
 * small fix for version

= 1.4.0 =
 * add new wordpress version
 * add plugin frontend footer

= 1.3.4 =
 * add sponsorships views
 * add webhook for plugin activation

= 1.3.3 =
 * small changes in typo & wording, preparation for sponsorship presentation, update wordpress version

= 1.3.2 =
 * include all files in plugin, set unique class names

= 1.3.1 =
 * remove footer function

= 1.3.0 =
 * fix up date format problems, fix several small issues

= 1.2.2 =
 * move fullcalendar styles and scripts loading to calendar case in style selector

= 1.2.1 =
 * add bootstrap selector
 * fix some issues for calendar view
 * add default date for calendar view
 * add tiv-status class for styling
 * add startdate input for the calendar

= 1.2.0 =
 * update to new version.
 * preparation for calendar view

= 1.1.0 =

* add grid view, change api for performance reasons

= 1.0.9 =

* fix small issues with qty in shortcode

= 1.0.8 =

* add qty selector
* add fallback for missing partner id and no results

= 1.0.7 =

* made partner id required

= 1.0.6 =

* small structure changes for perfomance reasons

= 1.0.5 =

* add in instance handler

= 1.0.4 =
* add date fallback

= 1.0.3 =
* change url processing

= 1.0 =
* initial release
