=== TIVENTS ===
Contributors: willihelwig, TIVENTS
Tags: events, tickets
Requires at least: 3.0.1
Tested up to: 6.0.3
Stable tag: 1.5.2
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Präsentieren Sie Ihre Produkte von TIVENTS innerhalb Ihrer Website mit einem Shortcode via API.

== Description ==

Mit diesem Plugin betten Sie ganz einfach Ihre Produkte, die über TIVENTS angeboten werden,  auf Ihrer Wordpress Seite ein. Sie entscheiden, ob Sie nur Gutscheine und Events, oder nur Gutscheine bzw. nur Events präsentieren möchten.
Wenn Sie nur Ihre eigenen Gutscheine und/ oder Events anzeigen lassen möchten, benötigen Sie Ihre Partner-ID. Diese finden Sie in Ihrem Partnerkonto (https://manage.tivents.app).

= Related Links =

* [TIVENTS](https://tivents.info/)
* [Documentation](https://docs.tivents.systems/books/wordpress-plugin)
* [Support](https://wordpress.org/support/plugin/tivents-products-feed/)
* [Github](https://github.com/tivents/tivents-products-feed)

== Screenshots ==

1. Listenansicht
2. Kachelansicht
3. Einstellungen
4. Short Code Einbindung


== Installation ==

1. Upload `tivents-products-feed` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Was brauche ich um das Plugin zu nutzen? =

Sie benötigen ein Partnerkonto auf TIVENTS und Ihre Partner-ID. Weitere Informationen erhalten Sie unter https://tivents.info/faq

= Was kann ich anpassen? =

Sie können entscheiden, ob Sie alle Produkte, Gutscheine oder Events anzeigen möchten und die Primär- und Sekundärfarbe anpassen.

== Changelog ==

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
