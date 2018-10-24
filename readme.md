# PartnerComm Quick Find Plugin

## Notes
This is the PartnerComm Quick Find engine, and should always be used for new Quick Find projects. See further documentation here: https://github.com/PartnerComm/pcomm-web-documentation/blob/master/quickfind.md

This version will restrict some content on keyword pages and category pages according to user preferences set on a site preferences page by individual users that are saved to a cookie. 

That entire system needs to be rethought, but the upshot is that in order to convert MSK to use this version of the plugin and not break existing Quick Finds that rely on site preferences, there is a new check for `if ( !defined('PCQF_USER_PREFERENCES_OFF') )` so sites that do not rely on site preferences can define that in a config file to ignore site preference checks. This is actually backwards from how it should probably be done (site preferences and the associated pre_get_posts changes should be opt-in rather than opt-out), but this was a quick solution during OE that would not break existing sites. 

The plugin also supports a config option for adding post types to the main QF query that may be client specific. This can be defined in a config file, as `define( 'PCQF_CLIENT_POST_TYPES', ['life-event-type'] );`. This is only a start to how we might handle this, although it's probably better handled by a plugin setting. In fact, the change in 1.0.3 really makes this moot, and it needs to be rethought as part of a larger reimagining of this plugin. Keeping it in place for now, even though it was added specifically as a need for MSK two days ago, perhaps as a possible proof of concept for how we could think about the problem moving forward.

~~This was originally copied over from the Roche project. There were issues with the site redirecting to a /site-preferences page which didn't exist for the MSK project it was copied into. So... it's currently a mix between Roche and MSK. At some point need a way to pull out the vendor specific differences, or make those differences configurable.~~

# Release Notes
- 1.0.8
    - version bump
- 1.0.7
    - Quickfind manager should display titles when wrapped in comments 
- 1.0.6 (2018-10-22)
    - Bug Fix: resolve invalid foreach loops
    - Add docs-type CPT
- 1.0.5 (2018-10-22)
    - Resolve undefined index bug
    - Remove depreciated function screen_icon
- 1.0.4 (2018-09-30)
	- Add life-events-type to admin queries in QF manager
	
- 1.0.3 (2018-09-27)
	- Add Life Events post type

- 1.0.2 (2018-09-25)
	- Add user preferences off config option
	- Add post types array config option

- 0.9.0 (2018-07-18)
    - Adding fixed version numbers
    - See notes section above for next refactoring steps
