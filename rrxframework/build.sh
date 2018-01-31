#!/bin/sh
MODULE_NAME="rrxframework"
rm -rf output
mkdir -p output/application/$MODULE_NAME
cp -r base util ext service components output/application/$MODULE_NAME
cd output
tar cvzf $MODULE_NAME.tar.gz application
rm -rf application
