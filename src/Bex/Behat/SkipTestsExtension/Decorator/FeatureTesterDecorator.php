<?php

namespace Bex\Behat\SkipTestsExtension\Decorator;

use Behat\Gherkin\Node\FeatureNode;
use Behat\Testwork\Environment\Environment;
use Behat\Testwork\Tester\Result\IntegerTestResult;
use Behat\Testwork\Tester\Result\TestResult;
use Behat\Testwork\Tester\Result\TestResults;
use Behat\Testwork\Tester\Setup\Setup;
use Behat\Testwork\Tester\Setup\Teardown;
use Behat\Testwork\Tester\SpecificationTester;
use Bex\Behat\SkipTestsExtension\ServiceContainer\Config;

class FeatureTesterDecorator implements SpecificationTester
{
    /**
     * @var ScenarioTester
     */
    private $featureTester;
    
    /**
     * @var Config
     */
    private $config;
    
    /**
     * @param SpecificationTester $featureTester
     * @param Config              $config
     */
    public function __construct(SpecificationTester $featureTester, Config $config)
    {
        $this->featureTester = $featureTester;
        $this->config = $config;
    }

    /**
     * Sets up specification for a test.
     *
     * @param Environment $env
     * @param mixed       $spec
     * @param Boolean     $skip
     *
     * @return Setup
     */
    public function setUp(Environment $env, $spec, $skip)
    {
        return $this->featureTester->setUp($env, $spec, $skip);
    }

    /**
     * Tests provided specification.
     *
     * @param Environment $env
     * @param mixed       $spec
     * @param Boolean     $skip
     *
     * @return TestResult
     */
    public function test(Environment $env, $spec, $skip)
    {
        if ($spec instanceof FeatureNode && $this->shouldSkipFeature($spec)) {
            $skip = true;
        }

        return $this->featureTester->test($env, $spec, $skip);
    }

    /**
     * Tears down specification after a test.
     *
     * @param Environment $env
     * @param mixed       $spec
     * @param Boolean     $skip
     * @param TestResult  $result
     *
     * @return Teardown
     */
    public function tearDown(Environment $env, $spec, $skip, TestResult $result)
    {
        return $this->featureTester->tearDown($env, $spec, $skip, $result);
    }

    /**
     * @param FeatureNode $feature
     *
     * @return bool
     */
    private function shouldSkipFeature(FeatureNode $feature)
    {
        $matchingSkipTags = array_intersect($feature->getTags(), $this->config->getSkipTags());
        
        return $this->config->shouldSkipFeatures() && !empty($matchingSkipTags);
    }
}
