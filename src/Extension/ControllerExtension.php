<?php
namespace DorsetDigital\EdgeCacher;

use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Extension;

class ControllerExtension extends Extension
{

    public function onAfterInit()
    {
        $request = $this->owner->getResponse();
        $page = $this->owner->dataRecord;
        if ($page) {
            $enabled = Config::inst()->get($page->ClassName, 'cache_enabled');
            if ($enabled === true) {
                $cacheTime = Config::inst()->get($page->ClassName, 'cache_time');
                $cacheKey = $page->getCacheKey();
                $request->addHeader('x-edgecache', 'enable');
                $request->addHeader('x-edgecache-time', $cacheTime);
                $request->addHeader('x-edgecache-key', $cacheKey);
            } else {
                $request->addHeader('x-edgecache', 'not active');
            }
        }
    }
}
