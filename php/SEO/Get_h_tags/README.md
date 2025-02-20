
# Page Analysis Script

This script analyzes target web pages for heading tags (H1-H6) and saves the extracted information into a CSV file.

## 1. Functionality
- Iterates over a list of websites.
- Checks if a specific page exists.
- Scrapes H1-H6 tags from specified pages.
- Saves extracted data into `get_h_tags.csv`.

## 2. Usage
- Modify `$sites` to include target websites.
- Modify `$pages` to specify which pages to analyze.
- Run the script using:
  ```
  php get_h_tags.php
  ```
- Extracted data will be stored in `get_h_tags.csv`.

## License
This project is released under the MIT License.
