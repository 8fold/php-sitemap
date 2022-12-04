<?php
declare(strict_types=1);

namespace Eightfold\Sitemap\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\Sitemap\Sitemap;

use DateTime;

use Eightfold\Sitemap\Changefreq;

class DocumentTest extends TestCase
{
    /**
     * @test
     */
    public function can_reproduce_0_9_sample(): void
    {
        $expected = file_get_contents(__DIR__ . '/sample0_9.xml');

        $result = (string) Sitemap::create('http://www.example.com')
            ->addUrl(
                loc: '/',
                lastmod: DateTime::createFromFormat('Y-m-d', '2005-01-01'),
                changefreq: Changefreq::MONTHLY,
                priority: 0.8
            )->addUrl(
                loc: '/catalog?item=12&desc=vacation_hawaii',
                changefreq: Changefreq::WEEKLY
            )->addUrl(
                loc: '/catalog?item=73&desc=vacation_new_zealand',
                lastmod: DateTime::createFromFormat('Y-m-d', '2004-12-23'),
                changefreq: Changefreq::WEEKLY
            )->addUrl(
                loc: '/catalog?item=74&desc=vacation_newfoundland',
                lastmod: DateTime::createFromFormat(
                    DateTime::ATOM,
                    '2004-12-23T18:00:15+00:00'
                ),
                priority: 0.3,
                fullLastmod: true
            )->addUrl(
                loc: '/catalog?item=83&desc=vacation_usa',
                lastmod: DateTime::createFromFormat('Y-m-d', '2004-11-23')
            );

        $this->assertSame(
            $expected,
            $result . "\n"
        );
    }

    /**
     * @test
     */
    public function has_expected_content(): void
    {
        $expected = <<<xml
        <?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>https://yourdomain.com/</loc></url></urlset>
        xml;

        $result = (string) Sitemap::create('https://yourdomain.com')
            ->addUrl('/');

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function has_expected_schema(): void
    {
        $result = (string) Sitemap::create('https://yourdomain.com');

        $this->assertTrue(
            str_contains($result, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"')
        );
    }

    /**
     * @test
     */
    public function must_be_utf8_encoded(): void
    {
        $result = (string) Sitemap::create('https://yourdomain.com');

        $this->assertTrue(
            str_contains($result, 'encoding="UTF-8"')
        );
    }
}
