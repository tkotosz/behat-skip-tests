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
        When I have a failing step
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
           * @Given I have a failing step
           */
          public function isHaveAPendingStep()
          {
              throw new \Exception('error');
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
    Then I should see the message "2 scenarios (1 passed, 1 undefined)"
  
  Scenario: Not skipping pending scenario
    Given I have the configuration:
      """
      default:
        extensions:
          Bex\Behat\SkipTestsExtension:
            skip_scenarios: false
      """
    When I run Behat with "--no-snippets" parameter
    Then I should see the message "2 scenarios (1 passed, 1 failed)"

  Scenario: Skipping scenario based on custom skip-tags
    Given I have the configuration:
      """
      default:
        extensions:
          Bex\Behat\SkipTestsExtension:
            skip_tags: ['mytag', 'myothertag']
      """
    And I have the feature:
      """
      Feature: First feature

      Scenario: First scenario
        Given I have a passing step
        When I have another passing step
        Then I have another passing step

      @mytag
      Scenario: Second scenario
        Given I have a passing step
        When I have a not yet implemented but defined step
        Then I have a totally new undefined step

      @myothertag
      Scenario: Third scenario
        Given I have a passing step
        When I have a not yet implemented but defined step
        Then I have a totally new undefined step

      Scenario: Forth scenario
        Given I have a passing step
        When I have another passing step
        Then I have another passing step
      """
    When I run Behat with "--no-snippets" parameter
    Then I should see the message "4 scenarios (2 passed, 2 undefined)"

  Scenario: Background skipped for skipped scenario
    Given I have the configuration:
      """
      default:
        extensions:
          Bex\Behat\SkipTestsExtension: ~
      """
    And I have the feature:
      """
      Feature: Multi-step feature

      Background:
        Given I have a failing step

      @pending
      Scenario: my scenario
        Given I have a passing step
        When I have a failing step
        Then I have a totally new undefined step
      """
    When I run Behat with "--no-snippets" parameter
    Then I should see the message "1 scenario (1 undefined)"

  Scenario: Skip pending examples (scenario outline)
    Given I have the configuration:
      """
      default:
        extensions:
          Bex\Behat\SkipTestsExtension: ~
      """
    And I have the feature:
      """
      Feature: Multi-step feature

      @pending
      Scenario Outline: my outline scenario
        Given I have a <first> step
        When I have a <second> step
        Then I should have a <third> step

        Examples:
          | first   | second  | third   |
          | passing | failing | skipped |
      """
    When I run Behat with "--no-snippets" parameter
    Then I should see the message "1 scenario (1 undefined)"
