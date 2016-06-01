<?php

namespace Keyhunter\Administrator\Model;

use Keyhunter\Administrator\Repository;

class Role extends Repository
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'rank', 'active'];

    /**
     * @var string
     */
    protected $table = 'roles';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Retrieve only active languages
     *
     * @param $query
     * @return Language[]
     */
    public function scopeActive($query)
    {
        $query->where('active', '=', 1);
    }
}