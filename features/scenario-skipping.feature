Feature: Skip scenarios
  In order to skip not-yet-implemented scenarios
  As a developer
  I should be able to force behat to skip specific scenarios

  Background:
    Given I have the feature:
      """
      Feature: First feature

      Scenario: First scenario
        Given I have a passing step
        When I have another passing step
        Then I have another passing step

      @pending
      Scenario: Second scenario
        Given I have a passing step
        When I have a not yet implemented but defined step
        Then I have a totally new undefined step
      """
    And I have the context:
      """
      <?php
      use Behat\Behat\Context\Context;
      use Behat\Behat\Context\SnippetAcceptingContext;
      class FeatureContext implements Context, SnippetAcceptingContext
      {
          /**
           * @Given I have a passing step
           */
          public function iHaveAPassingStep()
          {
              return true;
          }
          
          /**
           * @Given I have another passing step
           */
          public function iHaveAnotherPassingStep()
          {
              return true;
          }

          /**
           * @Given I have a not yet implemented but defined step
           */
          public function isHaveAPendingStep()
          {
              throw new \Behat\Behat\Tester\Exception\PendingException();
          }
      }
      """

  Scenario: Skip pending scenario
    Given I have the configuration:
      """
      default:
        extensions:
          Bex\Behat\SkipTestsExtension: ~
      """
    When I run Behat with "--no-snippets" parameter
    Then I should see the message "6 steps (3 passed, 1 undefined, 2 skipped)"
