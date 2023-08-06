# Installation

Make sure you have the following repository and configuration options added to your composer.json file in your WordPress plugin or theme:

```json
{
  "repositories": [{
    "type": "composer",
    "url": "https://ce-kw.github.io/satis/"
  }],
  "minimum-stability": "dev",
  "prefer-stable": true
}
```

Then run `composer require kwio/gutenberg-blocks-framework`.