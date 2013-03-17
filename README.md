# About
A full CakePHP Coding Standards stack centered around pre-commit automated testing

# Installation
1. Copy this repository's files to ~/app/Plugin/CodingStandard
1. Console/cake CodingStandards.coding_standards_check install
1. ln -s -f /home/domains/domains/{sub.domain.tld}/app/Plugin/CodingStandards/Vendor/pre-commit .git/hooks/pre-commit
1. chmod +x /home/domains/domains/{sub.domain.tld}/app/Plugin/CodingStandards/Vendor/pre-commit

And you're done, 

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
We suggest adding this project as a submodule to your existing project (it doesn't make much sense standalone).  To do this execute this from your repository's root path
1. git submodule add git@github.com:AnthonysWebLLC/CodingStandards.git ./app/Plugin/CodingStandards
1. git commit -m "Adding CodingStandards Submodule"

Then when you clone either use `git clone --recursive <...>` when copying your repository or run `git submodule update --init` to get all the files

The to update do the following
1. cd app/Plugin/CodingStandards
1. git checkout master
1. git pull

Then you'll have to update your main repository with something like
1. cd ..
1. git add CodingStandards
1. git commit -m "Updating CodingStandards submodule to lastest in master"
1. git push
