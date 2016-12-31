; <?php exit(); ?>
; Please leave the above line in place to make this file unreadable from
; the web.


;	Osxome v0.2, a text-based news/journal system
;	by North Knight Software: http://www.northknight.ca/
;	Released under the MIT License:
;	  http://www.opensource.org/licenses/mit-license.php
;	See LICENSE for more information
;	Last modified December 2, 2008
;


; This is the directory on the hard drive of your server
; where the site will be located
newsDir = /www/osxome/news

; The time offset between your local time, and the server's time.
; The format for the offset is +/- followed by a four digit time with
; a leading 0.
; Some example offsets are: -0100 and +0030
timeOffset = -0200

; The location entered into your web browser to see the page.
pageURL = http://osxome.localhost/

; If you cannot use mod_rewrite on your server, you should change the
; indHref and viewHref variables to the commented alternates
indHref = news/article/
; indHref = "?page=news&amp;action=article&amp;offset="

viewHref = news/view/
; viewHref = "?page=news&amp;action=view&amp;offset="

; Description of the page for the RSS file
rssDescription = An Osxome example site

; Items shown per pae
itemsPerPage = 10

; The page's title
title = Sample template

