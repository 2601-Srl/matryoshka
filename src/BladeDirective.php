<?php

namespace _2601\Matryoshka;

use Exception;

class BladeDirective
{
    /**
     * The cache instance.
     *
     * @var RussianCaching
     */
    protected $cache;

    /**
     * A list of model cache keys.
     *
     * @param array $keys
     */
    protected $keys = [];

    /**
     * Create a new instance.
     *
     * @param RussianCaching $cache
     */
    public function __construct(RussianCaching $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Handle the @cache setup.
     *
     * @param mixed       $model
     * @param string|null $identifier
     */
    public function setUp($model, $identifier = null)
    {
        ob_start();

        $this->keys[] = $key = $this->normalizeKey($model, $identifier);

        return $this->cache->has($key);
    }

    /**
     * Handle the @endcache teardown.
     */
    public function tearDown()
    {
        return $this->cache->put(
            array_pop($this->keys),
            ob_get_clean()
        );
    }

    /**
     * Normalize the cache key.
     *
     * @param mixed       $item
     * @param string|null $identifier
     */
    protected function normalizeKey($item, $identifier = null)
    {
        // We'll try to use the item to calculate
        // the cache key, itself.
        if (is_object($item) && method_exists($item, 'getCacheKey')) {
            return $item->getCacheKey($identifier);
        }
    
        // If we're dealing with a collection, we'll
        // use a hashed version of its contents.
        if ($item instanceof \Illuminate\Support\Collection) {
            return $identifier.md5($item);
        }
    
        throw new Exception('Could not determine an appropriate cache key.');
    }
}
