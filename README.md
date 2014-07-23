# Swot PHP :apple:

[![Build Status](https://travis-ci.org/mdwheele/swotphp.svg?branch=master)](https://travis-ci.org/mdwheele/swotphp) [![Latest Stable Version](https://poser.pugx.org/mdwheele/swotphp/v/stable.svg)](https://packagist.org/packages/mdwheele/swotphp) [![Total Downloads](https://poser.pugx.org/mdwheele/swotphp/downloads.svg)](https://packagist.org/packages/mdwheele/swotphp) [![License](https://poser.pugx.org/mdwheele/swotphp/license.svg)](https://packagist.org/packages/mdwheele/swotphp)

This is a port of the popular Ruby Gem, "Swot".  As such, please do NOT make data contributions to this repository.
Contribute any new academic domain names to the Ruby version of this package at https://github.com/leereilly/swot.
Please follow the [contribution guidelines](https://github.com/leereilly/swot/blob/master/CONTRIBUTING.md) noted on 
Lee's repository.

I will be pulling changes to upstream domain data.

> If you have a product or service and offer **academic discounts**, there's a good chance there's some manual 
> component to the approval process. Perhaps `.edu` email addresses are automatically approved because, for the most 
> part at least, they're associated with American post-secondary educational institutions. Perhaps `.ac.uk` email 
> addresses are automatically approved because they're guaranteed to belong to British universities and colleges. 
> Unfortunately, not every country has an education-specific TLD (Top Level Domain) and plenty of schools use `.com` 
> or `.net`.

> Swot is a community-driven or crowdsourced library for verifying that domain names and email addresses are tied to 
> a legitimate university of college - more specifically, an academic institution providing higher education in 
> tertiary, quaternary or any other kind of post-secondary education in any country in the world.

### Installation

Install through Composer.

```json
"require": {
    "mdwheele/swotphp": "dev-master"
}
```

### Usage

The easiest way to get started with SwotPHP is to use a facade to access functionality.  However, the base
implementation is present as a plain ol' PHP object in case developers want control over the construction themselves.
The implementation has an injected dependency on [PHP Domain Parser](https://github.com/jeremykendall/php-domain-parser),
an implementation of a URL parser backed by the [Public Suffix List](http://publicsuffix.org).

Framework-specific facades / service providers may be added later.  The native facade is a good example of how
the object should be instantiated.

**Native**

```php
require_once('vendor/autoload.php');

use SwotPHP\Facades\Native\Swot;
```

#### Verify Email Addresses

```php
Swot::isAcademic('lreilly@stanford.edu')           # true
Swot::isAcademic('lreilly@strath.ac.uk')           # true
Swot::isAcademic('lreilly@soft-eng.strath.ac.uk')  # true
Swot::isAcademic('pedro@ugr.es')                   # true
Swot::isAcademic('lee@uottawa.ca')                 # true
Swot::isAcademic('lee@leerilly.net')               # false
```

#### Verify Domain Names

```ruby
Swot::isAcademic('harvard.edu')              # true
Swot::isAcademic('www.harvard.edu')          # true
Swot::isAcademic('http://www.harvard.edu')   # true
Swot::isAcademic('http://www.github.com')    # false
Swot::isAcademic('http://www.rangers.co.uk') # false
```

#### Find School Names

```php
Swot::schoolName('lreilly@cs.strath.ac.uk') -> "University of Strathclyde"
Swot::schoolName('http://www.stanford.edu') -> "Stanford University"
```

### Known Issues

- Lack of automated data import from Ruby gem's domain list.
- There are architectural issues with the package resultant from differences in PHP and Ruby-isms. When I get time, there are two areas I'd like to focus on:
    1. Consolidate some of how the internal API flows while maintaining public interface.
    2. Implement a different mode of accessing the public suffix registry.  Currently, the package is possibly abusing an excellent [URL parser](https://github.com/jeremykendall/php-domain-parser) and I'd like to investigate the possibility of a separate component dedicated to modeling the Public Suffix registry's ruleset and algorithms.
