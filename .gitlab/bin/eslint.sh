#!/usr/bin/env sh

set -eu

./node_modules/.bin/eslint -c ./.eslintrc ./resources --format json |
    sed "s#$PWD/\?##g" |
    jq '[.[] | .filePath as $filename
      | .messages[] as $message
      | {description: $message.message, hash: ($message.message + $filename), location: { path: $filename, lines: { begin:$message.line, end:$message.endLine } } } ]' |
    jq -c .[] |
    while read -r jsonLine; do
        hash="$(echo $jsonLine | jq -r '.hash' | md5sum | awk '{print $1}')"

        echo $jsonLine | jq --arg hash "$hash" -s -r '.[] | .hash |= "\($hash)"'
    done |
    jq -n '[inputs]'
