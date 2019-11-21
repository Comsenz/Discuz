#!/bin/bash

if [ -z "$GITHUB_TOKEN" ]; then
    echo -e "\e[0;31mGITHUB_TOKEN is not set."
    exit 1
fi

git config user.name "discuz-bot"
git config user.email "171550539@qq.com"

git add app/* -f
git commit -m "php-cs-fixer output for commit $GITHUB_SHA [Skip ci]"

OUT="$(git push https://"$GITHUB_ACTOR":"$GITHUB_TOKEN"@github.com/"$GITHUB_REPOSITORY".git HEAD:"$GITHUB_REF" 2>&1 > /dev/null)"

echo "$OUT"
