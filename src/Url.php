<?php
declare(strict_types=1);

namespace Eightfold\Sitemap;

use Stringable;
// use Eightfold\XMLBuilder\Contracts\Buildable;

use DateTime;

use Eightfold\XMLBuilder\Element;

use Eightfold\Sitemap\Changefreq;

class Url implements Stringable
{
    private Element|string $lastmod = '';

    private Element|string $changefreq = '';

    private Element|string $priority = '';

    public static function create(string $path): self
    {
        return new self(
            htmlentities($path)
        );
    }

    final private function __construct(private string $path)
    {
    }

    public function withLastmod(DateTime $lastmod, bool $full = false): self
    {
        if ($full) {
            $this->lastmod = Element::lastmod($lastmod->format(DateTime::ATOM));

        } else {
            $this->lastmod = Element::lastmod($lastmod->format('Y-m-d'));

        }
        return $this;
    }

    public function withChangefreq(Changefreq $changefreq): self
    {
        $this->changefreq = Element::changefreq(
            $changefreq->value
        );
        return $this;
    }

    public function withPriority(float $priority): self
    {
        $this->priority = Element::priority(
            strval($priority)
        );
        return $this;
    }

    public function __toString(): string
    {
        return (string) Element::url(
            Element::loc($this->path),
            $this->lastmod,
            $this->changefreq,
            $this->priority
        );
    }
}
