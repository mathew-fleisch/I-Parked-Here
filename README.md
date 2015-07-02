# I-Parked-Here
Personal parking assistant allows you to quickly save your car's coordinates on a Google Maps window. A center point, boundry boxes and a home icon are preconfigured, making standard usage, quick and simple. Street cleaning in the city frequently happens weekly, and easy-to-use day/time sliders makes it quick to update. Once the street cleaning information is set, simply tap on the map where parked, and click save. When you come back to the page, the information you saved will automatically be loaded on the screen.

tl;dr
Saves a "dropped pin" on a google maps api as a text file and uses coordinates to place an icon, onLoad.

To Do:
  - Save json file instead of text file and update parser.
  - Make output responsive
  - Set up config file (boundries, center point, zoom level, house)
     - Create wizard when no config file is found to set the values in a UI
  - Verify relative and absolute paths
  - Make more clear error messages on set_position.php
