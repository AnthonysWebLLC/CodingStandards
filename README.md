# About #
A full CakePHP Coding Standards stack centered around pre-commit automated testing

# Installation - Repository #
1. Copy this repository's files to ~/app/Plugin/CodingStandard
1. Add this to your bootstrap.php: if (Configure::read('debug') > 0) {  CakePlugin::load('CodingStandards', array('bootstrap' => true)); }
1. Add & commit files

# Installation - Each development server #
1. Console/cake CodingStandards.coding_standards_check install
1. ln -s -f /home/domains/domains/{sub.domain.tld}/app/Plugin/CodingStandards/Vendor/pre-commit .git/hooks/pre-commit
1. chmod +x /home/domains/domains/{sub.domain.tld}/app/Plugin/CodingStandards/Vendor/pre-commit
1. Optional: Add Configure::write('CodingStandards.SERVER_NAME', '<Insert Accessible URL HERE>')
 1. This will give you a full url where errors are output
 1. Will enable CSS style checking (We need to switch the API to uploads to make this not required)

[Note: The install script is only tested on Fedora and likely has issues with non-yum based linux flavors]

# Use #
After installing whenever you a attempt a commit the coding standards plugin will automatically check all changed code and present a warning message if anything doesn't follow the standards.  You can skip this check via `git commit -n`

You can also run a full repository coding standards check any time via `Console/cake CodingStandards.coding_standards_check`

# Why have Coding Standardsor changing ? #
Amoung many, one of the most prevalent reasons for having Coding Standards is to help make code more easily readbale when you have multiple or changing project contributors.

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
We'd love you're help! See [README-CONTRIBUTORS.md](README-CONTRIBUTORS.md)

# Todo #
1. Simplify Installation to be as little of steps as possible (even if requires additional scripting)
 1. Add pre-hook installation to install script (and by doing this remove proprietary domains install folder)
 2. 
1. Integrate all from original [Introducing coding standards procedure](http://goo.gl/T5xjL) on Basecamp
1. Integrate all from [CakePHP Coding Standards Google Doc](http://goo.gl/yYtgD) to this repository as needed, perhaps other linked README files, or just links to the documents where that content comes from?
1. Pick an open source licence and apply
1. Link to documentation online for the standards
1. Proofread, spellcheck, cleanup, etc all instructions here
1. Make README.md here more concise
1. Make screencast demonstrating how it works
1. Promote this project @ CakePHP
