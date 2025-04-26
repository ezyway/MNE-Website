-- Overview

This project contains many images under the products section.
Since manually setting resolution and format for each image isn't practical, image sizes can get very large.

To optimize them, use the convert_imgs.py script — a tool that:
    Resizes images to a custom resolution
    Compresses images by adjusting quality
    Preserves the original folder structure
    Outputs images in .webp format for better web performance

Important: Ensure the Height is greater than the Width, as the website is designed to display images vertically.

-- How to Use
1. Prepare the Folder Structure
    Create a folder named: input_images
    Place all the images you want to convert inside input_images.

2. Run the Conversion Script
    Execute the script: python convert_imgs.py

3. Enter Required Parameters When Prompted
    You will be asked for: Target Width (pixels), Target Height (pixels) and WebP Quality (0–100) (100 = no compression; lower values = smaller file sizes but lower quality)

4. View the Results
    Converted images will be saved inside: output_images
    Original folder structure inside input_images will be preserved.
    Output images will be in .webp format.

-- Notes
A full log of any errors during conversion will be saved in: conversion_errors.log
Lower quality percentage reduces file size at the cost of slight quality loss.
Multiprocessing is used for faster batch processing — all CPU cores are utilized.
Input images can be of any type (JPEG, PNG, etc.), and they will be converted automatically.