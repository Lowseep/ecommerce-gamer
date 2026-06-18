<?php

namespace App\Services;

class MonitorSistemaService
{
    // CONCEPTO SO: Leer directamente del sistema de archivos /proc del kernel
    public function obtenerDatos(): array
    {
        return [
            'cpu'      => $this->getCpu(),
            'memoria'  => $this->getMemoria(),
            'disco'    => $this->getDisco(),
            'uptime'   => $this->getUptime(),
            'procesos' => $this->getProcesos(),
            'red'      => $this->getRed(),
        ];
    }

    private function getCpu(): array
    {
        $stat1 = file('/proc/stat');
        usleep(200000);
        $stat2 = file('/proc/stat');

        $cpu1 = explode(' ', preg_replace('/\s+/', ' ', trim($stat1[0])));
        $cpu2 = explode(' ', preg_replace('/\s+/', ' ', trim($stat2[0])));

        $total1 = array_sum(array_slice($cpu1, 1));
        $total2 = array_sum(array_slice($cpu2, 1));
        $idle1  = $cpu1[4];
        $idle2  = $cpu2[4];

        $totalDiff = $total2 - $total1;
        $idleDiff  = $idle2 - $idle1;

        $uso = $totalDiff > 0
            ? round((($totalDiff - $idleDiff) / $totalDiff) * 100, 1)
            : 0;

        return ['uso' => $uso, 'nucleos' => (int) shell_exec('nproc')];
    }

    private function getMemoria(): array
    {
        $meminfo = [];
        foreach (file('/proc/meminfo') as $linea) {
            if (preg_match('/^(\w+):\s+(\d+)/', $linea, $m)) {
                $meminfo[$m[1]] = (int) $m[2];
            }
        }

        $total      = round($meminfo['MemTotal'] / 1024, 1);
        $disponible = round($meminfo['MemAvailable'] / 1024, 1);
        $usado      = round($total - $disponible, 1);

        return [
            'total'      => $total,
            'usado'      => $usado,
            'disponible' => $disponible,
            'porcentaje' => round(($usado / $total) * 100, 1),
        ];
    }

    private function getDisco(): array
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

    private function getUptime(): array
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

    private function getProcesos(): array
    {
        $loadavg = file_get_contents('/proc/loadavg');
        $partes  = explode(' ', $loadavg);

        return [
            'load_1'  => $partes[0],
            'load_5'  => $partes[1],
            'load_15' => $partes[2],
            'activos' => explode('/', $partes[3])[0],
            'total'   => explode('/', $partes[3])[1],
        ];
    }

    private function getRed(): array
    {
        $netdev = file('/proc/net/dev');
        $iface  = [];

        foreach ($netdev as $linea) {
            if (str_contains($linea, 'eth0') || str_contains($linea, 'enp')) {
                $partes       = preg_split('/\s+/', trim($linea));
                $iface['rx']  = round((int)$partes[1] / 1048576, 2);
                $iface['tx']  = round((int)$partes[9] / 1048576, 2);
                break;
            }
        }

        return $iface ?: ['rx' => 0, 'tx' => 0];
    }
}