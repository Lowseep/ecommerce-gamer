#!/bin/bash
# ============================================================
# monitor.sh — Linux Kernel API Demo
# Lee /proc directamente para obtener métricas del SO
# CONCEPTO SO: Llamadas al sistema y sistema de archivos /proc
# ============================================================

echo "======================================"
echo "  MONITOR DEL SISTEMA - Fsociety 2026"
echo "  $(date '+%d/%m/%Y %H:%M:%S')"
echo "======================================"

# ── CPU desde /proc/stat ──────────────────
echo ""
echo "[ CPU ]"
CPU=$(grep '^cpu ' /proc/stat | awk '{uso=($2+$4)*100/($2+$3+$4+$5)} END {printf "%.1f%%", uso}')
echo "  Uso actual: $CPU"
echo "  Núcleos:    $(nproc)"

# ── Memoria desde /proc/meminfo ───────────
echo ""
echo "[ MEMORIA RAM ]"
TOTAL=$(grep MemTotal /proc/meminfo | awk '{printf "%.1f MB", $2/1024}')
DISPONIBLE=$(grep MemAvailable /proc/meminfo | awk '{printf "%.1f MB", $2/1024}')
USADO=$(grep -E "^MemTotal|^MemAvailable" /proc/meminfo | awk 'NR==1{t=$2} NR==2{printf "%.1f MB", (t-$2)/1024}')
echo "  Total:      $TOTAL"
echo "  Usado:      $USADO"
echo "  Disponible: $DISPONIBLE"

# ── Procesos desde /proc/loadavg ─────────
echo ""
echo "[ PROCESOS ]"
LOADAVG=$(cat /proc/loadavg)
LOAD1=$(echo $LOADAVG | awk '{print $1}')
LOAD5=$(echo $LOADAVG | awk '{print $2}')
LOAD15=$(echo $LOADAVG | awk '{print $3}')
PROCS=$(echo $LOADAVG | awk '{print $4}')
echo "  Carga 1m:   $LOAD1"
echo "  Carga 5m:   $LOAD5"
echo "  Carga 15m:  $LOAD15"
echo "  Procesos:   $PROCS"

# ── Uptime desde /proc/uptime ─────────────
echo ""
echo "[ UPTIME ]"
SEGUNDOS=$(cat /proc/uptime | awk '{print int($1)}')
DIAS=$((SEGUNDOS / 86400))
HORAS=$(( (SEGUNDOS % 86400) / 3600 ))
MINUTOS=$(( (SEGUNDOS % 3600) / 60 ))
echo "  Tiempo activo: ${DIAS}d ${HORAS}h ${MINUTOS}m"

# ── Disco ─────────────────────────────────
echo ""
echo "[ DISCO ]"
df -h / | awk 'NR==2 {printf "  Total: %s | Usado: %s | Libre: %s | Uso: %s\n", $2, $3, $4, $5}'

# ── Red desde /proc/net/dev ───────────────
echo ""
echo "[ RED ]"
cat /proc/net/dev | grep -E 'eth0|enp' | awk '{
    printf "  RX: %.2f MB | TX: %.2f MB\n", $2/1048576, $10/1048576
}'

# ── Servicios del sistema ─────────────────
echo ""
echo "[ SERVICIOS ]"
for servicio in nginx php8.3-fpm mariadb redis-server; do
    STATUS=$(systemctl is-active $servicio 2>/dev/null)
    if [ "$STATUS" = "active" ]; then
        echo "  ✔ $servicio: activo"
    else
        echo "  ✘ $servicio: inactivo"
    fi
done

echo ""
echo "======================================"