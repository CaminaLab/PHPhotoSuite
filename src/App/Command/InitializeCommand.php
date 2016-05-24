<?php

namespace PHPhotoSuit\App\Command;

use PHPhotoSuit\PhotoSuite\Infrastructure\Persistence\SqliteConfig;
use PHPhotoSuit\PhotoSuite\Infrastructure\Persistence\SqlitePhotoRepository;
use PHPhotoSuit\PhotoSuite\Infrastructure\Persistence\SqlitePhotoThumbRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InitializeCommand extends Command
{
    protected function configure()
    {
        $this->setName('photosuit:init')
            ->setDescription('Initialize repository persistence.')
            ->addArgument(
                'driver',
                InputArgument::REQUIRED,
                'Which is the repository driver?(Available options: sqlite)'
            )
            ->addOption(
                'dbpath',
                null,
                InputOption::VALUE_REQUIRED,
                'SQLITE OPTION path where database is stored'
            );
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new SqliteConfig($input->getOption('dbpath'));
        $sqlitePhotoRepository = new SqlitePhotoRepository($config);
        $sqlitePhotoThumbRepository = new SqlitePhotoThumbRepository($config);
        
        $sqlitePhotoRepository->initialize();
        $sqlitePhotoThumbRepository->initialize();

        $output->writeln('Sqlite database tables successfully created!');
    }
}
