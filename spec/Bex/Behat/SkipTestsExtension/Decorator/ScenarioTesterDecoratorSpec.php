<?php

namespace spec\Bex\Behat\SkipTestsExtension\Decorator;

use Behat\Behat\Tester\ScenarioTester;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\ScenarioInterface;
use Behat\Testwork\Environment\Environment;
use Behat\Testwork\Tester\Result\TestResult;
use Behat\Testwork\Tester\Setup\Setup;
use Behat\Testwork\Tester\Setup\Teardown;
use Bex\Behat\SkipTestsExtension\ServiceContainer\Config;
use Bex\Behat\SkipTestsExtension\Decorator\ScenarioTesterDecorator;
use PhpSpec\ObjectBehavior;

class ScenarioTesterDecoratorSpec extends ObjectBehavior
{
    function let(ScenarioTester $scenarioTester, Config $config)
    {
        $this->beConstructedWith($scenarioTester, $config);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Bex\Behat\SkipTestsExtension\Decorator\ScenarioTesterDecorator');
    }

    function it_should_call_the_decorated_method_during_setup(
        ScenarioTester $scenarioTester,
        Environment $env,
        FeatureNode $feature,
        ScenarioInterface $scenario,
        Setup $setup
    ) {
        $scenarioTester->setUp($env, $feature, $scenario, false)->shouldBeCalled()->willReturn($setup);

        $this->setUp($env, $feature, $scenario, false)->shouldReturn($setup);
    }

    function it_should_call_the_decorated_method_during_tear_down(
        ScenarioTester $scenarioTester,
        Environment $env,
        FeatureNode $feature,
        ScenarioInterface $scenario,
        TestResult $result,
        Teardown $tearDown
    ) {
        $scenarioTester->tearDown($env, $feature, $scenario, false, $result)->shouldBeCalled()->willReturn($tearDown);

        $this->tearDown($env, $feature, $scenario, false, $result)->shouldReturn($tearDown);
    }

    function it_should_call_the_decorated_method_during_test_without_modifying_the_input_if_scenario_is_not_tagged_for_skip(
        ScenarioTester $scenarioTester,
        Config $config,
        Environment $env,
        FeatureNode $feature,
        ScenarioInterface $scenario,
        TestResult $testResult
    ) {
        $config->getScenarioSkipTags()->willReturn(['pending', 'skip']);
        $scenario->getTags()->willReturn(['javascript']);

        $scenarioTester->test($env, $feature, $scenario, false)->shouldBeCalled()->willReturn($testResult);

        $this->test($env, $feature, $scenario, false)->shouldReturn($testResult);
    }

    function it_should_call_the_decorated_method_during_test_with_skip_flag_if_the_scenario_tagged_for_skip(
        ScenarioTester $scenarioTester,
        Config $config,
        Environment $env,
        FeatureNode $feature,
        ScenarioInterface $scenario,
        TestResult $testResult
    ) {
        $config->getScenarioSkipTags()->willReturn(['pending', 'skip']);
        $scenario->getTags()->willReturn(['javascript', 'pending']);

        $scenarioTester->test($env, $feature, $scenario, true)->shouldBeCalled()->willReturn($testResult);

        $this->test($env, $feature, $scenario, false)->shouldReturn($testResult);
    }
}
