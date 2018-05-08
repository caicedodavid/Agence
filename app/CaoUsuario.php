<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use \DateTimeZone;
use \DateTime;
use \DateInterval;
use \App\Calendar;

class CaoUsuario extends Model
{
    protected $table = 'cao_usuario';

    public function permissaoSistema()
    {
        return $this->hasOne('App\PermissaoSistema', 'co_usuario', 'co_usuario');
    }

}