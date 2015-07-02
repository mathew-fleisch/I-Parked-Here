# I Parked Here
Personal parking assistant allows you to quickly save your car's coordinates in a Google Maps window. A center point, boundry boxes and a home icon are preconfigured, making standard usage, quick and simple. Street cleaning in the city frequently happens weekly, and easy-to-use day/time sliders makes it quick to update. Once the street cleaning information is set, simply tap on the map where parked, and click save. When returning to the page, the information previously saved will automatically be loaded.

## tl;dr

Saves a "dropped pin" on a google maps api as a json file and uses coordinates to place an icon, onLoad.

To Do:
  - Make output responsive (started)
  - Set up config file (boundries, center point, zoom level, house)
     - Create wizard when no config file is found to set the values in a UI
  - Verify relative and absolute paths
  - Update Google Maps API
  - Load json file with js instead of php
  - Create multiple users on the same instance
  - <strike>Make more clear error messages on set-position.php</strike>
  - <strike>Save json file instead of text file and update parser.</strike>
