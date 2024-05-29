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

```
fetch('http://localhost:10005/wp-json/searchconsole/v1/json_data?site=MYSITE', {
	method: 'POST', 
	headers: { 
		'Authorization': 'Basic ' + btoa('admin:XXXX XXXX XXXX XXXX XXXX')
	},
	body: JSON.stringify({
		startDate: '2024-05-01',
		endDate: '2024-05-21',
		dimensions: ['QUERY'],
		type: 'web'
	})
})
.then(response => response.json())
.then(json => console.log(json));
```
