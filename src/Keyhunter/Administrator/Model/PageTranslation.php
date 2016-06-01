<?php namespace Keyhunter\Administrator\Model;

use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model {

    public $timestamps = false;

    protected $fillable = ['title', 'body'];

}
