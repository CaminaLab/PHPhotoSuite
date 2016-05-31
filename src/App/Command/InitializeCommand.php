<?php

namespace PHPhotoSuit\App\Command;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use PHPhotoSuit\App\Config\Repository;
use PHPhotoSuit\PhotoSuite\Infrastructure\Persistence\MysqlConfig;
use PHPhotoSuit\PhotoSuite\Infrastructure\Persistence\MysqlPhotoRepository;
use PHPhotoSuit\PhotoSuite\Infrastructure\Persistence\MysqlPhotoThumbRepository;
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
        $this->setName('init')
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
            )
            ->addOption(
                'host',
                null,
                InputOption::VALUE_REQUIRED,
                'MYSQL OPTION database host'
            )
            ->addOption(
                'dbname',
                null,
                InputOption::VALUE_REQUIRED,
                'MYSQL OPTION database name'
            )
            ->addOption(
                'user',
                null,
                InputOption::VALUE_REQUIRED,
                'MYSQL OPTION database user'
            )
            ->addOption(
                'password',
                null,
                InputOption::VALUE_REQUIRED,
                'MYSQL OPTION database user'
            )
            ->addOption(
                'password',
                null,
                InputOption::VALUE_REQUIRED,
                'MYSQL OPTION database port'
            );
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        switch ($input->getArgument('driver')) {
            case Repository::REPOSITORY_SQLITE:
                $config = new SqliteConfig($input->getOption('dbpath'));
                $sqlitePhotoRepository = new SqlitePhotoRepository($config);
                $sqlitePhotoThumbRepository = new SqlitePhotoThumbRepository($config);

                $sqlitePhotoRepository->initialize();
                $sqlitePhotoThumbRepository->initialize();

                $output->writeln('Sqlite database tables successfully created!');
                break;
            case Repository::REPOSITORY_MYSQL:
                $config = new MysqlConfig(
                    $input->getOption('host'),
                    $input->getOption('dbname'),
                    $input->getOption('user'),
                    $input->getOption('password'),
                    empty($input->getOption('port')) ? 3306 : $input->getOption('port')
                );
                $mysqlPhotoRepository = new MysqlPhotoRepository($config);
                $mysqlPhotoThumbRepository = new MysqlPhotoThumbRepository($config);

                $mysqlPhotoRepository->initialize();
                $mysqlPhotoThumbRepository->initialize();

                $output->writeln('Mysql database tables successfully created!');
                break;
            default:
                throw new InvalidArgumentException(sprintf('Invalid Driver %s', $input->getArgument('driver')));
                break;
        }

    }
}
