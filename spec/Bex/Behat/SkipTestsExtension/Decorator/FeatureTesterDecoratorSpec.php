<?php

namespace spec\Bex\Behat\SkipTestsExtension\Decorator;

use Behat\Gherkin\Node\FeatureNode;
use Behat\Testwork\Environment\Environment;
use Behat\Testwork\Tester\Result\TestResult;
use Behat\Testwork\Tester\Setup\Setup;
use Behat\Testwork\Tester\Setup\Teardown;
use Behat\Testwork\Tester\SpecificationTester;
use Bex\Behat\SkipTestsExtension\Decorator\ScenarioTesterDecorator;
use Bex\Behat\SkipTestsExtension\ServiceContainer\Config;
use PhpSpec\ObjectBehavior;

class FeatureTesterDecoratorSpec extends ObjectBehavior
{
    function let(SpecificationTester $featureTester, Config $config)
    {
        $this->beConstructedWith($featureTester, $config);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Bex\Behat\SkipTestsExtension\Decorator\FeatureTesterDecorator');
    }

    function it_should_call_the_decorated_method_during_setup(
        SpecificationTester $featureTester,
        Environment $env,
        FeatureNode $feature,
        Setup $setup
    ) {
        $featureTester->setUp($env, $feature, false)->shouldBeCalled()->willReturn($setup);

        $this->setUp($env, $feature, false)->shouldReturn($setup);
    }

    function it_should_call_the_decorated_method_during_tear_down(
        SpecificationTester $featureTester,
        Environment $env,
        FeatureNode $feature,
        TestResult $result,
        Teardown $tearDown
    ) {
        $featureTester->tearDown($env, $feature, false, $result)->shouldBeCalled()->willReturn($tearDown);

        $this->tearDown($env, $feature, false, $result)->shouldReturn($tearDown);
    }

    function it_should_call_the_decorated_method_during_test_without_modifying_the_input_if_feature_is_not_tagged_for_skip(
        SpecificationTester $featureTester,
        Config $config,
        Environment $env,
        FeatureNode $feature,
        TestResult $testResult
    ) {
        $config->shouldSkipFeatures()->willReturn(true);
        $config->getSkipTags()->willReturn(['pending', 'skip']);
        $feature->getTags()->willReturn(['javascript']);

        $featureTester->test($env, $feature, false)->shouldBeCalled()->willReturn($testResult);

        $this->test($env, $feature, false)->shouldReturn($testResult);
    }

    function it_should_call_the_decorated_method_during_test_with_skip_flag_if_the_feature_tagged_for_skip(
        SpecificationTester $featureTester,
        Config $config,
        Environment $env,
        FeatureNode $feature,
        TestResult $testResult
    ) {
        $config->shouldSkipFeatures()->willReturn(true);
        $config->getSkipTags()->willReturn(['pending', 'skip']);
        $feature->getTags()->willReturn(['javascript', 'pending']);

        $featureTester->test($env, $feature, true)->shouldBeCalled()->willReturn($testResult);

        $this->test($env, $feature, false)->shouldReturn($testResult);
    }

    function it_should_call_the_decorated_method_during_test_without_modifying_the_input_if_feature_skipping_disabled(
        SpecificationTester $featureTester,
        Config $config,
        Environment $env,
        FeatureNode $feature,
        TestResult $testResult
    ) {
        $config->shouldSkipFeatures()->willReturn(false);
        $config->getSkipTags()->willReturn(['pending', 'skip']);
        $feature->getTags()->willReturn(['javascript', 'pending']);

        $featureTester->test($env, $feature, false)->shouldBeCalled()->willReturn($testResult);

        $this->test($env, $feature, false)->shouldReturn($testResult);
    }
}
