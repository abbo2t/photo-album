<?php

namespace PhotoAlbum\Commands;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
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

        $helper = $this->getHelper('question');
        
        $default = 'SAP FI - Open Items Service';
        $question = new Question('Please enter the display name' . ' [' . $default . ']', $default);
        $display_name = $helper->ask($input, $output, $question);

        $default = 'sap_fi_open_items';
        $question = new Question('Please enter the module name' . ' [' . $default . ']', $default);
        $module_name = $helper->ask($input, $output, $question);

        $default = 'SAP-ZOFI-OPEN-ITEMS';
        $question = new Question('Please enter the name' . ' [' . $default . ']', $default);
        $name = $helper->ask($input, $output, $question);

        $default = 'zofi-open-items';
        $question = new Question('Please enter the path' . ' [' . $default . ']', $default);
        $path = $helper->ask($input, $output, $question);

        $default = 'ZOFI_OPEN_ITEMS_SRV';
        $question = new Question('Please enter the service URL' . ' [' . $default . ']', $default);
        $service_url = $helper->ask($input, $output, $question);

        //$default = 'sap-mtls-order-management';
        //$question = new Question('Please enter the MTLS Certificate ID' . ' [' . $default . ']', $default);
        //$mtls_certificate_id = $helper->ask($input, $output, $question);

        $question = new ChoiceQuestion(
            'Please select the landscape [FI]',
            ['FI', 'RB', 'G1'],
            0
        );
        $question->setErrorMessage('Landscape %s is invalid.');

        $landscape = $helper->ask($input, $output, $question);
        $output->writeln('You have just selected: ' . $landscape);

        $mtls_certificate_ids = [
            'FI' => 'sap-mtls-order-management',
        ];

        $mtls_certificate_id = $mtls_certificate_ids[$landscape];

        //$display_name = 'SAP FI - Open Items Service';
        //$module_name = 'sap_fi_open_items';
        //$name = 'SAP-ZOFI-OPEN-ITEMS';
        //$path ='zofi-open-items';
        //$service_url = 'ZOFI_OPEN_ITEMS_SRV';

        $top_comment = $display_name . ' API';
        $global_key = str_replace('_', '', $module_name);
        $source = $path . '-srv-api';
        $description = 'System API for ' . $display_name . ' Service';

        //$mtls_certificate_id = 'sap-mtls-order-management';

        //$album_id = $input->getArgument('display-name');

        $path_to_template =  dirname(__FILE__) . '/../Templates/zofi-open-items-srv/';
        $path_to_output =  dirname(__FILE__) . '/../../output/';
        $api_main_file = 'api-main.tf';
        $main_file = 'main.tf';

        $main_template = file_get_contents($path_to_template . $main_file);
        $api_template = file_get_contents($path_to_template . 'zofi-open-items-srv-api/' . $main_file);

        //$output->writeln($path_to_template . $main_file);
        //$output->writeln($main_template);

        $populated = $main_template;
        $api_populated = $api_template;

        foreach([
            'display-name' => $display_name,
            'module-name' => $module_name,
            'name' => $name,
            'path' => $path,
            'service-url' => $service_url,
            'top_comment' => $top_comment,
            'global-key' => $global_key,
            'source' => $source,
            'description' => $description,
            'mtls-certificate-id' => $mtls_certificate_id
        ] as $var_name => $var_value) {
            $populated = str_replace('{{' .$var_name . '}}', $var_value, $populated);
            $api_populated = str_replace('{{' .$var_name . '}}', $var_value, $api_populated);
        }

        $myfile = fopen($path_to_output . $api_main_file, "w") or die("Unable to open file!");
        
        fwrite($myfile, $api_populated);
        fclose($myfile);

        $myfile = fopen($path_to_output . $main_file, "w") or die("Unable to open file!");
        fwrite($myfile, $populated);
        fclose($myfile);

        return Command::SUCCESS;

    }
}
