<?php

namespace PhotoAlbum\Commands;

use Exception;
use Phar;
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

        $question = new ChoiceQuestion(
            'Please select the landscape [FI]',
            ['FI', 'RB', 'G1'],
            0
        );
        $question->setErrorMessage('Landscape %s is invalid.');

        $landscape = $helper->ask($input, $output, $question);

        $default = 'ZOFI_OPEN_ITEMS_SRV';
        $question = new Question('Please enter the current SAP service URL segment' . ' [' . $default . ']: ', $default);
        $service_url = $helper->ask($input, $output, $question);

        $default = 'SAP FI - Open Items Service';
        $question = new Question('Please enter a display name for the APIM API' . ' [' . $default . ']: ', $default);
        $display_name = $helper->ask($input, $output, $question);

        //$default = 'SAP-ZOFI-OPEN-ITEMS-SRV';
        $default = 'SAP-' . str_replace('-SRV', '', str_replace('_', '-', $service_url));
        $question = new Question('Please enter a system name for the APIM API' . ' [' . $default . ']: ', $default);
        $name = $helper->ask($input, $output, $question);

        //$default = 'sap_fi_open_items';
        $default = strtolower(str_replace('-', '_', str_replace(['-ZO', '-SRV'], ['-', ''],  $name)));
        $question = new Question('Please enter a module name for the APIM API' . ' [' . $default . ']: ', $default);
        $module_name = $helper->ask($input, $output, $question);

        //$default = 'zofi-open-items';
        $default = strtolower(str_replace('-SRV', '', str_replace('_', '-', $service_url)));
        $question = new Question('Please enter a path for the APIM API' . ' [' . $default . ']: ', $default);
        $path = $helper->ask($input, $output, $question);

        //$output->writeln('You have just selected: ' . $landscape);

        $mtls_certificate_ids = [
            'FI' => 'sap-mtls-order-management',
            'RB' => 'sap-mtls-order-management',
            'G1' => 'sap-mtls-jdf-pol',
        ];

        $subdomains = [
            'FI' => ['dev' => 'cfiweb', 'qual' => 'fi3web', 'cert' => 'qfiweb', 'prod' => 'pfiweb'],
            'RB' => ['dev' => 'crbscs', 'qual' => 'rb3scs', 'cert' => 'qrbscs', 'prod' => 'prbscs'],
            'G1' => ['dev' => 'cg1web', 'qual' => 'g12web', 'cert' => 'qg1web', 'prod' => 'pg1web'],
        ];

        $sap_client_ids = [
            'FI' => ['dev' => '230', 'qual' => '410', 'cert' => '410', 'prod' => '410'],
            'RB' => ['dev' => '230', 'qual' => '410', 'cert' => '410', 'prod' => '410'],
            'G1' => ['dev' => '210', 'qual' => '410', 'cert' => '410', 'prod' => '410'],
        ];

        $mtls_certificate_id = $mtls_certificate_ids[$landscape];

        $top_comment = $display_name . '';
        $global_key = str_replace('_', '', $module_name);
        $source = $path . '-srv';
        $description = 'System API for ' . $display_name . '';

        $path_to_template =  dirname(__FILE__) . '/../Templates/zofi-open-items-srv/';
        $path_to_output =  dirname(__FILE__) . '/../../output/';

        if ($phar = Phar::running()) {
            $path_to_output = str_replace('phar://', '', dirname($phar)) . '/';
        }

        $api_main_file = 'api-main.tf';
        $main_file = 'main.tf';

        $main_template = file_get_contents($path_to_template . $main_file);
        $api_template = file_get_contents($path_to_template . 'zofi-open-items-srv-api/' . $main_file);

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
            'mtls-certificate-id' => $mtls_certificate_id,
            'service_subdomain_dev' => $subdomains[$landscape]['dev'],
            'service_subdomain_qual' => $subdomains[$landscape]['qual'],
            'service_subdomain_cert' => $subdomains[$landscape]['cert'],
            'service_subdomain_prod' => $subdomains[$landscape]['prod'],
            'sap_client_id_dev' => $sap_client_ids[$landscape]['dev'],
            'sap_client_id_qual' => $sap_client_ids[$landscape]['qual'],
            'sap_client_id_cert' => $sap_client_ids[$landscape]['cert'],
            'sap_client_id_prod' => $sap_client_ids[$landscape]['prod'],
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

        $output->writeln('Success!');

        return Command::SUCCESS;

    }
}
