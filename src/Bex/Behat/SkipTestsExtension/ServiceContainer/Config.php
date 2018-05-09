<?php

namespace Bex\Behat\SkipTestsExtension\ServiceContainer;

use Bex\Behat\StepTimeLoggerExtension\Service\OutputPrinter\OutputPrinterInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Config
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var string[]
     */
    private $scenarioSkipTags = [];

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function __construct(ContainerBuilder $container, $config)
    {
        $this->container = $container;
        $this->scenarioSkipTags = ['pending', 'skip'];
    }

    /**
     * @return string[]
     */
    public function getScenarioSkipTags()
    {
        return $this->scenarioSkipTags;
    }
}
