# Gutenberg Blocks Framework 🧱 Docs

## Requirements

* [Jekyll](https://jekyllrb.com/docs/installation/)
* Bundler
* Docker

## Setup

`bundle install`

## Development

`bundle exec jekyll serve --livereload`

### Generate reference docs

Run `docker run --rm -v $(pwd):/data phpdoc/phpdoc:3 -d ./src -t ./docs/reference --template=.phpdoc/templates` from project root.