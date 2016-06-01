<?php namespace Keyhunter\Administrator\Model;

use Keyhunter\Administrator\Repository;

class Language extends Repository
{
    protected $fillable = ['slug', 'title', 'active', 'rank'];

    public $timestamps = false;

}