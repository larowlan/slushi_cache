<?php

namespace Drupal\Tests\slushi_cache\Kernel;

use Drupal\Core\Cache\Cache;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\KernelTests\KernelTestBase;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Tests slushi cache operation.
 *
 * @group slushi_cache
 */
class SlushiCacheTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['slushi_cache'];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * {@inheritdoc}
   */
  protected function bootEnvironment() {
    parent::bootEnvironment();
    $this->setSetting('cache', [
      'bins' => [
        'render' => 'cache.backend.slushi',
      ],
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function register(ContainerBuilder $container) {
    parent::register($container);
    // Change container to database cache backends.
    $container
      ->register('cache_factory', 'Drupal\Core\Cache\CacheFactory')
      ->addArgument(new Reference('settings'))
      ->addMethodCall('setContainer', [new Reference('service_container')]);

    // Change container to use database lock backends.
    $container
      ->register('lock', 'Drupal\Core\Lock\DatabaseLockBackend')
      ->addArgument(new Reference('database'));
  }

  /**
   * Tests cache operation.
   */
  public function testSlushiCache() {
    /** @var \Drupal\Core\Cache\CacheBackendInterface $cache_bin */
    $cache_bin = $this->container->get('cache.render');

    // Cache as permanent, expire should be auto set.
    $cache_bin->set('slushi_test', 'stuff');
    $return = $cache_bin->get('slushi_test');
    $this->assertNotEquals(Cache::PERMANENT, $return->expire);
    $this->assertEquals(REQUEST_TIME + 86400, $return->expire);

    // Cache with expiry, expire should be retained.
    $cache_bin->set('slushi_test', 'stuff', REQUEST_TIME + 1000);
    $return = $cache_bin->get('slushi_test');
    $this->assertEquals(REQUEST_TIME + 1000, $return->expire);
  }

}
