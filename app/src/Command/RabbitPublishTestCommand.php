<?php

namespace App\Command;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'test:rabbit:publish',
    description: 'Add a short description for your command',
)]
class RabbitPublishTestCommand extends Command
{

    protected function configure(): void
    {
        $this->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');
        if(is_null($arg1)) {
            $arg1 = 'no message set...';
        }

        $queue_name = 'test';

        $connection = new AMQPStreamConnection('192.168.64.1', 8797, 'user', '123456');
        $channel = $connection->channel();

        $channel->queue_declare($queue_name, false, false, false, false);

        $io->writeln('sengin ' . $arg1);

        $msg = new AMQPMessage($arg1);
        $channel->basic_publish($msg, '', $queue_name);

        $channel->close();
        $connection->close();

        $io->success('ok!');
        return Command::SUCCESS;
    }
}
