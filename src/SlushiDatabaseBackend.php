<?php

namespace Drupal\slushi_cache;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\DatabaseBackend;
use Drupal\Core\Site\Settings;

/**
 * Defines a database cache backend with a maximum lifetime.
 */
class SlushiDatabaseBackend extends DatabaseBackend {

  /**
   * {@inheritdoc}
   */
  public function setMultiple(array $items) {
    foreach ($items as &$item) {
      if (!isset($item['expire']) || $item['expire'] === Cache::PERMANENT) {
        // Enforce a maximum lifetime.
        $item['expire'] = REQUEST_TIME + Settings::get('slushi_cache_melt_time', 86400);
      }
    }
    parent::setMultiple($items);
  }

}
