<?php

namespace Bex\Behat\SkipTestsExtension\ServiceContainer;

class Config
{
    const CONFIG_PARAM_SKIP_SCENARIOS = 'skip_scenarios';
    const CONFIG_PARAM_SKIP_TAGS = 'skip_tags';

    /**
     * @var bool
     */
    private $skipScenarios;

    /**
     * @var string[]
     */
    private $skipTags = [];

    /**
     * @param array $config
     */
    public function __construct($config)
    {
        $this->skipScenarios = $config[self::CONFIG_PARAM_SKIP_SCENARIOS];
        $this->skipTags = $config[self::CONFIG_PARAM_SKIP_TAGS];
    }

    /**
     * @return bool
     */
    public function shouldSkipScenarios()
    {
        return $this->skipScenarios;
    }

    /**
     * @return string[]
     */
    public function getSkipTags()
    {
        return $this->skipTags;
    }
}
