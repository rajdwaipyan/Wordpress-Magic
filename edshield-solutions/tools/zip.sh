#!/usr/bin/env bash
# Package the theme as an installable zip: dist/edshield-<version>.zip
set -euo pipefail
cd "$(dirname "$0")/.."
version=$(grep -m1 -E '^Version:' theme/edshield/style.css | sed -E 's/^Version:\s*//' | tr -d '\r ')
mkdir -p dist
out="dist/edshield-${version}.zip"
rm -f "$out"
(cd theme && zip -rq "../$out" edshield -x 'edshield/node_modules/*' 'edshield/.git/*' '*.DS_Store' 'edshield/package-lock.json')
echo "$out ($(du -h "$out" | cut -f1), $(unzip -l "$out" | tail -1 | awk '{print $2}') files)"
