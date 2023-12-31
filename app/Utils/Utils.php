<?php

class Utils
{
    public static function GenerarCodigo()
    {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codigo = '';

        for ($i = 0; $i < 5; $i++)
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];

        return $codigo;
    }

    public static function DameUnEmpleado($sector)
    {
        $empleados = Empleado::ListarActivosPorSector($sector);
        $disponibles = array_filter($empleados, function ($empleado)
        {
            return $empleado;
        });

        if (count($disponibles) > 0)
        {
            $empleado = $disponibles[array_rand($disponibles)];
            return $empleado;
        }
        return NULL;
    }
}
