--- Intro ---

This project has a lot of images in the products section.
Every image cannot be manually set to a certain resolution nor be of a certain type, thus making the image sizes potentially large.
To overcome this issue, convert_imgs.py can be used.
You have to set a custom resolution and percentage of image quality compared to original image.
Keep in mind that the resolution should be such that the Height > Width, because the website displays it like that.
The folder structure will be preserved here.


--- Usage ---

Create a folder "input_images" in this directory
Run convert_imgs.py
Input Height, Width, and percentage of compression (100 - No compression)
Check "output_images" folder