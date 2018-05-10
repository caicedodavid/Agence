<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use \DateTimeZone;
use \DateTime;
use \DateInterval;
use \App\Calendar;

class PermissaoSistema extends Model
{
	protected $table = 'permissao_sistema';

	public static function valids()
    {
        return \App\PermissaoSistema::where('co_sistema', 1)
    			->where('in_ativo', 'S')
    			->whereIn('co_tipo_usuario', [1, 2, 3])->get();
    }
}