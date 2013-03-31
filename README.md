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

# Todo / Ideas #
1. Move all of this list to somewhere for better organization
 1. Github issue tracker? (Whatever we use we want open to public, nice if stays in GitHub)
1. Add `su` to installation steps required such a pear
 1. See email titled "Coding Standards missing CakePHP" between Mirko & Anthony starting March 19th
1. Simplify Installation to be as little of steps as possible (even if requires additional scripting)
 1. See comments RE:Simplify bootstrap.php @ https://anthonysweb.beanstalkapp.com/cakephp2-template/changesets/f8221749f30b0e3fb47aeb3137541a50c1446351#comment-90505
 1. Add pre-hook installation to install script (and by doing this remove proprietary domains install folder)
 1. Make pre-hook installation optional, maybe y/n prompt during install, with brief mention only in documentation above?
1. Integrate all from original [Introducing coding standards procedure](http://goo.gl/T5xjL) on Basecamp
1. Integrate all from [CakePHP Coding Standards Google Doc](http://goo.gl/yYtgD) to this repository as needed, perhaps other linked README files, or just links to the documents where that content comes from?
1. Address misc from Mirko's email on March 17th 2013
 1. I'd love to write unit tests for the plugin and setup TravisCI (example: http://goo.gl/FG9qa). I think it'll make plugin look trustworthy and also friendly for contributors.
  1. Related thought: Make Coding Standards work out of the box with default CakePHP installtion.  Have the TravisCI pull from latest stable branch of CakePHP on github and run tests that way?
 1. Inspiration for contributing guidelines file copy: cakephp factory girl rails puppet
 1. We should think about using pull requests as primary mean of collaboration. I saw many other open source projects use them for good reasons - notify other project members about recent changes, encourage code review and discussion before merging pull request, others can even push follow-up commits if necessary.
1. Pick an open source licence and apply
1. New DOCUMENTATION.md movig extra information outside of quick start in README.md there?
1. Add a forced inclusions list (We sometimes want to check vendor code too -- can we make a pre-commit on a submodule work or.... perhaps could make CS work as submodule of submodule?  Hrmmm feels tricky)
 1. AW Internal: Configure cakephp standard template to check the Coding Standards code with Coding Standards
1. Make Coding Standards check its self (probably best done via a config variable for custom pathS settings)"
1. Link to documentation online for the standards
1. Proofread, spellcheck, cleanup, etc all instructions here
1. Make README.md here more concise
1. Make screencast demonstrating how it works
1. Promote this project @ CakePHP
1. Add HTML validation (via w3c API / file upload method with full action rendering? Would probably need configuration in project to do this, unless can figure out a way to do it right on .ctp files non-rendered)
1. Add markdown syntax checking?
1. Add things to do deeper code analysis, like redundency checks
 1. Perhaps not good as a prt of this project, but something seperate?
1. Document caveats with submodules (to use the pre-commit-submodule script, perhaps sanity check the pre-commit hook with --skip-submodules for pre-commit stashing) 
1. Code cleanup
 1. Move logic of /Vendors/check-staged-changes into PHP Plugin code, minimizing BASH script logic
  1. Along the way / after also cleanup logic to only check files that would be found in full-report, utilizing all configured rules?
