<?php
namespace Elevate\Themeoptions\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Elevate\Themeoptions\Helper\GenerateScss;

class Generatecss extends Command
{
    /**
     * @var \Elevate\Themeoptions\Helper\GenerateScss
     */
    protected $scssHelper;

    public function __construct(
        \Elevate\Themeoptions\Helper\GenerateScss $scssHelper
    ) {
        $this->scssHelper = $scssHelper;
        parent::__construct();
    }
    protected function configure()
    {
        $this->setName('elevate:generate_css')->setDescription('Generate CSS filesfrom Scss');

        parent::configure();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Generating Css");
        $generate = $this->scssHelper->generateScssCommandLine();

        if (isset($generate['success'])) {
            $output->writeln("Success");
        } else {
            $output->writeln("Failed");
        }

    }
}
