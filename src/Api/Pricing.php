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

use UpCloud\Entity\Pricing as PricingEntity;

/**
 * @author Shirleyson Kaisser <skaisser@gmail.com>
 */
class Pricing extends AbstractApi
{
    /**
     * @return PricingEntity
     */
    public function listPrices()
    {
        $pricing = $this->adapter->get(sprintf('%s/price', $this->endpoint));

        $pricing = json_decode($pricing);

        return new PricingEntity($pricing->pricing);
    }
}
