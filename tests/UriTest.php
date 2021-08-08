<?php

namespace Bauhaus;

use Generator;
use Bauhaus\Uri\InvalidArgument;
use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
{
    public function validUris(): array
    {
        return [
            'tel:+49666' => new Uri(
                scheme: 'tel',
                user: null,
                password: null,
                host: null,
                port: null,
                path: '+49666',
                query: [],
                fragment: null,
            ),

            'scheme://host' => new Uri(
                scheme: 'scheme',
                user: null,
                password: null,
                host: 'host',
                port: null,
                path: '',
                query: [],
                fragment: null,
            ),

            'scheme://host/path' => new Uri(
                scheme: 'scheme',
                user: null,
                password: null,
                host: 'host',
                port: null,
                path: '/path',
                query: [],
                fragment: null,
            ),

            'scheme://host/super/path' => new Uri(
                scheme: 'scheme',
                user: null,
                password: null,
                host: 'host',
                port: null,
                path: '/super/path',
                query: [],
                fragment: null,
            ),

            'scheme://host/super/path-super' => new Uri(
                scheme: 'scheme',
                user: null,
                password: null,
                host: 'host',
                port: null,
                path: '/super/path-super',
                query: [],
                fragment: null,
            ),

            'scheme://host:666' => new Uri(
                scheme: 'scheme',
                user: null,
                password: null,
                host: 'host',
                port: 666,
                path: '',
                query: [],
                fragment: null,
            ),

            'scheme://user@host:666' => new Uri(
                scheme: 'scheme',
                user: 'user',
                password: null,
                host: 'host',
                port: 666,
                path: '',
                query: [],
                fragment: null,
            ),

            'scheme://user:secret@host:666' => new Uri(
                scheme: 'scheme',
                user: 'user',
                password: 'secret',
                host: 'host',
                port: 666,
                path: '',
                query: [],
                fragment: null,
            ),

            'scheme://user:secret@host:666' => new Uri(
                scheme: 'scheme',
                user: 'user',
                password: 'secret',
                host: 'host',
                port: 666,
                path: '',
                query: [],
                fragment: null,
            ),

            'scheme://user:secret@host:666#fragment' => new Uri(
                scheme: 'scheme',
                user: 'user',
                password: 'secret',
                host: 'host',
                port: 666,
                path: '',
                query: [],
                fragment: 'fragment',
            ),

            'scheme://user:secret@host:666?query=value#fragment' => new Uri(
                scheme: 'scheme',
                user: 'user',
                password: 'secret',
                host: 'host',
                port: 666,
                path: '',
                query: [ 'query' => 'value' ],
                fragment: 'fragment',
            ),

            'scheme://user:secret@host:666/?query=value#fragment' => new Uri(
                scheme: 'scheme',
                user: 'user',
                password: 'secret',
                host: 'host',
                port: 666,
                path: '/',
                query: [ 'query' => 'value' ],
                fragment: 'fragment',
            ),

            'scheme://user:secret@host:666/?q1=v1&q2=v2&q3=v3' => new Uri(
                scheme: 'scheme',
                user: 'user',
                password: 'secret',
                host: 'host',
                port: 666,
                path: '/',
                query: [ 'q1' => 'v1', 'q2' => 'v2', 'q3' => 'v3' ],
                fragment: null,
            ),

            'http+o2o.o-o~o_o://user:secret@host' => new Uri(
                scheme: 'http+o2o.o-o~o_o',
                user: 'user',
                password: 'secret',
                host: 'host',
                port: null,
                path: '',
                query: [],
                fragment: null,
            ),

            'http://ooo.fefas-1403u~s_e!r)$&\'(**+,;=:secret@host' => new Uri(
                scheme: 'http',
                user: 'ooo.fefas-1403u~s_e!r)$&\'(**+,;=',
                password: 'secret',
                host: 'host',
                port: null,
                path: '',
                query: [],
                fragment: null,
            ),

            'http://user:se123!$&\'()*+.,;:=123asd@host' => new Uri(
                scheme: 'http',
                user: 'user',
                password: 'se123!$&\'()*+.,;:=123asd',
                host: 'host',
                port: null,
                path: '',
                query: [],
                fragment: null,
            ),

            'http://user:secret@fefas-14_03~ooo.dev' => new Uri(
                scheme: 'http',
                user: 'user',
                password: 'secret',
                host: 'fefas-14_03~ooo.dev',
                port: null,
                path: '',
                query: [],
                fragment: null,
            ),

            'http://user:secret@host/path' => new Uri(
                scheme: 'http',
                user: 'user',
                password: 'secret',
                host: 'host',
                port: null,
                path: '/path',
                query: [],
                fragment: null,
            ),
        ];
    }

    public function validUrisDataProvider(): Generator
    {
        foreach ($this->validUris() as $uri => $expected) {
            yield $uri => [$expected, $uri];
        }
    }

    /**
     * @test
     * @dataProvider validUrisDataProvider
     */
    public function parseUriProperly(Uri $expected, string $uri): void
    {
        $this->assertEquals($expected, Uri::fromString($uri));
    }

    public function invalidUris(): array
    {
        return [
            '1tel:+49666',
            '1scheme://host',
            'scheme://host:666asd',
        ];
    }

    public function invalidUrisDataProvider(): Generator
    {
        foreach ($this->invalidUris() as $uri) {
            yield $uri => [$uri];
        }
    }

    /**
     * @test
     * @dataProvider invalidUrisDataProvider
     */
    public function throwInvalidArgumentInvalidUriProvided(string $uri): void
    {
        $this->expectException(InvalidArgument::class);
        $this->expectExceptionMessage("Invalid URI provided: $uri");

        Uri::fromString($uri);
    }
}
