<?php namespace Keyhunter\Administrator;

use Illuminate\Contracts\Auth\Guard;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest {

    protected $config;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

    /**
     * Get the validation rules that apply to the request.
     *
     * @param Application $application
     * @return array
     */
	public function rules(Application $application)
	{
        $this->config = $application['scaffold.config'];
        $identity     = $this->config->get('auth_identity', 'username');
        $credential   = $this->config->get('auth_credential', 'password');

		return [
			$identity   => 'required',
			$credential => 'required'
		];
	}

	protected function getRedirectUrl()
	{
        return $this->config->get('home_page', 'admin/members');
	}
}
