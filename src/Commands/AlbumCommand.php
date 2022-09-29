<?php

namespace PhotoAlbum\Commands;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PhotoAlbum\Repositories\AlbumRepository;

class AlbumCommand extends Command
{

    private $albumRepository;

    public function __construct(AlbumRepository $albumRepository)
    {
        parent::__construct();

        $this->albumRepository = $albumRepository;
    }

    public function configure()
    {
        $this->setName('list:album')
            ->setDescription('Display photo IDs and titles from an album.')
            ->addArgument('album-id', InputArgument::REQUIRED, 'Album ID');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $album_id = $input->getArgument('album-id');

        if (!preg_match('/^[0-9]+$/', $album_id)) {
            throw new Exception('Album id [' . $album_id . '] is not valid');
        }

        $photos = $this->albumRepository->getPhotosByAlbumId($album_id);

        if(empty($photos)) {
            $output->writeln('<comment>No photos found for album with id [' . $album_id . ']</comment>');

            return Command::SUCCESS;
        }

        foreach($photos as $photo) {
            $output->writeln('<info>[' . $photo['id'] . ']</info> ' . $photo['title']);
        }
    }
}
