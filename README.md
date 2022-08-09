[![MIT license](http://img.shields.io/badge/license-MIT-brightgreen.svg)](http://opensource.org/licenses/MIT)
[![Packagist](https://img.shields.io/packagist/v/flownative/rulebasedredirects.svg)](https://packagist.org/packages/flownative/rulebasedredirects)
[![Maintenance level: Friendship](https://img.shields.io/badge/maintenance-%E2%99%A1%E2%99%A1-ff69b4.svg)](https://www.flownative.com/en/products/open-source.html)

# Rule-Based Redirects for Flow

This package uses a PSR middleware to redirect requests to URLs using regular expression
based rules.

It can be used to implement such redirect rules in cases where you cannot use the native
ways of implementing them in your webserver (e.g. nginx or Apache httpd.)

## Installation

`composer require flownative/rulebasedredirects`

## Usage

As soon as the package is installed, the rules given in `Flownative.RuleBasedRedirects.rules`
take effect. Here is an example:

```yaml
Flownative:
  RuleBasedRedirects:
    rules: []
      - host: '/^www\.acme\.com$/'
        path: '/^\/foo\/(.*)$/'
        target: 'https://www.acme.com/elsewhere/$1'
        status: 301
```
