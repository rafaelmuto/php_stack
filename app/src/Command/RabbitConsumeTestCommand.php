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
    name: 'test:rabbit:consume',
    description: 'Add a short description for your command',
)]
class RabbitConsumeTestCommand extends Command
{
    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        $connection = new AMQPStreamConnection('192.168.64.1', 8797, 'user', '123456');
        $channel = $connection->channel();

        $queue_name = 'test';

        $channel->queue_declare($queue_name, false, false, false, false);

        $channel->basic_consume($queue_name, '', false, true, false, false, function (AMQPMessage $msg) {
            dump($msg->getBody());
        });

        while ($channel->is_open()) {
            $channel->wait();
        }

        $io->success('ok!');
        return Command::SUCCESS;
    }
}
