<?php namespace Keyhunter\Administrator\Model;

use Keyhunter\Administrator\Repository;
use Keyhunter\Translatable\HasTranslations;
use Keyhunter\Translatable\Translatable;

class Page extends Repository implements Translatable
{
    use HasTranslations;

    protected $translatedAttributes = ['title', 'body'];

    protected $fillable = ['slug', 'title', 'body', 'active'];

    protected $table = 'pages';
}
