# Technical Showcase: Photo Album

## Description

This program fetches IDs and titles of photos in photo albums from the JSONPlaceholder API.

## Prerequisites 

- A Bash-compatible shell
- PHP 7+ on the command line
- Optional: Unit tests require the Composer dependency manager (<https://getcomposer.org/>)

## Installation

<code>cd /path/to/this/repo</code>

<code>chmod 755 photo-album build/photoAlbum.phar</code>

### Example usage

List photos in album with ID 2:

<code>./photo-album 2</code>

## Running tests

<code>composer install</code>

<code>vendor/bin/phpunit tests</code>

## Notes

- This has been tested in MacOS 10.15.7 and Ubuntu 18.04.4 LTS, but any OS capabile of running Bash scripts should work.
- For simplicity, I'm using a hard-coded API URL instead of setting it up in an environment file.
- Non-integer IDs are considered invalid.
