<?php

namespace Keyhunter\Administrator\Middleware;

use App;
use Closure;
use Keyhunter\Administrator\PermissionChecker;
use URL;

class Authenticate
{
    protected $settings;
    protected $loginUrl;
    /**
     * @var PermissionChecker
     */
    private $permissionChecker;

    /**
     * Authenticate constructor.
     *
     * @param PermissionChecker $permissionChecker
     */
    public function __construct(PermissionChecker $permissionChecker)
    {
        $this->settings = App::make('scaffold.config');
        $this->loginUrl = URL::to($this->settings->get('login_path', 'admin/login'));
        $this->permissionChecker = $permissionChecker;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 1. Check global permission
        if (! ($response = $this->permissionChecker->isPermissionGranted($this->settings->get('permission', false))))
        {
            return response()->redirectGuest($this->loginUrl)->with('redirect', $request->url());

            /**
             * When redirect is impossible, just stop the execution
             * @fallback
             */
            abort(403, "Permission denied");
        }

        if ($this->isResponseObject($response))
        {
            return $response;
        }

        if ($this->redirectReceived($response))
        {
            return $response->with('redirect', $request->url());
        }

        return $next($request);
    }

    /**
     * @param $response
     * @return bool
     */
    protected function isResponseObject($response)
    {
        return is_a($response, 'Illuminate\Http\JsonResponse') || is_a($response, 'Illuminate\Http\Response');
    }

    /**
     * @param $response
     * @return bool
     */
    protected function redirectReceived($response)
    {
        return is_a($response, 'Illuminate\\Http\\RedirectResponse');
    }
}
