<?php

namespace App\Models\SqlSrv;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Trabajador extends Model
{
    protected $connection = 'sqlsrv';

    protected $table = 'dbo.Trabajador';

    public $incrementing = false;

    public static function getInfoCuenta($rut)
    {
        return DB::connection('sqlsrv')
            ->table('dbo.Trabajador as t')
            ->select(
                't.RutTrabajador as rut',
                't.NumeroCuentaBancaria as numero_cuenta',
                'b.Nombre as banco'
            )
            ->join('dbo.Banco as b', [
                'b.IdEmpresa' => 't.IdEmpresa',
                'b.IdBanco' => 't.IdBanco'
            ])
            ->where('t.RutTrabajador', $rut)
            ->first();
    }

    public static function getObtenerTrabajador($rut, $empresaId)
    {
        $t = DB::connection('sqlsrv')
            ->table('dbo.Trabajador as t')
            ->join('dbo.Contratos as c', [
                'c.IdEmpresa' => 't.IdEmpresa',
                'c.IdTrabajador' => 't.IdTrabajador'
            ])
            ->where('t.RutTrabajador', $rut)
            ->where('c.IndicadorVigencia', true)
            ->whereIn('t.IdEmpresa', [$empresaId])
            ->first();

        if (!$t) {
            return null;
        }

        return [
            'rut' => $rut,
            'code' => $t->IdTrabajador,
            'nombre' => $t->Nombre,
            'apellido_paterno' => $t->ApellidoPaterno,
            'apellido_materno' => $t->ApellidoMaterno,
            'fecha_nacimiento' => Carbon::parse($t->FechaNacimiento)->format('Y-m-d'),
            'sexo' => $t->Sexo,
            'email' => $t->Mail,
            'tipo_zona_id' => $t->IdTipoZona,
            'nombre_zona' => $t->NombreZona,
            'tipo_via_id' => $t->IdTipoVia,
            'nombre_via' => $t->NombreVia,
            'direccion' => $t->Direccion,
            'distrito_id' => $t->COD_COM,
            'estado_civil_id' => $t->EstadoCivil,
            'nacionalidad_id' => $t->IdNacionalidad,
            'empresa_id' => $empresaId,
            'numero_cuenta' => $t->NumeroCuentaBancaria,
            'zona_labor_id' => $t->IdZonaLabores
        ];
    }
}
