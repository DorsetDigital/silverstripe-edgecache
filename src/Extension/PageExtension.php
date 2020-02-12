<?php

namespace DorsetDigital\EdgeCacher;

use SilverStripe\Core\Extension;
use DorsetDigital\EdgeCache\Client\Cloudflare;

class PageExtension extends Extension
{
    /**
     * @config
     * @var bool
     */
    private static $cache_enabled = false;

    /**
     * @config
     * @var int
     */
    private static $cache_time = 0;

    /**
     * Gets a cache key for the page
     * @return string
     */
    public function getCacheKey()
    {
        $key = $this->owner->ClassName . '-' . $this->owner->ID;
        return hash('ripemd128', $key);
    }

    public function onAfterPublish()
    {
        $this->purgePage();
    }

    public function onAfterWrite()
    {
        if (!$this->owner->isPublished()) {
            $this->purgePage();
        }
    }

    private function purgePage()
    {
        if ($this->owner->config()->get('cache_enabled') !== true) {
            return true;
        }
        $client = Cloudflare::create();
        $link = $this->owner->Link();
        $client->expirePage($link);
    }
}
