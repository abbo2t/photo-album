<?php

namespace PhotoAlbum\Repositories;

use GuzzleHttp\Client;
use PhotoAlbum\Repositories\AlbumRepository;

class AlbumApiRepository implements AlbumRepository
{
    const ALBUM_API_URL = 'https://jsonplaceholder.typicode.com/photos';

    public function getPhotosByAlbumId($id)
    {
        $client = new Client;
        $response = $client->get(self::ALBUM_API_URL . '?albumId=' . $id);
        $photos = json_decode($response->getBody(), true);

        return $photos;
    }
}
