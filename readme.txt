=== MiniMeta Widget ===
Contributors: danielhuesken
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=daniel%40huesken-net%2ede&item_name=Daniel%20Huesken%20Plugin%20Donation&item_number=MiniMeta%20Widget&no_shipping=0&no_note=1&tax=0&currency_code=EUR&lc=DE&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: widget, sidebar, meta, admin, links, login, gravatar
Requires at least: 2.5
Tested up to: 2.8.2
Stable tag: 4.2.3

"Mini" Version of the WordPress Meta Widget.

== Description ==

"Mini" Version of the WP Meta Widget

* Diffrent logon types (Form,Link)
* Uses redirection for logon/logout 
* All standart WP Links can enabled/disabled
* Additional Admin Links (from Plugins too)
* Display Blog Links
* build in style sheet support

Only 2.5+
Please Use Version 3.0.1 for WordPress lower than 2.5

== Installation ==

1. Download MiniMeta Widget.
1. Decompress and upload the contents of the archive into /wp-content/plugins/.
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Plugin Hooks are testet =

MiniMeta Version later 2.7.0 needed:&lt;br /&gt;
[Semisecure Login](http://wordpress.org/extend/plugins/semisecure-login/)<br />
[Raz-Captcha](http://wordpress.org/extend/plugins/raz-captcha/)<br />
[Chap Secure Login](http://wordpress.org/extend/plugins/chap-secure-login/)<br />

= Raz-Captcha Plugin hooks do not work =

In the Plugin file are dependings for files where the plugin work. This dependings must be commentet out.


== Screenshots ==

1. MiniMeta Widget with Login form
2. Some links of MiniMeta Widget when loggt in
3. MiniMeta Widget Options Page

== Changelog ==
= 4.2.3 =
* widget not displayed on when logt out

= 4.2.2 =
* Bugfix for Plugin activate function
* Bugfix for option not Displaying on Pages

= 4.2.1 =
* More translations
* Bug fix for Admin Links
* Fiexed Options not saved Problems

= 4.2.0 =
* &lt;UL&gt; improvments
* Links ar sortable
* class support for styles
*  WP 2.8 WP_Widget class usage
* Use Link Name Dashbord or Seite Admin for Link: Seite Admin
* Form in ul li tags

= 4.1.0 =
* Extra File for WP 2.6 compatibility
* Use some more WP 2.7 functions
* Load Scripts in footer WP 2.8
* Display gravatar
* Display Links by categorys
* Use of contextual help
* Reworked Config Page for more intutive
* Removed K2 SBM Support
* Some bug fixes

= 4.0.2 =
* Some bug fixes and improvments

= 4.0.1 = 
* Bufix for WP autoupdate
* Option Page per function
* Style for login form input felds

= 4.0.0 =
* New Plugin file strucktur
* Support for Sidebar Widget with function
* Unistall function as form and for WP 2.7
* Best K2SBM detection
* Display own Links from Blog Links
* Complete new Optionspage

= 3.5.9 =
* Bug fix for no topics
* New language file selection   
 
= 3.5.8 =
* Path fix for wp 2.5

= 3.5.7 =
* K2RC7 Copatibility older SBM don't work
* https path compatibility

= 3.5.6 =
* Lang Path fix

= 3.5.5 =
* Fixes for K2 1.0-RC6
* WP 2.6 Plugin dir copatibilty

= 3.5.4 =
* admin_head corections
* adminlink generation improfments
* more &lt;ul&gt; xhtml fixes

= 3.5.3 =
* Adminlinks creation for menus with empty submenus and menus without same submenu link

= 3.5.2 =
* Now Hopfull complite bug fix at adminlinks selection

= 3.5.1 =
* Add message to notify when WP is lower than 2.5
* Fix bug at adminlinks selection

= 3.5.0 =
* Full Compatibility to WP 2.5
* Added Opten to disable topics for Admin Links
* Added enable/disable Wordpress Cookie test
* Added enable/disable login/ou redirect
* &lt;ul&gt; xhtml fixes
* Loginform and LoginLink at same time
* Atomatik Admin Links creation as Adminon Plugins Tab. minimeta-adminlinks.php no more nedded.

= 3.0.1 =
* Bugfix &lt;/Optiongroup&gt;
* Bugfix wron &lt;li&gt; (thx David Potter)
* Grammer fixes
* Cookie handlind for login fix
* cusom style not lod fix
* CSS syle fix for thems

= 3.0.0 =
* Better/full support for K2 SBM
* Plasing the widget up to 9 times
* removed WP-Admin Links Plugin Support
* Integrated owen Admin Links
* Style Sheet Support
* Support for own Style Sheet and Admin Links

= 2.7.1 =
* Grammer fixes (thx Joe)
* Updatet German Localisation (thx Joe)
* readded link to Your Profile

= 2.7.0 =
* Support for WP Admin Links (http://wordpress.org/extend/plugins/wp-admin-links/)
* Some more Code Cleanup
* Changed MinMeta.php to minimeta-widget.php
* Added plugin hooks for login form
* Testet with: 
* * Semisecure Login (http://wordpress.org/extend/plugins/semisecure-login/)
* * Raz-Captcha (http://wordpress.org/extend/plugins/raz-captcha/) delete seite dependings
* * Chap Secure Login (http://wordpress.org/extend/plugins/chap-secure-login/)

= 2.6.5 =
* removed Update check because its integratet in WP 2.3

= 2.6.4 =
* Comatibility for Sidebar Modules and K2 SBM
* Added German Translation (Only not in WordPress strings)

= 2.6.3 =
* Code Cleanup
* Fixed some bugs

= 2.6.2 =
* Added User Identity function to Title
* Removed Your Profile form Links and add the link to Title

= 2.6.1 =
* Added Update check

= 2.6.0 =
* More Admin Links Plugin/comments/User
* All links can now enabled/disabeld for login/logoff
* Cleand up Options page

= 2.5.1 =
* Small Bug fix in new post entry

= 2.5.0 =	
* Added links for New Page/Post
* Added Translation functionality
* Added deleting options on deactivateing plugin

= 2.0.0 =
* enable/disable links
* Different Login Types
* Login/Logoff with redirect
			  
=  1.0.0 =
* Inital Release