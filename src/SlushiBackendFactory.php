<?php

namespace Drupal\slushi_cache;

use Drupal\Core\Cache\DatabaseBackendFactory;

/**
 * Defines a database cache backend factory with a maximum age.
 */
class SlushiBackendFactory extends DatabaseBackendFactory {

  /**
   * {@inheritdoc}
   */
  function get($bin) {
    return new SlushiDatabaseBackend($this->connection, $this->checksumProvider, $bin);
  }

}
