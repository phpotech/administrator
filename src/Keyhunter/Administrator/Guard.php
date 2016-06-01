<?php


namespace Keyhunter\Administrator;

interface Guard
{
    /**
     * Check permissions
     *
     * @param $permission
     * @return bool
     */
    public function isPermissionGranted($permission);
}