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

use UpCloud\Entity\Plan as PlanEntity;

/**
 * @author Shirleyson Kaisser <skaisser@gmail.com>
 */
class Plan extends AbstractApi
{
    /**
     * @return PlanEntity[]
     */
    public function getAll()
    {
        $plans = $this->adapter->get(sprintf('%s/plan', $this->endpoint, 200));

        $plans = json_decode($plans);

        $this->extractMeta($plans);

        return array_map(function ($plan) {
            return new PlanEntity($plan);
        }, $plans->plan);
    }
}
