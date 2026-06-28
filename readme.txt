=== classic-starter ===

Contributors: momiji
Tags: blog, two-columns, right-sidebar, custom-menu, featured-images, sticky-post, threaded-comments, translation-ready, wide-blocks, editor-style, block-styles, block-patterns, full-width-template
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 8.0
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A minimal classic theme meant to be extended. Colours, spacing and type live in CSS custom properties, and each page loads only the CSS it needs.

== Description ==

classic-starter is a minimal classic theme meant to be used as a base for building new
themes. It ships with a small set of templates and a token-based CSS architecture, so
that spacing, colour, type scale and breakpoints can all be adjusted from one place.

Stylesheets are enqueued per page type, so a visitor only downloads the CSS the current
page actually needs.

Features:

* Templates for the front page, the post list, search results, archives, single posts,
  pages, comments and 404 errors
* A "No Sidebar" page template for pages such as contact forms and landing pages, where
  wide and full-width blocks can stretch to the edges of the screen
* A front page that shows the static page content (when one is set), the latest posts
  and a widget area at the bottom for a call to action
* A post list with featured images, dates, categories, excerpts cut to a fixed number of
  lines, and numbered pagination
* A primary navigation menu: on wide screens it is centred below the site title and its
  sub-menus open as a drop-down below each item; on small screens it slides in from the right
  as a panel, with sub-menus as an accordion
* Breadcrumbs below the header on every page except the front page (structured data is
  left to SEO plugins)
* A footer menu, a privacy policy link and a copyright line
* A right sidebar widget area, moved below the content on small screens
* The tagline shown in a band above the header
* Custom logo support, falling back to the site title as text
* A full-screen search modal, opened from an icon button in the header
* Post, comment and Latest Posts block dates in a single theme format (Y.m.d), which can
  be changed through translation
* A "Line" block style for buttons, and "Call to action" and "Latest posts card" block
  patterns
* Editor styles, so the editor matches the front end
* Editor colour and font size presets limited to the design tokens, with custom colours,
  gradients and font sizes turned off, so content stays consistent with the design
* Design tokens defined in :root, so colours and spacing can be re-themed in one place
* Per-page conditional stylesheet loading, to avoid serving unused CSS
* Transitions, animations and smooth scrolling turned off when the visitor prefers
  reduced motion
* Threaded comments in a chat-style layout, with an author badge
* Translation ready, with a Japanese translation included

== Installation ==

1. In your WordPress admin, go to Appearance > Themes and click Add New.
2. Click Upload Theme, choose the theme zip file, and click Install Now.
3. Click Activate.

== Frequently Asked Questions ==

= Where do I change the colours and spacing? =

All colours, spacing steps and font sizes are defined as custom properties in the :root
block of assets/css/tokens.css. Change the values there rather than editing individual
rules. The same file is loaded in the block editor, so the editor follows the changes.

= How do I add a navigation menu? =

Go to Appearance > Menus, create a menu and assign it to the Primary Menu location (the
header) or the Footer Menu location (the footer). A menu is only rendered once one has
been assigned. The primary menu supports two levels: on wide screens a sub-menu opens as
a drop-down below its parent item on hover or keyboard focus, scrolling inside it when it
does not fit the screen, and on small screens it opens as an
accordion inside the slide-in panel. A third level is not rendered. The footer menu shows
a single level.

= Does the theme include widget areas? =

Yes, two. "Sidebar" is shown to the right of the content on the front page, posts, pages
and archives, and below the content on small screens. It is only rendered when it has
widgets, so the layout stays in one column otherwise. "Front page bottom" is shown at
the bottom of the front page; place the "Call to action" pattern there and edit its text
and link.

= How do I hide the sidebar on a page? =

Edit the page, open the Template setting in the Page panel and choose "No Sidebar". The
content keeps a readable line length; use the wide or full width alignment on blocks
that should stretch further.

= What does the front page show? =

The front page template is used whichever option is chosen in Settings > Reading. When a
static page is set as the homepage, its content is shown first, followed by the latest
posts and a link to the posts page. When the latest posts are shown, they are paginated.
A main visual (hero) is not included, as it is expected to be built for each site.

== Changelog ==

= 0.1.0 =
* Initial release.

== Copyright ==

classic-starter WordPress Theme, (C) 2026 Momiji
classic-starter is distributed under the terms of the GNU GPL.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

Images used in screenshot.png, (C) 2026 Momiji
Licensed under GPLv2 or later.
