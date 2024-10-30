=== Board Document Manager from CHUHPL ===
Contributors: ManiacalV, chuhpl
Tags: library, board, documents
Requires at least: 5
Tested up to: 6.2
Stable tag: trunk
License: GPLv2 or later

A simple management system for Board Meeting agendas and meeting minutes. 

== Description ==

This plugin collects PDF board minutes and agendas (regular and special) and displays them in an archive for your patrons and customers. You manage and upload the files from your Administration console.

There is a short code that will then display the latest agenda and all of your meeting notes.

== Installation ==

Install as normal and you should be ready to start uploading your files from the "Board Docs" menu.

== Frequently Asked Questions ==

= Where are the documents saved? =

The documents are all saved in "/wp-content/uploads/board-document-manager-from-chuhpl"

= Are the documents deleted when I uninstall the plugin. =

Yes. Each document is deleted, then the folder.

= What is the shortcode to show the display? =

Put the following shortcode on a page or post where you want to view the latest Agenda and an archive of all of the minutes.

[showBoardDocumentManager]

== Screenshots ==

1. Entering new documents.
2. Viewing the documents in the admin area.
3. The view of how the shortcode renders.

== Changelog ==
= 1.9.1 =
Updated a few depreciated functions and uses to get it working in PHP 8

= 1.9 =
* Changed a hard coded wp_ to a $wpdb->prefix

= 1.8 =
* Changed the enqueuing order to suppress error and constructor names to __construct

= 1.7 = 
* Changed the wp_die() to die() in showMe.php. This was adding data and breaking the downloads.

= 1.6 =
* Fixed a link to go to the add page, not main menu

= 1.5 =
* Found a few text strings not set up for i18n.

= 1.4 =
* Added a "Add another document" link on the Add success page.

= 1.3 =
* Cleaned up a few typos.

= 1.2 =
* Cleaned up a few typos.

= 1.1 =
* Added screenshots, more info, instructions, etc.

= 1.0 =
* Finalized by renaming the folders properly.

= 0.6 =
* Proving I'm new to this, let's add load_plugin_textdomain() and see if that helps.

= 0.5 =
* Renamed text domain with dashes instead of underscores.

= 0.4 =
* Fixed the domain name for i18n

= 0.3 =
* Added plugin URI and text domain for i18n.

= 0.2 =
* Removed uneeded break on 242 in bdmAdd.php

= 0.1 =
* First Version

== Upgrade Notice ==

== Other plugins ==
