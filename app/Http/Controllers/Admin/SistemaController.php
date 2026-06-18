<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SistemaController extends Controller
{
    public function index()
    {
        return view('admin.sistema.monitor');
    }

    public function datos()
    {
        $datos = [
            'cpu'      => $this->getCpu(),
            'memoria'  => $this->getMemoria(),
            'procesos' => $this->getProcesos(),
            'uptime'   => $this->getUptime(),
            'disco'    => $this->getDisco(),
            'red'      => $this->getRed(),
        ];

        return response()->json($datos);
    }

    private function getCpu()
    {
        $stat1 = file('/proc/stat');
        usleep(200000);
        $stat2 = file('/proc/stat');

        $cpu1 = explode(' ', preg_replace('/\s+/', ' ', trim($stat1[0])));
        $cpu2 = explode(' ', preg_replace('/\s+/', ' ', trim($stat2[0])));

        $idle1  = $cpu1[4];
        $idle2  = $cpu2[4];
        $total1 = array_sum(array_slice($cpu1, 1));
        $total2 = array_sum(array_slice($cpu2, 1));

        $totalDiff = $total2 - $total1;
        $idleDiff  = $idle2 - $idle1;

        $uso = $totalDiff > 0
            ? round((($totalDiff - $idleDiff) / $totalDiff) * 100, 1)
            : 0;

        return [
            'uso'     => $uso,
            'nucleos' => (int) shell_exec('nproc'),
        ];
    }

    private function getMemoria()
    {
        $meminfo = file('/proc/meminfo');
        $datos   = [];

        foreach ($meminfo as $linea) {
            if (preg_match('/^(\w+):\s+(\d+)/', $linea, $m)) {
                $datos[$m[1]] = (int) $m[2];
            }
        }

        $total      = round($datos['MemTotal'] / 1024, 1);
        $disponible = round($datos['MemAvailable'] / 1024, 1);
        $usado      = round($total - $disponible, 1);
        $porcentaje = round(($usado / $total) * 100, 1);

        return [
            'total'      => $total,
            'usado'      => $usado,
            'disponible' => $disponible,
            'porcentaje' => $porcentaje,
        ];
    }

    private function getProcesos()
    {
        $loadavg = file_get_contents('/proc/loadavg');
        $partes  = explode(' ', $loadavg);

        return [
            'load_1'  => $partes[0],
            'load_5'  => $partes[1],
            'load_15' => $partes[2],
            'total'   => trim(explode('/', $partes[3])[1]),
            'activos' => explode('/', $partes[3])[0],
        ];
    }

    private function getUptime()
    {
        $uptime   = file_get_contents('/proc/uptime');
        $segundos = (int) explode(' ', $uptime)[0];

        $dias    = floor($segundos / 86400);
        $horas   = floor(($segundos % 86400) / 3600);
        $minutos = floor(($segundos % 3600) / 60);

        return [
            'segundos' => $segundos,
            'formato'  => "{$dias}d {$horas}h {$minutos}m",
        ];
    }

    private function getDisco()
    {
        $total = disk_total_space('/');
        $libre = disk_free_space('/');
        $usado = $total - $libre;

        return [
            'total'      => round($total / 1073741824, 1),
            'usado'      => round($usado / 1073741824, 1),
            'libre'      => round($libre / 1073741824, 1),
            'porcentaje' => round(($usado / $total) * 100, 1),
        ];
    }

    private function getRed()
    {
        $netdev = file('/proc/net/dev');

        foreach ($netdev as $linea) {
            // Busca cualquier interfaz que no sea lo (loopback)
            if (preg_match('/^\s*(eth\d+|enp\w+|ens\w+|eno\w+)/', $linea, $match)) {
                $partes = preg_split('/\s+/', trim($linea));
                return [
                    'interfaz' => rtrim($partes[0], ':'),
                    'rx'       => round((int)$partes[1] / 1048576, 2),
                    'tx'       => round((int)$partes[9] / 1048576, 2),
                ];
            }
        }

        return ['interfaz' => 'N/A', 'rx' => 0, 'tx' => 0];
    }
}