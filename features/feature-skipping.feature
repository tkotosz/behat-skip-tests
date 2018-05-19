Feature: Skip features
  In order to skip not-yet-implemented feature
  As a developer
  I should be able to force behat to skip all scenario within a feature

  Background:
    Given I have the feature:
      """
      @pending
      Feature: First feature

      Scenario: My scenario
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

  Scenario: Skip pending feature
    Given I have the configuration:
      """
      default:
        extensions:
          Bex\Behat\SkipTestsExtension: ~
      """
    When I run Behat with "--no-snippets" parameter
    Then I should see the message "1 scenario (1 undefined)"

  Scenario: Not skipping feature non-pending features
    Given I have the configuration:
      """
      default:
        extensions:
          Bex\Behat\SkipTestsExtension: ~
      """
    And I have the feature:
      """
      Feature: First feature

      Scenario: My scenario
        Given I have a passing step
        When I have a failing step
        Then I have a totally new undefined step
      """
    When I run Behat with "--no-snippets" parameter
    Then I should see the message "1 scenario (1 failed)"

  Scenario: Not skipping pending features
    Given I have the configuration:
      """
      default:
        extensions:
          Bex\Behat\SkipTestsExtension:
            skip_features: false
      """
    When I run Behat with "--no-snippets" parameter
    Then I should see the message "1 scenario (1 failed)"
  
