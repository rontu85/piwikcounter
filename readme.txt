=== PiwikCounter ===
Contributors: rontu
Tags: counter, piwik
Requires at least: 3.0
Tested up to: 3.1
Stable tag: 0.3.0
License: GPLv2 or later

PiwikCounter allows you to show the summary of (unique) visitors as a widget on your blog.

== Description ==
PiwikCounter is a Counter which needs a Piwik installation under your control. Without it, it won't work. 
It get's its data through Piwiks API and caches the amount of visits until yesterday and just adds the amount of visits for the actual date.

== Installation ==
1. Download PiwikCounter
2. Unzip it to your plugins directory.
3. Configure it in the admin menu.
4. Add the widget to your blog.

If you want to show only the unique visitors on your blog, you need to add the following configuration to Piwik.
In config.ini.php add 'enable_processing_unique_visitors_year_and_range = 1' without '' under [General].
After that you should be able to get the amount of unique visitors.

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 0.3.0 =
- added update interval option
- PiwikCounter now just updates the amount of visits before today if they are greater than the old value.

= 0.2.0 =
- added internationalisation
- added a german translation
- added an improved admin menu
- added an option to turn todays visits on and off

= 0.1.2 =
- reactivated unique visitors (see Installation)

= 0.1.1 = 
- selection of unique visitors deactivated, because of a problem with Piwiks API.

== Upgrade Notice ==