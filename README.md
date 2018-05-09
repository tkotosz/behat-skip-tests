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

See example in [this](https://github.com/tkotosz/behat-skip-tests/blob/master/features/scenario-skipping.feature) feature file.
