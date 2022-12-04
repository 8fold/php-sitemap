<?php
declare(strict_types=1);

namespace Eightfold\Sitemap;

use Eightfold\XMLBuilder\Contracts\Buildable;

use DateTime;

use Eightfold\XMLBuilder\Document;

use Eightfold\Sitemap\Url;

use Eightfold\Sitemap\Changefreq;

class Sitemap implements Buildable
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

    public function build(): string
    {
        return (string) $this;
    }

    public function __toString(): string
    {
        return (string) Document::urlset(
            ...$this->urls
        )->props('xmlns ' . self::SCHEMA_VERSION);
    }
}
