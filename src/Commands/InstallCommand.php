<?php

namespace Deployee\Plugins\Install\Commands;

use Deployee\Plugins\Install\Events\RunInstallCommandEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class InstallCommand extends Command
{
    /**
     * @var string
     */
    private $deployDefinitionPath;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param string $deployDefinitionPath
     */
    public function setDeployDefinitionPath(string $deployDefinitionPath)
    {
        $this->deployDefinitionPath = $deployDefinitionPath;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @inheritdoc
     */
    public function configure()
    {
        parent::configure();
        $this->setName('install');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Running install');

        if(!is_dir($this->deployDefinitionPath)){
            $output->writeln(sprintf('Directory %s does not exist', $this->deployDefinitionPath));
            exit(255);
        }

        $this->eventDispatcher->dispatch(RunInstallCommandEvent::class, new RunInstallCommandEvent());

        $output->writeln('Finished installing');
    }
}