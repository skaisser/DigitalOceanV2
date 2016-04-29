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

use UpCloud\Entity\Server as ServerEntity;
use UpCloud\Exception\HttpException;

/**
 * @author Shirleyson Kaisser <skaisser@gmail.com>
 */
class Server extends AbstractApi
{
    /**
     * @return ServerEntity[]
     */
    public function listServers()
    {
        $servers = $this->adapter->get(sprintf('%s/server', $this->endpoint));

        $servers = json_decode($servers);

        return array_map(function ($server) {
            return new ServerEntity($server);
        }, $servers->server);
    }

   

    /**
     * @param string $uuid
     *
     * @throws HttpException
     *
     * @return ServerEntity
     */
    public function getByUniqueId($uuid)
    {
        $server = $this->adapter->get(sprintf('%s/server/%s', $this->endpoint, $uuid));

        $server = json_decode($server);

        return new ServerEntity($server->server);
    }

    /**
     * @param array|string $names
     * @param string       $region
     * @param string       $size
     * @param string|int   $image
     * @param bool         $backups
     * @param bool         $ipv6
     * @param bool         $privateNetworking
     * @param int[]        $sshKeys
     * @param string       $userData
     *
     * @throws HttpException
     *
     * @return DropletEntity|null
     */
    public function create($names, $region, $size, $image, $backups = false, $ipv6 = false, $privateNetworking = false, array $sshKeys = [], $userData = '')
    {
        $data = is_array($names) ? ['names' => $names] : ['name' => $names];

        $data = array_merge($data, [
            'region' => $region,
            'size' => $size,
            'image' => $image,
            'backups' => $backups ? 'true' : 'false',
            'ipv6' => $ipv6 ? 'true' : 'false',
            'private_networking' => $privateNetworking ? 'true' : 'false',
        ]);

        if (0 < count($sshKeys)) {
            $data['ssh_keys'] = $sshKeys;
        }

        if (!empty($userData)) {
            $data['user_data'] = $userData;
        }

        $droplet = $this->adapter->post(sprintf('%s/droplets', $this->endpoint), $data);

        $droplet = json_decode($droplet);

        if (is_array($names)) {
            return array_map(function ($droplet) {
                return new DropletEntity($droplet);
            }, $droplet->droplets);
        }

        return new DropletEntity($droplet->droplet);
    }

    /**
     * @param int $uuid
     *
     * @throws HttpException
     */
    public function server($uuid)
    {
        $this->adapter->delete(sprintf('%s/server/%s', $this->endpoint, $uuid));
    }

    /**
     * @param int $id
     *
     * @throws HttpException
     *
     * @return KernelEntity[]
     */
    public function getAvailableKernels($id)
    {
        $kernels = $this->adapter->get(sprintf('%s/droplets/%d/kernels', $this->endpoint, $id));

        $kernels = json_decode($kernels);

        $this->meta = $this->extractMeta($kernels);

        return array_map(function ($kernel) {
            return new KernelEntity($kernel);
        }, $kernels->kernels);
    }

    /**
     * @param int $id
     *
     * @return ImageEntity[]
     */
    public function getSnapshots($id)
    {
        $snapshots = $this->adapter->get(sprintf('%s/droplets/%d/snapshots?per_page=%d', $this->endpoint, $id, 200));

        $snapshots = json_decode($snapshots);

        $this->meta = $this->extractMeta($snapshots);

        return array_map(function ($snapshot) {
            $snapshot = new ImageEntity($snapshot);

            return $snapshot;
        }, $snapshots->snapshots);
    }

    /**
     * @param int $id
     *
     * @return ImageEntity[]
     */
    public function getBackups($id)
    {
        $backups = $this->adapter->get(sprintf('%s/droplets/%d/backups?per_page=%d', $this->endpoint, $id, 200));

        $backups = json_decode($backups);

        $this->meta = $this->extractMeta($backups);

        return array_map(function ($backup) {
            return new ImageEntity($backup);
        }, $backups->backups);
    }

    /**
     * @param int $id
     *
     * @return ActionEntity[]
     */
    public function getActions($id)
    {
        $actions = $this->adapter->get(sprintf('%s/droplets/%d/actions?per_page=%d', $this->endpoint, $id, 200));

        $actions = json_decode($actions);

        $this->meta = $this->extractMeta($actions);

        return array_map(function ($action) {
            return new ActionEntity($action);
        }, $actions->actions);
    }

    /**
     * @param int $id
     * @param int $actionId
     *
     * @return ActionEntity
     */
    public function getActionById($id, $actionId)
    {
        $action = $this->adapter->get(sprintf('%s/droplets/%d/actions/%d', $this->endpoint, $id, $actionId));

        $action = json_decode($action);

        return new ActionEntity($action->action);
    }

    /**
     * @param int $id
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function reboot($id)
    {
        return $this->executeAction($id, ['type' => 'reboot']);
    }

    /**
     * @param int $id
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function powerCycle($id)
    {
        return $this->executeAction($id, ['type' => 'power_cycle']);
    }

    /**
     * @param int $id
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function shutdown($id)
    {
        return $this->executeAction($id, ['type' => 'shutdown']);
    }

    /**
     * @param int $id
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function powerOff($id)
    {
        return $this->executeAction($id, ['type' => 'power_off']);
    }

    /**
     * @param int $id
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function powerOn($id)
    {
        return $this->executeAction($id, ['type' => 'power_on']);
    }

    /**
     * @param int $id
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function passwordReset($id)
    {
        return $this->executeAction($id, ['type' => 'password_reset']);
    }

    /**
     * @param int    $id
     * @param string $size
     * @param bool   $disk
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function resize($id, $size, $disk = true)
    {
        return $this->executeAction($id, ['type' => 'resize', 'size' => $size, 'disk' => $disk ? 'true' : 'false']);
    }

    /**
     * @param int $id
     * @param int $image
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function restore($id, $image)
    {
        return $this->executeAction($id, ['type' => 'restore', 'image' => $image]);
    }

    /**
     * @param int        $id
     * @param int|string $image
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function rebuild($id, $image)
    {
        return $this->executeAction($id, ['type' => 'rebuild', 'image' => $image]);
    }

    /**
     * @param int    $id
     * @param string $name
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function rename($id, $name)
    {
        return $this->executeAction($id, ['type' => 'rename', 'name' => $name]);
    }

    /**
     * @param int $id
     * @param int $kernel
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function changeKernel($id, $kernel)
    {
        return $this->executeAction($id, ['type' => 'change_kernel', 'kernel' => $kernel]);
    }

    /**
     * @param int $id
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function enableIpv6($id)
    {
        return $this->executeAction($id, ['type' => 'enable_ipv6']);
    }

    /**
     * @param int $id
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function enableBackups($id)
    {
        return $this->executeAction($id, ['type' => 'enable_backups']);
    }

    /**
     * @param int $id
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function disableBackups($id)
    {
        return $this->executeAction($id, ['type' => 'disable_backups']);
    }

    /**
     * @param int $id
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function enablePrivateNetworking($id)
    {
        return $this->executeAction($id, ['type' => 'enable_private_networking']);
    }

    /**
     * @param int    $id
     * @param string $name
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function snapshot($id, $name)
    {
        return $this->executeAction($id, ['type' => 'snapshot', 'name' => $name]);
    }

    /**
     * @param int   $id
     * @param array $options
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    private function executeAction($id, array $options)
    {
        $action = $this->adapter->post(sprintf('%s/droplets/%d/actions', $this->endpoint, $id), $options);

        $action = json_decode($action);

        return new ActionEntity($action->action);
    }
}
