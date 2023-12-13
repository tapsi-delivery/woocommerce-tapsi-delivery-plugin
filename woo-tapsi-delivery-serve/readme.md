> This directory is made for testing js scripts and css styles locally and should not be packed for production. 

# Steps:
1. copy any of the files that you are going to change from `https://static.tapsi.cab/pack/wp-plugin/map/*` into current directory with the exact same name
2. replace `https://static.tapsi.cab/pack/wp-plugin/map/` with `http://localhost/${YOUR-WP-PROJECT-NAME}/wp-content/plugins/woo-tapsi-delivery-serve/` in project code
3. manipulate the files in current directory and test as much as you want~
4. ask infra team to upload the changed files to https://static.tapsi.cab/pack/wp-plugin/map/
5. undo step 2

#List of currently served files:

| name                    | may change | purpose                                                                                                           |
|-------------------------|------------|-------------------------------------------------------------------------------------------------------------------|
| map-admin.js (legacy)   | Y          | script: handles map js codes on admin panel of the plugin                                                         |
| map-center-marker.svg   | **N**      | icon: the center pin of the map (displayed on the map)                                                            |
| map-admin.css (legacy)  | M          | styles: admin panel's map css codes (handles centering map center marker, handles map size, border, positioning, etc) |
| dot.svg                 | **N**      | icon: the black dot that is displayed on map preview image in the checkout page                                   |
| warning.svg             | **N**      | icon: the warning icon used in checkout page                                                                      |
| map-public.css (legacy) | M          | styles: handles same responsibility as map-admin.js in the checkout page + managing map modal styles              |
| mapsi-style.json        | **N**      | style: tapsi map canvas style configurations                                                                      |

## Looking for `map-public.js` ?

- The scripts for handling map on the checkout page is added to `public/js/woo-tapsi-delivery-public.js` by jQuery
  - why?
    - we had some problems with the sequence of loading and running the scripts. A pure js file, such as the one used for admin panel (`map-admin.js`) was executed only once in the checkout page and the changes applied by the script were overwritten by some other scripts. Our knowledge up to today has not been enough to resolve the issue.
