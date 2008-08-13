=== MiniMeta Widget ===
Contributors: danielhuesken
Donate link: http://danielhuesken.de/
Tags: widget, sidebar, meta, admin, links
Requires at least: 2.5
Tested up to: 2.6
Stable tag: 3.5.9

Mini Verson of the WP Meta Widget with differnt logon types and additional Admin Links.  The Links can all enabeld or disabeld. 

== Description ==

Mini Verson of the WP Meta Widget

* Diffrent logon types (form,link)
* Uses redirection for logon/logout 
* All standart WP Links can enabled/disabled
* Additional Admin Links (from Plugins too)
* build in style sheet support
* Full K2 SBM support

Only 2.5+
Please Use Version 3.0.1 for WordPress lower than 2.5

== Installation ==

1. Download MiniMeta Widget.
1. Decompress and upload the contents of the archive into /wp-content/plugins/.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use your 'Presentation'|'Sidebar Widgets' or 'Presentation'|'K2 Seidbar Manager' settings to drag and configure

== Frequently Asked Questions ==

= After upload a new Version of MiniMeta Widget I have the old one =

Delete old Plugin Directory bevor upload the new version, because the Plugin Filname hase changed in Version 2.7.0.
You must activate the Plugin again.

= Plugin Hooks are testet =

Version later 2.7.0 needed:<br />
[Semisecure Login](http://wordpress.org/extend/plugins/semisecure-login/)<br />
[Raz-Captcha](http://wordpress.org/extend/plugins/raz-captcha/)<br />
[Chap Secure Login](http://wordpress.org/extend/plugins/chap-secure-login/)<br />

= Raz-Captcha Plugin hooks do not work =

In the Plugin file are dependings for files where the plugin work. This dependings must be commentet out.

= Can I make own Style sheet =

Yes, simply copy the minimeta-widget.css file in the custom folder and edit it.
The files in the custom folder will not be overweritet on Plugin Updates.

= The widget coud not displayt =

The Widget would not display if all links to display are disabeld when loggt in or off

== Screenshots ==

1. MiniMeta Widget with Login form
2. Some links of MiniMeta Widget when loggt in
3. MiniMeta Widget confugration Page
4. MiniMeta Widget with Admin Links select box
