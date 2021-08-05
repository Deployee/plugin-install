<?php

namespace Deployee\Plugins\Install\Commands;

use Deployee\Components\Config\ConfigInterface;
use Deployee\Components\Environment\EnvironmentInterface;
use Deployee\Plugins\Install\Events\RunInstallCommandEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class InstallCommand extends Command
{
    /**
     * @var EnvironmentInterface
     */
    private $env;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @param EnvironmentInterface $env
     */
    public function setEnv(EnvironmentInterface $env)
    {
        $this->env = $env;
    }

    /**
     * @param ConfigInterface $config
     */
    public function setConfig(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param EventDispatcher $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcher $eventDispatcher)
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

        $path = $this->config->get('deploy_definition_path', 'definitions');
        $path = strpos($path, '/') !== 0 && strpos($path, ':') !== 1
            ? $this->env->getWorkDir() . DIRECTORY_SEPARATOR . $path
            : $path;

        if(!is_dir($path)){
            $output->writeln(sprintf('Directory %s does not exist', $path));
            exit(255);
        }

        $this->eventDispatcher->dispatch(new RunInstallCommandEvent(), RunInstallCommandEvent::class);

        $output->writeln('Finished installing');
    }
}