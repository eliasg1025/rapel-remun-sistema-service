<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Liquidaciones extends Model
{
    protected $table = 'liquidaciones';

    public $incrementing = false;

    public $timestamps = false;

    public $fillable = ['id', 'finiquito_id', 'rut', 'mes', 'ano', 'monto', 'empresa_id', 'fecha_emision'];

    public static function get(array $fechas, int $estado, int $empresa_id)
    {
        return DB::table('liquidaciones as l')
            ->select(
                'l.id', 'l.rut', 'l.nombre', 'l.apellido_paterno', 'l.apellido_materno',
                'l.mes', 'l.ano', 'l.monto', 'l.estado', 'e.shortname as empresa', 'l.banco', 'l.numero_cuenta',
                DB::raw('DATE_FORMAT(l.fecha_hora_marca_firmado, "%d/%m/%Y") fecha_firmado'),
                DB::raw('DATE_FORMAT(l.fecha_hora_marca_para_pago, "%d/%m/%Y") fecha_para_pago'),
                DB::raw('DATE_FORMAT(l.fecha_hora_marca_pagado, "%d/%m/%Y") fecha_pagado'),
                DB::raw('DATE_FORMAT(l.fecha_hora_marca_pagado, "%d/%m/%Y") fecha_pagado'),
                DB::raw('DATE_FORMAT(l.fecha_hora_marca_rechazado, "%d/%m/%Y") fecha_rechazado'),
                DB::raw('DATE_FORMAT(l.fecha_pago, "%d/%m/%Y") fecha_pago')
            )
            ->join('empresas as e', 'e.id', '=', 'l.empresa_id')
            ->where('l.estado', $estado)
            ->when($empresa_id !== 0, function($query) use($empresa_id) {
                $query->where('l.empresa_id', $empresa_id);
            })
            ->when($estado === 0, function($query) use ($fechas) {
                $query->whereBetween('l.fecha_emision', [$fechas['desde'], $fechas['hasta']]);
            })
            ->when($estado === 1, function($query) use ($fechas) {
                $query->whereDate('l.fecha_hora_marca_firmado', '>=' , $fechas['desde'])
                    ->whereDate('l.fecha_hora_marca_firmado', '<=', $fechas['hasta']);
            })
            ->when($estado === 2, function($query) use ($fechas) {
                $query->whereBetween('l.fecha_pago', [$fechas['desde'], $fechas['hasta']]);
            })
            ->get();
    }

    public static function getByTrabajador($rut)
    {
        return DB::table('liquidaciones as l')
            ->select(
                'l.id as key', 'l.id', 'l.rut',
                'l.mes', 'l.ano', 'l.monto', 'l.estado', 'e.shortname as empresa', 'l.banco', 'l.numero_cuenta',
                DB::raw('DATE_FORMAT(l.fecha_hora_marca_firmado, "%d/%m/%Y %H:%i:%s") fecha_firmado'),
                DB::raw('DATE_FORMAT(l.fecha_pago, "%d/%m/%Y") fecha_pago')
            )
            ->join('empresas as e', 'e.id', '=', 'l.empresa_id')
            ->where('l.rut', $rut)
            ->get();
    }

    public static function getPagados($empresa_id, $fecha_pago, $rut='')
    {
        return DB::table('liquidaciones as l')
            ->select(
                'l.id as key', 'l.id', 'l.rut', 'l.nombre', 'l.apellido_paterno', 'l.apellido_materno',
                'l.mes', 'l.ano', 'l.monto', 'l.estado', 'e.shortname as empresa', 'l.banco', 'l.numero_cuenta',
                DB::raw('DATE_FORMAT(l.fecha_pago, "%d/%m/%Y") fecha_pago')
            )
            ->join('empresas as e', 'e.id', '=', 'l.empresa_id')
            ->whereIn('l.estado', [3, 4])
            ->where('l.fecha_pago', $fecha_pago)
            ->where('l.empresa_id', $empresa_id)
            ->orderBy('l.apellido_paterno', 'ASC')
            ->get();
    }

    public static function getRechazados($empresa_id = 9)
    {
        return DB::table('liquidaciones_rechazos as l')
            ->select(
                'l.id as key', 'l.id', 'l.rut', 'l.nombre', 'l.apellido_paterno', 'l.apellido_materno',
                'l.mes', 'l.ano', 'l.monto', 'e.shortname as empresa', 'l.banco', 'l.numero_cuenta',
                DB::raw('DATE_FORMAT(l.fecha_pago, "%d/%m/%Y") fecha_pago')
            )
            ->join('empresas as e', 'e.id', '=', 'l.empresa_id')
            ->where('l.empresa_id', $empresa_id)
            ->orderBy('l.apellido_paterno', 'ASC')
            ->get();
    }

    public static function toggleRechazo(int $tipo, $finiquitos)
    {
        try {
            if ($tipo === 1) {
                return DB::table('liquidaciones')
                    ->whereIn('id', $finiquitos)
                    ->update([
                        'estado' => 4,
                        'fecha_hora_marca_rechazado' => now()->toDateTimeString()
                    ]);
            } else {
                return DB::table('liquidaciones')
                    ->whereIn('id', $finiquitos)
                    ->update([
                        'estado' => 3,
                        'fecha_hora_marca_rechazado' => null
                    ]);
            }
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public static function forPayment(string $fecha, array $finiquitos)
    {
        try {
            return DB::table('liquidaciones')
                ->whereIn('id', $finiquitos)
                ->update([
                    'estado' => 2,
                    'fecha_pago' => date($fecha),
                    'fecha_hora_marca_para_pago' => now()->toDateTimeString()
                ]);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public static function massiveCreate(array $liquidaciones)
    {
        $count = 0;
        $total = sizeof($liquidaciones);
        $errors = [];

        foreach($liquidaciones as $liquidacion)
        {
            try {
                DB::table('liquidaciones')->updateOrInsert(
                    [
                        'id' => $liquidacion['IdLiquidacion']
                    ],
                    [
                        'finiquito_id' => $liquidacion['IdFiniquito'],
                        'rut' => $liquidacion['RutTrabajador'],
                        'nombre' => $liquidacion['Nombre'],
                        'apellido_paterno' => $liquidacion['ApellidoPaterno'],
                        'apellido_materno' => $liquidacion['ApellidoMaterno'],
                        'ano' => $liquidacion['Ano'],
                        'mes' => $liquidacion['Mes'],
                        'monto' => $liquidacion['MontoAPagar'],
                        'empresa_id' => $liquidacion['IdEmpresa'],
                        'fecha_emision' => date($liquidacion['FechaEmision']),
                        'banco' => $liquidacion['Banco'],
                        'numero_cuenta' => $liquidacion['NumeroCuentaBancaria']
                    ]
                );

                $count++;
            } catch (\Exception $e) {
                array_push($errors, [
                    'id' => $liquidacion['IdLiquidacion'],
                    'error' => $e->getMessage()
                ]);
            }
        }

        return [
            'total' => $total,
            'completados' => $count,
            'errores' => $errors
        ];
    }

    public static function marcarPagadoMasivo(array $finiquitos)
    {
        try {
            return DB::table('liquidaciones')
                ->whereIn('id', $finiquitos)
                ->update([
                    'estado' => 3,
                    'fecha_hora_marca_pagado' => now()->toDateTimeString()
                ]);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public static function terminarProceso(array $finiquitos)
    {
        DB::beginTransaction();
        try {
            foreach ($finiquitos as $finiquitosId) {
                $finiquito = Liquidaciones::where('id', $finiquitosId)->first();

                if ($finiquito->estado === 3) {
                    $finiquito->estado = 5;
                    $finiquito->fecha_hora_marca_archivado = now()->toDateTimeString();
                    $finiquito->save();
                } else {

                    $rechazo = new LiquidacionRechazo();
                    $rechazo->rut = $finiquito->rut;
                    $rechazo->nombre = $finiquito->nombre;
                    $rechazo->apellido_paterno = $finiquito->apellido_paterno;
                    $rechazo->apellido_materno = $finiquito->apellido_materno;
                    $rechazo->mes = $finiquito->mes;
                    $rechazo->ano = $finiquito->ano;
                    $rechazo->banco = $finiquito->banco;
                    $rechazo->numero_cuenta = $finiquito->numero_cuenta;
                    $rechazo->monto = $finiquito->monto;
                    $rechazo->fecha_pago = $finiquito->fecha_pago;
                    $rechazo->empresa_id = $finiquito->empresa_id;
                    $rechazo->liquidacion_id = $finiquitosId;
                    $rechazo->save();

                    $finiquito->estado = 1;
                    $finiquito->save();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage() . ' -- ' . $e->getLine()
            ];
        }
    }

    public static function montosPorEstado($empresa_id = 9)
    {
        return DB::table('liquidaciones as l')
            ->select(
                'e.shortname as empresa',
                DB::raw('round( sum(case l.estado when 5 then l.monto else 0 end), 2 ) as pagados'),
                DB::raw('round( sum(case l.estado when 2 then l.monto else 0 end), 2 ) as para_pago'),
                DB::raw('round( sum(case l.estado when 1 then l.monto else 0 end), 2 ) as firmados'),
                DB::raw('round( sum(case l.estado when 0 then l.monto else 1 end), 2 ) as pendiente'),
                DB::raw('round( sum(l.monto), 2 ) as total')
            )
            ->join('empresas as e', 'e.id', '=', 'l.empresa_id')
            ->where('l.empresa_id', [$empresa_id])
            ->groupBy('l.empresa_id')
            ->first();
    }
}
