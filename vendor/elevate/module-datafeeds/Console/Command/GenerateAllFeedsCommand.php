<?php

namespace Elevate\DataFeeds\Console\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;


use Elevate\DataFeeds\Helper\FeedGenerator;

class GenerateAllFeedsCommand extends Command
{

    private $generator;


    public function __construct(FeedGenerator $generator)
    {
        $this->generator = $generator;

        parent::__construct();
    }


    protected function configure()
    {
        $this->setName('elevate:feed:generate-all')->setDescription('Generate all enabled fields')
            ->addArgument('storeId', InputArgument::OPTIONAL, 'Store id (optional - defaults to 4)');
        parent::configure();
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $feeds = $this->generator->getEnabledFeeds();
        $storeId = $input->getArgument('storeId');

        foreach($feeds as $curFeed) {
            if(!empty($storeId)) {
                $this->generator->generateFeed($curFeed, $storeId);
            } else {
                $this->generator->generateFeed($curFeed);
            }
            echo "generated feed $curFeed \n";
        }

        return true;

    }

}
