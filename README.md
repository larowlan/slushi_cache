# Slushi Cache
A Drupal database cache backend where permanent is really only semi-permanent.

# Installation
Download and enable the module.

# Configuration
In your settings.php customise the lifetime like so:

```php
$settings['slushi_cache_melt_time'] = 86400;
// Setup the bins where you want permanent entries to melt.
$settings['cache']['bins']['render'] = 'cache.backend.slushi';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.slushi';
```

Where the lifetime is the actual maximum permanent age in seconds.

You can find the cache bin names in your `core.services.yml`.
