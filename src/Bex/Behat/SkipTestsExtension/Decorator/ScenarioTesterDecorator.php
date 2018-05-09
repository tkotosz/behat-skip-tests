<?php

namespace Bex\Behat\SkipTestsExtension\Decorator;

use Behat\Behat\Tester\ScenarioTester;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\ScenarioInterface as Scenario;
use Behat\Testwork\Environment\Environment;
use Behat\Testwork\Tester\Result\TestResult;
use Behat\Testwork\Tester\Setup\Setup;
use Behat\Testwork\Tester\Setup\Teardown;
use Bex\Behat\SkipTestsExtension\ServiceContainer\Config;

class ScenarioTesterDecorator implements ScenarioTester
{
    /**
     * @var ScenarioTester
     */
    private $scenarioTester;
    
    /**
     * @var Config
     */
    private $config;
    
    /**
     * @param ScenarioTester $scenarioTester
     * @param Config         $config
     */
    public function __construct(ScenarioTester $scenarioTester, Config $config)
    {
        $this->scenarioTester = $scenarioTester;
        $this->config = $config;
    }

    /**
     * Sets up example for a test.
     *
     * @param Environment $env
     * @param FeatureNode $feature
     * @param Scenario    $scenario
     * @param Boolean     $skip
     *
     * @return Setup
     */
    public function setUp(Environment $env, FeatureNode $feature, Scenario $scenario, $skip)
    {
        return $this->scenarioTester->setUp($env, $feature, $scenario, $skip);
    }

    /**
     * Tests example.
     *
     * @param Environment $env
     * @param FeatureNode $feature
     * @param Scenario    $scenario
     * @param Boolean     $skip
     *
     * @return TestResult
     */
    public function test(Environment $env, FeatureNode $feature, Scenario $scenario, $skip)
    {
        if (!empty(array_intersect($scenario->getTags(), $this->config->getScenarioSkipTags()))) {
            $skip = true;
        }
        
        return $this->scenarioTester->test($env, $feature, $scenario, $skip);
    }

    /**
     * Tears down example after a test.
     *
     * @param Environment $env
     * @param FeatureNode $feature
     * @param Scenario    $scenario
     * @param Boolean     $skip
     * @param TestResult  $result
     *
     * @return Teardown
     */
    public function tearDown(Environment $env, FeatureNode $feature, Scenario $scenario, $skip, TestResult $result)
    {
        return $this->scenarioTester->tearDown($env, $feature, $scenario, $skip, $result);
    }
}
