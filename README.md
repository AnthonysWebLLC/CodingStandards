# About
A full CakePHP Coding Standards stack centered around pre-commit automated testing

# Installation
1. Copy this repository's files to ~/app/Plugin/CodingStandard
1. Console/cake CodingStandards.coding_standards_check install
1. ln -s -f /home/domains/domains/{sub.domain.tld}/app/Plugin/CodingStandards/Vendor/pre-commit .git/hooks/pre-commit
1. chmod +x /home/domains/domains/{sub.domain.tld}/app/Plugin/CodingStandards/Vendor/pre-commit

[Note: The install script is only tested on Fedora and likely has issues with non-yum based linux flavors]

# Use
After installing whenever you a attempt a commit the coding standards plugin will automatically check all changed code and present a warning message if anything doesn't follow the standards.  You can skip this check via 

# Why have Coding Standardsor changing ?
Amoung many, one of the most prevalent reasons for having Coding Standards is to help make code more easily readbale when you have multiple or changing project contributors.

# The standards
* PHP - 
* JavaScript - jQuery's standards
* ?HTML - W3C Validation / Google Coding Standards?
* ?CSS - W3C / Google Coding Standards?

# The tools
* PHP - PHPCS
* JavaScript - JSHint
* ?HTML - W3C Validation API?
* ?CSS - W3C Validation API?

# Contributing
We'd love you're help! See [README-CONTRIBUTORS.md](README-CONTRIBUTORS.md)

# Todo
1. Integrate all from [CakePHP Coding Standards Google Doc](http://goo.gl/yYtgD) to this README.md as needed.
1. Pick an open source licence and apply
1. Link to documentation online for the standards
1. Proofread, spellcheck, cleanup, etc all instructions here
1. Make README.md here more concise
1. Make screencast demonstrating how it works
1. Promote this project @ CakePHP
