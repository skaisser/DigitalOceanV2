<?php

/*
 * This file is part of the UpCloud library.
 *
 * (c) Shirleyson Kaisser <skaisser@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UpCloud\Entity;

/**
 * @author Shirleyson Kaisser <skaisser@gmail.com>
 */
final class Plan extends AbstractEntity
{
   /**
     * @var int
     */
    public $core_number;


    /**
     * @var int
     */
    public $memory_amount;


    /**
     * @var string
     */
    public $name;


    /**
     * @var int
     */
    public $public_traffic_out;


    /**
     * @var int
     */
    public $storage_size;


    /**
     * @var string
     */
    public $storage_tier;

    
}
