[Jump back to README.md](README.md)

# Basic Workflow #

Fork >> Setup Development >> Edit >> Send GitHub pull request

# Development Setup #

1. Repositoriy setup (Add CodingStandards as a git submodule to an existing CakePHP project)
 1. `git submodule add git@github.com:AnthonysWebLLC/CodingStandards.git ./app/Plugin/CodingStandards`*
 1. `git commit -m "Adding CodingStandards Submodule"`
 1. Load Plugin in app/Config/bootstrap.php (See [README.md](README.md))
  1. Enable CodingStandards to check it's self
1. Development machine setup
 1. Usual `git clone` of your parent CakePHP project
 1. `git submodule update --init`
 1. `Console/cake CodingStandards.install`
 1. `cd app/Plugin/CodingStandards/.git/hooks && ln -s -f ../../Vendor/pre-commit-submodule ./pre-commit`

*Replace git url with your own fork as needed

# Editing #

1. Development machine CodingStandards Submodule Update
 1. `cd app/Plugin/CodingStandards`
 1. `git checkout master`
 1. `git pull`
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
