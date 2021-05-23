# Technical Showcase: Photo Album

## Description

This program fetches the contents of photo albums from the  JSONPlaceholder API.

## Installation

<code>cd /path/to/this/repo</code>

<code>composer install</code>

### Example usage:

<code># list photos in album 2:</code>

<code>php photoAlbum.php list:album 2<code>

## Running tests

<code>vendor/bin/phpunit tests</code>

## Notes

- Requires PHP 7+
- This project uses hard-coded paths instead of setting them up in an environment file, for simplicity.