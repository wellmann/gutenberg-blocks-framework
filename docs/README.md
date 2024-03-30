# 🧱 Gutenberg Blocks Framework Docs

## Requirements

* [docsify](https://docsify.js.org/)
* Docker

## Setup

`npm i docsify-cli -g`

## Development

`docsify serve ./docs`

### Generate reference docs

Run `docker run --rm -v $(pwd):/data phpdoc/phpdoc run -d ./src -t ./docs/reference --template=.phpdoc/templates` from project root.