[Jump back to README.md](README.md)

# Community developers #
Fork us and send a request we look at your update to merge into the main repository

# Setting up for development #
Weather a core or community developer we suggest adding a submodule to your existing project as this plugin doesn't make much sense standalone.  To do so execute this from your repository's root path:

1. git submodule add git@github.com:AnthonysWebLLC/CodingStandards.git ./app/Plugin/CodingStandards
 1. (Replace git repository URL if you've forked)
1. git commit -m "Adding CodingStandards Submodule"
1. [Add to ~/app/Config/bootstrap.php to check Coding Standards code with Coding Standards via something like copying the example past Configuration::write('CodingStandards.ADDITIONAL_PATHS', ...)]
 1. Caveat: If we start to allow Coding Standards sniffer customizations / configuration we may run into trouble here
1. Console/cake CodingStandards.install

Then when you clone either use `git clone --recursive <...>` when copying your repository or run `git submodule update --init` to get all the files

## Updating your copy ##
1. Initial setup on your machine
 1. `git submodule update --init`
 1. `cd app/Plugin/CodingStandards`
 1. `ln -s -f ../../Vendor/pre-commit-submodule ./.git/hooks/pre-commit`
 1. `chmod +x pre-commit`
1. Update CodingStandards Submodule
 1. `cd app/Plugin/CodingStandards`
 1. `git checkout master`
 1. `git pull`
1. Update parent project's link to submodule
 1. `cd ..`
 1. `git add CodingStandards`
 1. `git commit -m "Updating CodingStandards submodule"`
 1. `git push`

## Makeing edits ##

1. Make changes to Coding Standards
 1. `cd app/Plugin/CodingStandards`
 1. (Make changes)
 1. `git commit ...`
 1. `git push`
1. Update parent project's link to submodule
 1. `cd ..`
 1. `git add CodingStandards`
 1. `git commit -m "Updating CodingStandards submodule"`
 1. `git push`


# To-do #

1. Add link to good page explaining submodules