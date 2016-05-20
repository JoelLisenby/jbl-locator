# JBL Locator
A simple WordPress Google Maps locator plugin

![screenshot](https://i.imgur.com/wZtghpI.png)

## Use
1. Install plugin.
2. Go to ```Locator Map``` settings and add your Google Maps API browser key.
4. Add locations to the configured ```Locations``` post type.
3. Add the following shortcode to your page or post ```[jbllocator]```

## Locations
JBL Locator adds a custom post type to WordPress to provide easy administration of locations.

### Import Locations with CSV
Use ```Really Simple CSV Importer``` to import your locations from a CSV spreadsheet.

#### Example CSV
```
post_type,post_status,post_title,post_author,email,website,address,lat,lng
location,publish,John Doe,admin,john.doe@gmail.com,google.com,"San Diego, California, USA",32.8248175,-117.375346
```

## Customization

### Customizable Custom Post Type
JBL Locator provides a simple options page in wp-admin to customize the name, singular name and slug of the locator's custom post type.

### CSS Style
```
.jbllocator {} /* container div */
.jbllocator #jbllocatormap {} /* map div */
.jbllocator #jbllocatorsearch {} /* input search field */
```
