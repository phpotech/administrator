<?php namespace Keyhunter\Administrator;

use Keyhunter\Administrator\Traits\CallableTrait;

class PermissionChecker implements Guard
{
    use CallableTrait;

    /**
     * Check permissions
     *
     * @param $permission
     * @return bool
     */
    public function isPermissionGranted($permission)
    {
        if (is_callable($permission))
        {
            return $this->callback($permission);
        }

        return (bool) $permission;
    }
}