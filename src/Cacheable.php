<?php

namespace _2601\Matryoshka;

trait Cacheable
{
    /**
     * Calculate a unique cache key for the model instance.
     */
    public function getCacheKey($identifier)
    {
        return sprintf(
            "%s/%s/%s-%s",
            get_class($this),
            $identifier,
            $this->getKey(),
            $this->updated_at->timestamp
        );
    }
}
