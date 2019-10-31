<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DocsGenerateCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this
            ->setName('docs:generate')
            ->setDescription('Generates OpenAPI docs');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $swagger = 'vendor/bin/openapi';
        $source = 'src/Controller';
        $target = 'public/docs/openapi.json';

        $process = new Process([PHP_BINARY, $swagger, $source, '--output', $target]);
        $process->run(static function ($type, $buffer) use ($output) {
            $output->write($buffer);
        });

        $output->writeln('<info>Done!</info>');
    }
}
