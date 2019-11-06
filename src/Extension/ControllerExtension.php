<?php

namespace DorsetDigital\EdgeCache\Extension;

use DorsetDigital\EdgeCache\Core\EdgeFactory;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Extension;

class ControllerExtension extends Extension
{
    use Configurable;

    /**
     * @config
     * @var bool
     */
    private static $cache_enabled = true;

    /**
     * @config
     * @var int
     */
    private static $cache_time = 604800;

    /**
     * @config
     * @var string
     */
    private static $network;


    public function onAfterInit()
    {
        if ($this->owner->config()->get('cache_enabled') === true) {
            $response = $this->owner->getResponse();
            $response->addHeader('x-edgecache', 'enabled');
            $response->addHeader('x-edgecache-time', $this->owner->config()->get('cache_time'));
            $link = $this->owner->Link();
            $response->addHeader('x-edgecache-url', $link);
        }
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
        $client = EdgeFactory::buildFor($this->owner->config()->get('network'));
        $link = $this->owner->Link();
        $client->expirePage($link);
    }
}
