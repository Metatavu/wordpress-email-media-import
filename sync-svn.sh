#/bin/bash
#
# Git -> Subversion synchronization script.  
#
# Can be used to synchronize Git develop with the SVN repository trunk
# This script is greatly inspired by scripts from https://github.com/kasparsd/wp-deploy
# 
# Usage: sh sync-svn.sh
#

git checkout develop
git pull

pushd .
cd svn/trunk
svn up
popd

rsync --recursive --exclude='.*' --exclude='.git' --exclude='tests' --exclude='svn' --exclude="wp" --exclude="vendor" . svn/trunk

for file in $(cat ".svnignore" 2> /dev/null)
do
  rm -rf svn/trunk/$file
done

pushd .
cd svn/trunk
svn stat | awk '/^\?/ {print $2}' | xargs svn add > /dev/null 2>&1
svn stat | awk '/^\!/ {print $2}' | xargs svn rm --force
svn stat
svn ci -m "Git -> Subversion sync"
popd