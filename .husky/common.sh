#!/bin/sh

command_exists() {
  command -v "$1" >/dev/null 2>&1
}

run_composer_script() {
  if command_exists composer; then
    composer run-script "$1" --no-interaction
  else
    echo "Composer not found. Skipping $1"
  fi
}

run_npm_script() {
  if command_exists npm; then
    npm run "$1"
  else
    echo "npm not found. Skipping $1"
  fi
}
