[Jump back to README.md](README.md)

# Community developers #
Fork us and send a request we look at your update to merge into the main repository

# Setting up for development #
Weather a core or community developer we suggest adding a submodule to your existing project as this plugin doesn't make much sense standalone.  To do so execute this from your repository's root path:

1. git submodule add git@github.com:AnthonysWebLLC/CodingStandards.git ./app/Plugin/CodingStandards
 1. (Replace git repository URL if you've forked)
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

