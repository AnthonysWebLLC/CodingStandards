#!/bin/bash

BASEDIR=$(dirname $0)
GLOBALS="$"

if [ $# -eq 0 ]
then
    echo "Usage: $0 file_path"
    echo "For eg"
    echo " > $0 ../html/js/wth/events.js"
    echo " > $0 ../html/js/wth/*"
    exit 1
fi

# Support custom globals variables as shell second parameter
# since jshint-rhino.js doesn't support /* global MY_LIB: false */ in JS files
if [ $# -eq 2 ]
then
    GLOBALS=$2
fi

filepath=$1

rhino $BASEDIR/jshint-rhino.js $filepath curly=true,expr=true,newcap=false,quotmark=double,regexdash=true,trailing=true,undef=true,unused=true,scripturl=true,maxerr=100,eqnull=true,evil=true,sub=true,browser=true,wsh=true,predef={define,jQuery} $GLOBALS