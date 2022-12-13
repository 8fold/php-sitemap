<?php
declare(strict_types=1);

namespace Eightfold\Sitemap;

use Stringable;
// use Eightfold\XMLBuilder\Contracts\Buildable;

use DateTime;

use Eightfold\XMLBuilder\Document;

use Eightfold\Sitemap\Url;

use Eightfold\Sitemap\Changefreq;

/**
 * https://www.sitemaps.org
 *
 * Regarding priority: Some search engines, specifically Google, do not pay
 * much attention to priority (or lastmod). By default, we set all pages to 0.5.
 *
 * In your meta.json files, you can use the priority member to overwrite the
 * default. We recommend the following:
 *
 * 1.0-0.8: Homepage, product information, landing pages.
 * 0.7-0.4: News articles, some weather services, blog posts, pages that no site
 *          would be complete without.
 * 0.3-0.0: FAQs, outdated info, old press releases, completely static pages that
 *          are still relevant enough to keep from deleting entirely.
 */
class Sitemap implements Stringable
{
    private const SCHEMA_VERSION = 'http://www.sitemaps.org/schemas/sitemap/0.9';

    /**
     * @var array<string, Url>
     */
    private array $urls = [];

    public static function create(string $host): self
    {
        return new self($host);
    }

    final private function __construct(private string $host)
    {
    }

    public function addUrl(
        string $loc,
        ?DateTime $lastmod = null,
        ?Changefreq $changefreq = null,
        ?float $priority = null,
        bool $fullLastmod = false
    ): self {
        $u = Url::create(
            $this->host . $loc
        );

        if ($lastmod !== null) {
            $u = $u->withLastmod($lastmod, $fullLastmod);
        }

        if ($changefreq !== null) {
            $u = $u->withChangefreq($changefreq);
        }

        if ($priority !== null) {
            $u = $u->withPriority($priority);
        }

        $this->urls[$loc] = $u;

        return $this;
    }

    public function __toString(): string
    {
        return (string) Document::urlset(
            ...$this->urls
        )->props('xmlns ' . self::SCHEMA_VERSION);
    }
}
