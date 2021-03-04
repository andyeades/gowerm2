<?php

namespace Elevate\DataFeeds\Console\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Elevate\DataFeeds\Helper\FeedGenerator;


class ListFeedsCommand extends Command
{
    private $generator;


    public function __construct(FeedGenerator $generator)
    {
        $this->generator = $generator;

        parent::__construct();
    }


    protected function configure()
    {
        $this->setName('elevate:feed:list')->setDescription('List names of existing feeds');
        parent::configure();
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $feeds = $this->generator->getEnabledFeeds();

        foreach($feeds as $curFeed) {
            echo "$curFeed \n";
        }

        return true;

    }

}
