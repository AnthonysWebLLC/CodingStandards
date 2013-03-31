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

* More readbale code by driving consistency
 * With multiple developers
 * In a large codebase
 * Across infrequent updates
* Faster to implement via tools here
 * Full project based HTML report
 * Automated pre-commit hooks

# Chosen Standards #

| Type                                                    | Coding Standard                                      |
|:------------------------------------------------------- |:---------------------------------------------------- |
| PHP (Models, Controllers, Console, Configuration, ...)  | [CakePHP Coding Standards](http://goo.gl/lWw9V)      |
| CTP (Views, Layouts, Elements, ...)                     | [CakePHP Coding Standards](http://goo.gl/lWw9V)*     |                                       |
| CSS                                                     | [W3C CSS Validation](http://goo.gl/g5Vrk)            |
| JavaScript                                              | [jQuery JavaScript Style Guide](http://goo.gl/nFpZl) |

*CakePHP's PHP standards are applied here, but they give a lot of what are probably false-positive errors.  Though they can be resolved, we probably need to work on a better matching Coding Standard for .ctp files

# The standards #
* PHP -
* JavaScript - jQuery's standards
* ?CSS - W3C / Google Coding Standards?
* ?HTML - W3C Validation / Google Coding Standards?

# The tools #
* PHP - PHPCS
* JavaScript - JSHint
* ?HTML - W3C Validation API?
* ?CSS - W3C Validation API?

# Contributing #
We'd love you're help! See [CONTRIBUTING.md](CONTRIBUTING.md)
