<?php namespace Keyhunter\Administrator;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest {

    /**
     * @var
     */
    private $application;

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

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
     * @return array
     */
	public function rules()
	{
        if ($rules = $this->application['scaffold.module']->get('rules', [])) {
            $id = \Route::input('id');

            if (is_callable($rules))
                return $rules($id);

            $rules = array_map(function($rule) use ($id) {
                if (is_callable($rule)) {
                    $rule = $rule($id);
                }
                return $rule;
            }, $rules);
        }

        return $rules;
	}
}
