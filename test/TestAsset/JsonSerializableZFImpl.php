<?php

/**
 * @see       https://github.com/laminas/laminas-json-server for the canonical source repository
 * @copyright https://github.com/laminas/laminas-json-server/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-json-server/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Json\Server\TestAsset;

use Laminas\Stdlib\JsonSerializable;

class JsonSerializableLaminasImpl implements JsonSerializable
{
    public function jsonSerialize()
    {
        return [__FUNCTION__];
    }
}
