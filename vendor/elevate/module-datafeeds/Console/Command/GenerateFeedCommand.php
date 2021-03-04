<?php

namespace Elevate\DataFeeds\Console\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;



use Elevate\DataFeeds\Helper\FeedGenerator;


class GenerateFeedCommand extends Command
{
    private $generator;

    public function __construct(FeedGenerator $generator)
    {
        $this->generator = $generator;

        parent::__construct();
    }


    protected function configure()
    {
        $this->setName('elevate:feed:generate')->setDescription('Generate feed (for google, bing etc)')
        ->addArgument('name', InputArgument::REQUIRED, 'The name of the feed you want to generate. For a list of feeds use the elevate:feed:list command')
        ->addArgument('storeId', InputArgument::OPTIONAL, 'Store id (optional - defaults to 4)');
        parent::configure();
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if(!empty($input->getArgument('storeId'))) {
            $result = $this->generator->generateFeed($input->getArgument('name'), $input->getArgument('storeId'));
        } else {
            $result = $this->generator->generateFeed($input->getArgument('name'));
        }

        if (isset($result['success']) && $result['success']) {
            echo "Successfully generated feed\n";
            return true;
        } elseif(!empty($result['error_msg'])) {
                echo "Sorry feed failed to generate: " . $result['error_msg'] . "\n";
        }
        return false;
    }
}
