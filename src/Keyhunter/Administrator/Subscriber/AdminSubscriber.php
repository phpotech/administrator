<?php namespace Keyhunter\Administrator\Subscriber;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Log;
use Session;

class AdminSubscriber {

    /**
     * @var Request
     */
    private $request;
    /**
     * @var Application
     */
    private $application;

    public function __construct(Request $request, Application $application)
    {
        $this->request = $request;
        $this->application = $application;
    }

    /**
     * @param Guard $guard
     */
    public function onUserLogin(Guard $guard)
    {
        /**
         * @var $user \stdClass|\App\User
         */
        $user = $guard->user();
        $time = Carbon::now();
        $ip   = $this->request->getClientIp();

        Session::set('admin.last_login_at', $time);

        $this->log("Admin [$user->name] logged in at [$time] from [$ip]");
    }

    public function onUserLogout(Guard $guard)
    {
        /**
         * @var $user \stdClass|\App\User
         */
        $user = $guard->user();
        $diff = Carbon::now()->diffInSeconds(Session::get('admin.last_login_at', Carbon::now()));

        Session::remove('admin');

        $this->log("Admin [$user->name] logged out. Session length [$diff]");
    }

    public function onPerformAction(Guard $guard, $action, $arguments = null)
    {
        /**
         * @var $user \stdClass|\App\User
         */
        $user = $guard->user();
        $time = Carbon::now();

        $this->log("User [{$user->name}] did perform action [{$action}] at [$time]");

        if ($arguments)
        {
            if (is_array($arguments))
            {
                $arguments = serialize($arguments);
            }
            $this->log($arguments);
        }
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen('admin.login', '\Keyhunter\Administrator\Subscriber\AdminSubscriber@onUserLogin');

        $events->listen('admin.logout', '\Keyhunter\Administrator\Subscriber\AdminSubscriber@onUserLogout');

        $events->listen('admin.performAction', '\Keyhunter\Administrator\Subscriber\AdminSubscriber@onPerformAction');
    }

    protected function log($message)
    {
        if ($this->application['scaffold.config']->get('log_actions', false))
        {
            Log::debug($message);
        }
    }
}