#!/bin/bash

git clone git@github.com:Comsenz/framework.git

composer install

vendor/bin/php-cs-fixer fix

git config user.name "discuz-bot"
git config user.email "171550539@qq.com"

git add app/* -f
git commit -m "php-cs-fixer output for commit $GITHUB_SHA [Skip ci]"

OUT="$(git push)"

echo "$OUT"
