<?php

/**
 * @see       https://github.com/laminas/laminas-json-server for the canonical source repository
 * @copyright https://github.com/laminas/laminas-json-server/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-json-server/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Json\Server;

use Laminas\Json\Server;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{
    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        $this->server = new Server\Server();
        $this->server->setClass(TestAsset\Foo::class, 'foo');
        $this->cacheFile = tempnam(sys_get_temp_dir(), 'zjs');

        // if (!is_writeable(__DIR__)) {
        if (! is_writable($this->cacheFile)) {
            $this->markTestSkipped('Cannot write test caches due to permissions');
        }

        if (file_exists($this->cacheFile)) {
            unlink($this->cacheFile);
        }
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    public function tearDown()
    {
        if (file_exists($this->cacheFile)) {
            unlink($this->cacheFile);
        }
    }

    public function testRetrievingSmdCacheShouldReturnFalseIfCacheDoesNotExist()
    {
        $this->assertFalse(Server\Cache::getSmd($this->cacheFile));
    }

    public function testSavingSmdCacheShouldReturnTrueOnSuccess()
    {
        $this->assertTrue(Server\Cache::saveSmd($this->cacheFile, $this->server));
    }

    public function testSavedCacheShouldMatchGeneratedCache()
    {
        $this->testSavingSmdCacheShouldReturnTrueOnSuccess();
        $json = $this->server->getServiceMap()->toJSON();
        $test = Server\Cache::getSmd($this->cacheFile);
        $this->assertSame($json, $test);
    }

    public function testDeletingSmdShouldReturnFalseOnFailure()
    {
        $this->assertFalse(Server\Cache::deleteSmd($this->cacheFile));
    }

    public function testDeletingSmdShouldReturnTrueOnSuccess()
    {
        $this->testSavingSmdCacheShouldReturnTrueOnSuccess();
        $this->assertTrue(Server\Cache::deleteSmd($this->cacheFile));
    }
}
