=== WPNewCarousels ===
Contributors: arjunjain08	
Author URI: http://www.arjunjain.info/
Donate link: http://www.arjunjain.info
Plugin URI: http://wordpress.org/extend/plugins/wpnewcarousels/  
Tags: carousel, wordpress carousel,admin, plugin, multisite carousel,multisite,wordpress,slider
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: 1.3

This plugin is used to create the carousel that can be inserted to any page.

== Description ==
This plugin is used to create the carousel that can be inserted to any page. This plugin also support wordpress multisite setup.

**Features**

* Instead of delete just disable carousel
* Manage each carousel width,height,effects,speed,animation separately
* Manage carousel for each site in wordpress multisite setup

== Support ==

Fill up this form [ http://www.arjunjain.info/contact ] to leave comments,ask question,suggest new feature etc.

== Installation ==
1. Unzip
2. Upload to your plugin directory
3. Enable the plugin

== Using the WPNewCarousels ==

* Add new carousel in settings page.
* Add data to new carousel carousel in main. 
* Use shortcode [wpnewcarousel name="CAROUSEL_NAME" height="" width=""  startslide="" animationspeed="" imagepausetime="" shownav="" hoverpause=""].
* "height" and "width" are the optional parameters if inserted then these will replace the default Height and Width.
* Only "name" is the required parameter and others are optional.
* "effect" is the type of effect you want to show between image transition.<br />
	The effect parameter can be any of the following:<br /> <b>			
	sliceDown, sliceDownLeft, sliceUp, sliceUpLeft, sliceUpDown, sliceUpDownLeft,
	fold, fade, random, slideInRight, slideInLeft, boxRandom, boxRain, 
	boxRainReverse, boxRainGrow, boxRainGrowReverse</b>
* "startslide" is the starting slide number, default value is 0.
* "animationspeed" is the speed of carousel animation, default value is 500 [ where 1000 = 1sec ].
* "imagepause" is the time between image change, default value is 3000.
* "shownav" is the flag to show navigation with carousel or not, default value is true.
* "hoverpause" is the flag to stop carousel on mouse over, default value is true.

== Screenshots ==
1. Carousel button in default wordpress editor 
2. The WPNewCarousel settings Options
3. Sample carousel image
4. Add carousel data

== Changelog == 

= 1.0 (2012-1-6) =
* Update readme.txt file.

= 1.1 (2012-3-5) =
* Modify manage carousel class.
* Fix dynamic path to stylesheet and script.

= 1.2 (2012-3-28) =
* Modify carousel short code.
* Add startslide,animationspeed,imagepausetime,shownav,hoverpause parameter with carousel.
* Add carousel button in default wordpress editor.

= 1.3 (2012-4-5) =
* Fix IE bugs.
* Add effect parameter with carousel.