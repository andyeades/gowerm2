<?php
namespace Elevate\Themeoptions\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Writetranslations extends Command
{
    /**
     * @var \Elevate\Themeoptions\Helper\WriteTranslations
     */
    protected $transHelper;

    public function __construct(
        \Elevate\Themeoptions\Helper\WriteTranslations $transHelper
    ) {
        $this->transHelper = $transHelper;
        parent::__construct();
    }
    protected function configure()
    {
        $this->setName('elevate:output_translations')->setDescription('Output Custom i18n Translations');

        parent::configure();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Generating i18n Translations");
        $generate = $this->transHelper->generateTranslationsCommandLine();

        $output->writeln(print_r($generate));

        if (isset($generate['success'])) {
            $output->writeln("Success");
            foreach ($generate['success_array'] as $area) {
                $output->writeln($area);
            }
        } else {
            $output->writeln("Failed");
        }

    }
}
