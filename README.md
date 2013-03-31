# About #
This is a full CakePHP Coding Standards stack centered around:

* An easy to use shell interface for report generation
* Git pre-commit hooks for automated testing

# Requirments #
* Functioning CakePHP 2 application*
* CentOS or similar yum based Linux flavor**

*Tested with CakePHP 2.3.1

**Our install script is only tested on CentOS 6.3 and an old version of Fedora.  There are likely major issues with non-yum based Linux flavors.  We intend to support other major Linux flavors in future releases as the community grows.

# Install #
1. Copy files to `app/Plugin/CodingStandards`
1. Load Plugin in `app/Config/bootstrap.php`*
1. Install via `Console/cake CodingStandards.install`**

*Code to add

````php
    if (Configure::read('debug') > 0) {
        CakePlugin::load('CodingStandards', array('bootstrap' => true));
        //Configure::write('CodingStandards.ADDITIONAL_PATHS', array('CodingStandards' => Configure::read('CodingStandards.PLUGIN_PATH'))); // Optional - Useful if you have extra paths you want included in full reports.  Example here is the coding standards themselves, though you can other other(s).
        //Configure::write('CodingStandards.SERVER_NAME', '<Insert Accessible URL HERE>') // Optional and probably server specific -- enables CSS checking & provides full URL for HTML reports
        //Also see See app/Plugin/CodingStandards/Config/bootstrap.php for other variables you can tweak
    }
````

**Meant to be run on each server / installation.

# Use #

1. Run `Console/cake CodingStandards.check` >> run a full HTML report >> clean up all code
1. Run `Console/cake CodingStandards.check` >> run reports on specific file(s) you're concerned about >> clean up specific code
1. Run `git commit` >> (automatic check run, errors descriptively abort commit, otherwise done)* >> fix >> Run `git commit` ...

*You can skip this check via `git commit -n`

# Standards #

| Type                                                    | Coding Standard                                      |
|:------------------------------------------------------- |:---------------------------------------------------- |
| General (Applied to all files)                          | [Formatting Basics](#formatting-basics)              |
| PHP (Models, Controllers, Console, Configuration, ...)  | [CakePHP Coding Standards](http://goo.gl/lWw9V)      |
| CTP (Views, Layouts, Elements, ...)                     | [CakePHP Coding Standards](http://goo.gl/lWw9V)*     |                                       |
| CSS                                                     | [W3C CSS Validation](http://goo.gl/g5Vrk)            |
| JavaScript                                              | [jQuery JavaScript Style Guide](http://goo.gl/nFpZl) |

*CakePHP's PHP standards are applied here, but they give a lot of what are probably false-positive errors.  Though they can be resolved, we probably need to work on / find a better matching Coding Standard for .ctp files

### Formatting Basics ###
* Files MUST use only UTF-8 without BOM
* All files MUST use the Unix LF (linefeed) line ending
* Code MUST use an tab indent, and MUST NOT use spaces for indenting
* There MUST NOT be trailing whitespace at the end of non-blank lines

# Why Coding Standards? #

* More readable code by driving consistency
 * With multiple developers
 * In a large codebase
 * Across infrequent updates
* Faster to implement via tools here
 * Full project based HTML report
 * Automated pre-commit hooks
* ...

# Contributing #
We'd love your help! See [CONTRIBUTING.md](CONTRIBUTING.md)
