<?php

namespace DorsetDigital\EdgeCache\Core;

use DorsetDigital\EdgeCache\Client\Cloudflare;

class EdgeFactory
{
    /**
     * @param string $client
     * @return mixed
     * @throws \Exception
     */
    public static function buildFor($client)
    {
        switch (strtolower($client)) {
            case 'cloudflare':
                return Cloudflare::create();

            default:
                throw new \Exception(_t(__CLASS__.'.noclient', 'No valid edge client defined'));
        }
    }
}