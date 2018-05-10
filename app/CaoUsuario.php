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
    protected $primaryKey = 'co_usuario';
    public $incrementing = false;
    public $timestamps = false;

    public function permissaoSistema()
    {
        return $this->hasMany('App\PermissaoSistema', 'co_usuario', 'co_usuario');
    }

    public static function consultor()
    {
    	return self::join('permissao_sistema', 'cao_usuario.co_usuario', '=', 'permissao_sistema.co_usuario')
        	->where('co_sistema', 1)
    		->where('in_ativo', 'S')
    		->whereIn('co_tipo_usuario', [0, 1, 2])
    	->get();
    }

    public static function getRelatorio($usersId, $start, $end)
    {
    	$end = date('Y-m-d', strtotime('+1 month', strtotime($end . '-01')));
    	//dd($start . "-01", $end);
    	return self::select('cao_usuario.co_usuario', 'cao_usuario.no_usuario', 'cao_fatura.data_emissao', 'cao_fatura.valor', 'cao_fatura.total_imp_inc', 'cao_salario.brut_salario', 'cao_fatura.comissao_cn')
    		->leftJoin('cao_os', 'cao_usuario.co_usuario', '=', 'cao_os.co_usuario')
    		->join('cao_fatura', 'cao_os.co_os', '=', 'cao_fatura.co_os')
    		->join('cao_salario', 'cao_salario.co_usuario', '=', 'cao_usuario.co_usuario')
    		->whereIn('cao_usuario.co_usuario', $usersId)
    		->where('cao_fatura.data_emissao', '>=', $start . "-01")
    		->where('cao_fatura.data_emissao', '<', $end)
    		->orderBy('cao_usuario.co_usuario','ASC')
    		->orderBy('cao_fatura.data_emissao','ASC');
    }

}