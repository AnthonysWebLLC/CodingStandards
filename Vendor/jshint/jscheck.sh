#!/bin/bash

BASEDIR=$(dirname $0)

if [ $# -eq 0 ]
then
    echo "Usage: $0 file_path"
    echo "For eg"
    echo " > $0 ../html/js/wth/events.js"
    echo " > $0 ../html/js/wth/*"
    exit 1
fi

filepath=$1

GLOBALS="$,Handlebars,tinyMCE"

rhino $BASEDIR/jshint-rhino.js $filepath curly=true,expr=true,newcap=false,quotmark=double,regexdash=true,trailing=true,undef=true,unused=true,maxerr=100,eqnull=true,evil=true,sub=true,browser=true,wsh=true,predef={define,jQuery} $GLOBALS