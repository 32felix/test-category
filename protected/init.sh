#!/bin/bash
DIR="$( cd "$( dirname "$0" )" && pwd )"
cd $DIR/..

REF=${1:-master}

echo Trying to checkout $REF

git fetch origin
git reset --hard
git checkout $REF
git pull

cd protected;

composer install -n
#npm install

rm -rf $DIR/../assets/*

#git rev-parse HEAD | cut -c 36- > config/cache.key

echo -en "\n\n\nCurrent commit: "
git rev-parse HEAD

git log -n 1
