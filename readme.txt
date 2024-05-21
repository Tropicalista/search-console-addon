=== Search Console Addon ===
Contributors:      Tropicalista
Tags:              search-console
Tested up to:      6.5
Stable tag:        0.1.0
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

An addon to Extract Search Console data as Json

== Description ==

Use this plugin to add a REST endpoint to extract data as JSON.

Simply make a post request to /wp-json/searchconsole/v1/json_data and passing this data:

- site: (string) the site
- startDate: (string) the start date formatted as YYY-MM-DD
- endDate: (string) the end date formatted as YYY-MM-DD

You must also send an authorization header with username:password.

Please create an application password to access this from outside.
