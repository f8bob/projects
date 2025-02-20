# Page Speed Optimization: CLS Reduction Scripts

This repository provides scripts to assist in optimizing Page Speed by addressing Cumulative Layout Shift (CLS) issues. The scripts automate image downloading, compression, and the addition of width and height attributes to improve rendering stability.

## 1. Image Download

Usage:

Open `images_saver.php`.

Configure the following parameters:

`$url` – Define the URL of the target website.

`$local_dir` – Specify the directory for storing downloaded images (e.g., saved/).

`$search_for` – Set the path for filtering images from the website (e.g., public/1.0/uploads/).

`$skip_folder` – (Optional) Define a folder to be excluded from processing.

Execute the script in the terminal:

`php images_saver.php`

The images will be saved in the specified folder (saved/).

## 2. Image Compression

Usage:

Open `images_compressor.php`.

Adjust the compression quality as needed:

`$fileCompressor = new FileCompressor(85);`

Execute the script to compress all images:

`php images_compressor.php`

## 3. Adding Width and Height Attributes to Images

Usage:

Create an `index.html` file containing the page's HTML code with images.

Run the `html_update.php` script to automatically insert width and height attributes based on `images_size.csv` generated in Step 1:

# License
This project is released under the MIT License.
