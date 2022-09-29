<?php

namespace PhotoAlbum\Commands;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PhotoAlbum\Repositories\AlbumRepository;

class ApimCommand extends Command
{

    public function __construct()
    {
        parent::__construct();
    }

    public function configure()
    {
        $this->setName('generate:apim')
            ->setDescription('Generate APIM config files');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $display_name = 'SAP FI - Open Items Service';
        $module_name = 'sap_fi_open_items';
        $name = 'SAP-ZOFI-OPEN-ITEMS';
        $path ='zofi-open-items';
        $service_url = 'ZOFI_OPEN_ITEMS_SRV';

        $top_comment = $display_name . ' API';
        $global_key = str_replace('_', '', $module_name);
        $source = $path . '-srv-api';
        $description = 'System API for ' . $display_name . ' Service';

        $mtls_certificate_id = 'sap-mtls-order-management';

        //$album_id = $input->getArgument('display-name');

        $path_to_output = realpath( dirname(__FILE__) . '../../output/');
        $api_main_file = 'api-main.tf';
        $main_file = 'main.tf';

        $myfile = fopen($path_to_output . $api_main_file, "w") or die("Unable to open file!");
        $txt = "John Doe\n";
        fwrite($myfile, $txt);
        $txt = "Jane Doe\n";
        fwrite($myfile, $txt);
        fclose($myfile);

        $myfile = fopen($path_to_output . $main_file, "w") or die("Unable to open file!");
        $txt = "John Doe\n";
        fwrite($myfile, $txt);
        $txt = "Jane Doe\n";
        fwrite($myfile, $txt);
        fclose($myfile);

        return Command::SUCCESS;

    }
}
