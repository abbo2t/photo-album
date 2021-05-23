<?php

namespace PhotoAlbum\Repositories;

use GuzzleHttp\Client;

interface AlbumRepository
{
    public function getPhotosByAlbumId($id);
}