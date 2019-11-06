<?php

namespace DorsetDigital\EdgeCache\Core;

interface EdgeCacher
{
    public function expirePage($url);
}