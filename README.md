# 8fold Sitemap for PHP

Generates valid XML document and sitemap elements based on the [Sitemap protocol](https://www.sitemaps.org).

|Schema |Version |
|:------|:-------|
|0.9    |Latest  |

## Installation

```
composer require 8fold/php-sitemap
```

## Usage

You have received the required metadata in an array; could be the results of a database query.

```php
use Eigthfold\Sitemap\Sitemap;

$sitemap = Sitemap::create('http://yourdomain.com');

$items = // your array of items

foreach ($items as $item) {
  $sitemap = $sitemap->addUrl(
    $item->path
  );
}

(string) $sitemap;
```

## Details

Designed to fit within a loop.

## Other

{links or descriptions or license, versioning, and governance}
