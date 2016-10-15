#/bin/bash
#
# Release script. 
#
# Can be used to release new version of the plugin into Wordpress plugins directory
# 
# Usage: sh release.sh VERSION
#

TAG=$1

echo $TAG

pushd .
cd svn
svn cp trunk tags/$TAG
svn ci -m "Release $TAG"
popd

git tag -a $TAG -m "Release $TAG"
git push --tags