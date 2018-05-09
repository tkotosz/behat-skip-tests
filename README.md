Behat-SkipTestsExtension
=========================
[![License](https://poser.pugx.org/bex/behat-skip-tests/license)](https://packagist.org/packages/bex/behat-skip-tests)
[![Latest Stable Version](https://poser.pugx.org/bex/behat-skip-tests/version)](https://packagist.org/packages/bex/behat-skip-tests)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tkotosz/behat-skip-tests/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tkotosz/behat-skip-tests/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/tkotosz/behat-skip-tests/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tkotosz/behat-skip-tests/build-status/master)
[![Build Status](https://travis-ci.org/tkotosz/behat-skip-tests.svg?branch=master)](https://travis-ci.org/tkotosz/behat-skip-tests)

Behat-SkipTestsExtension allows you to skip tests without excluding them from the output.

Installation
------------

Install by adding to your `composer.json`:

```bash
composer require --dev bex/behat-skip-tests
```

Configuration
-------------

Enable the extension in `behat.yml` like this:

```yml
default:
  extensions:
    Bex\Behat\SkipTestsExtension: ~
```

Usage
-----

Add `@pending` tag to any scenario to skip all steps within that scenario.

Example input:
```gherkin
Feature: First feature

 Scenario: First scenario
   Given I have a few things
   When I do whatever
   Then I should see something
 
 @pending
 Scenario: Second scenario
   Given I have a lot of things
   When I do whatever
   Then I should see anything 

 Scenario: Third scenario
   Given I have a few other things
   When I do whatever
   Then I should see something else
```

Example output:
```bash
$ bin/behat --no-snippets
Feature: First feature

  Scenario: First scenario      # features/first.feature:3
    Given I have a few things   # FeatureContext::iHaveAFewThings()
    When I do whatever          # FeatureContext::iDoWhatever()
    Then I should see something # FeatureContext::iShouldSeeSomething()

  @pending
  Scenario: Second scenario      # features/first.feature:9
    Given I have a lot of things # FeatureContext::iHaveALotOfThings()
    When I do whatever           # FeatureContext::iDoWhatever()
    Then I should see anything

  Scenario: Third scenario           # features/first.feature:14
    Given I have a few other things  # FeatureContext::iHaveAFewOtherThings()
    When I do whatever               # FeatureContext::iDoWhatever()
    Then I should see something else # FeatureContext::iShouldSeeSomethingElse()

3 scenarios (2 passed, 1 undefined)
9 steps (6 passed, 1 undefined, 2 skipped)
0m0.01s (7.72Mb
```
