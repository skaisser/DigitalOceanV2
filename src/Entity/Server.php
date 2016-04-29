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
final class Server extends AbstractEntity
{
    /**
     * @var string
     */
    public $boot_order;


    /**
     * @var string
     */
    public $firewall;

    /**
     * @var int
     */
    public $host;

    /**
     * @var IpAddress[]
     */
    public $ip_addresses;

    /**
     * @var string
     */
    public $nic_model;

    /**
     * @var Storage[]
     */
    public $storage_devices;

    /**
     * @var string
     */
    public $timezone;

    /**
     * @var string
     */
    public $video_model;

    /**
     * @var string
     */
    public $vnc;

    /**
     * @var string
     */
    public $vnc_host;

    /**
     * @var string
     */
    public $vnc_password;

    /**
     * @var string
     */
    public $vnc_port;

    /**
     * @var string
     */
    public $zone;

   
    /**
     * @var string
     */
    public $core_number;

    /**
     * @var string
     */
    public $hostname;

    /**
     * @var int
     */
    public $licence;

    /**
     * @var string
     */
    public $memory_amount;

    /**
     * @var string
     */
    public $plan;

    /**
     * @var string
     */
    public $state;

    /**
     * @var Tags[]
     */
    public $tags;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $uuid;

    /**
     * @var Zone
     */
    public $zone;

    /**
     * @param array $parameters
     */
    public function build(array $parameters)
    {
        foreach ($parameters as $property => $value) {
            switch ($property) {
                case 'tags':
                    if (is_object($value)) {
                        $this->tags = new Tag($value);
                    }
                    unset($parameters[$property]);
                    break;

               
            }
        }

        parent::build($parameters);
    }
}
