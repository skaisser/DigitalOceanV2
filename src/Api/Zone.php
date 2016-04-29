<?php

/*
 * This file is part of the UpCloud library.
 *
 * (c) Shirleyson Kaisser <skaisser@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UpCloud\Api;

use UpCloud\Entity\Zone as ZoneEntity;

/**
 * @author Shirleyson Kaisser <skaisser@gmail.com>
 */
class Zone extends AbstractApi
{
    /**
     * @return ZoneEntity[]
     */
    public function getAll()
    {
        $zones = $this->adapter->get(sprintf('%s/zone', $this->endpoint, 200));

        $zones = json_decode($zones);

        $this->extractMeta($zones);

        return array_map(function ($zone) {
            return new ZoneEntity($zone);
        }, $zones->zone);
    }
}
