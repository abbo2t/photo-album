<?php

namespace PhotoAlbum\Repositories;

use PhotoAlbum\Repositories\AlbumRepository;

class AlbumApiRepository implements AlbumRepository
{
    const ALBUM_API_URL = 'https://jsonplaceholder.typicode.com/photos';

    public function getPhotosByAlbumId($id)
    {
        $data = $this->makeApiCall(self::ALBUM_API_URL . '?albumId=' . $id);
        $photos = json_decode($data, true);

        return $photos;
    }

    protected function makeApiCall($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
}
