# About #
A full CakePHP Coding Standards stack centered around an easy to use shell interface and git pre-commit hooks for automated testing

# Install #
1. Copy files to `app/Plugin/CodingStandards`
1. Load Plugin in `app/Config/bootstrap.php`*
1. Install via `Console/cake CodingStandards.install`**

*Code to add

````php
    if (Configure::read('debug') > 0) {
        CakePlugin::load('CodingStandards', array('bootstrap' => true));
        //Configure::write('CodingStandards.ADDITIONAL_PATHS', array('CodingStandards' => Configure::read('CodingStandards.PLUGIN_PATH'))); // Optional - Useful if you have extra paths you want included in full reports.  Example here is the coding standards themeselves, though you can other other(s).
        //Configure::write('CodingStandards.SERVER_NAME', '<Insert Accessible URL HERE>') // Optional and probably server specific -- enables CSS checking & provides full URL for HTML reports
        //Also see See app/Plugin/CodingStandards/Config/bootstrap.php for other variables you can tweak
    }
````

**Meant to be run on each server / installation.  The install script is only tested on CentOS 6.3 and an old version of Fedora.  There are likely major issues with non-yum based linux flavors.

# Use #
After installing whenever you a attempt a commit the coding standards plugin will automatically check all changed code and present a warning message if anything doesn't follow the standards.  You can skip this check via `git commit -n`

You can also run an interactive coding standards shell any time via `Console/cake CodingStandards.check`

Or you can check an individual file with `Console/cake CodingStandards.check check_file [FILE] [(summary|full_report)]`

# Why have Coding Standards? #

* More readbale code by driving
 * Consistency with multiple developers
 * Consistency in a large codebase
* Faster to implement with tools here
 * Full project based HTML report
 * Automated pre-commit hooks

# The standards #
* PHP -
* JavaScript - jQuery's standards
* ?HTML - W3C Validation / Google Coding Standards?
* ?CSS - W3C / Google Coding Standards?

# The tools #
* PHP - PHPCS
* JavaScript - JSHint
* ?HTML - W3C Validation API?
* ?CSS - W3C Validation API?

# Contributing #
We'd love you're help! See [CONTRIBUTING.md](CONTRIBUTING.md)
