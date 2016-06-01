<?php namespace Keyhunter\Administrator;

use Illuminate\Auth\EloquentUserProvider;
use Keyhunter\Administrator\Traits\CallableTrait;

class AuthUserProvider extends EloquentUserProvider {

    use CallableTrait;

    public function retrieveByCredentials(array $credentials)
    {
        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value)
        {
            if ( ! str_contains($key, 'password'))
            {
                // handle closures
                if (is_callable($value))
                {
                    $value = $this->callback($value);
                }

                if (is_array($value))
                {
                    $query->whereIn($key, $value);
                    continue;
                }

                $query->where($key, $value);
            }
        }

        return $query->first();
    }
}