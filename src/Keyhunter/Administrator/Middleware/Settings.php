<?php namespace Keyhunter\Administrator\Middleware;

use App;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\UrlGenerator;
use Keyhunter\Administrator\PermissionChecker;

class Settings
{
    /**
     * @var PermissionChecker
     */
    private $guard;
    /**
     * @var Application
     */
    private $application;


    /**
     * Module constructor.
     *
     * @param PermissionChecker $guard
     * @param Application       $application
     */
    public function __construct(PermissionChecker $guard, Application $application)
    {
        $this->application = $application;
        $this->guard = $guard;
    }

    public function handle($request, Closure $next)
    {
        $module = $this->application->make('scaffold.module', [$this->application->make('scaffold.config')->get('settings_path')]);

        /**
         * Check module permission
         */
        if ($module && ! $this->guard->isPermissionGranted($module->get('permission', true))) {
            return response()->view('administrator::errors.403', [], 403);
        }

        return $next($request);
    }
}