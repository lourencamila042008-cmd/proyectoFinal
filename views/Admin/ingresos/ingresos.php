<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

// ── TOTALES GENERALES ──────────────────────────────────────────────
$totalIngresos = $conn->query("
    SELECT COALESCE(SUM(d.subtotal), 0) AS total
    FROM facturas f
    JOIN detallefactura d ON f.id_detallefactura = d.id_detallefactura
    WHERE f.estado = 'pagada'
")->fetch_assoc()['total'];

$totalPendiente = $conn->query("
    SELECT COALESCE(SUM(d.subtotal), 0) AS total
    FROM facturas f
    JOIN detallefactura d ON f.id_detallefactura = d.id_detallefactura
    WHERE f.estado = 'pendiente'
")->fetch_assoc()['total'];

$totalFacturas = $conn->query("SELECT COUNT(*) AS total FROM facturas")->fetch_assoc()['total'];

$totalClientes = $conn->query("SELECT COUNT(*) AS total FROM clientes")->fetch_assoc()['total'];

// ── INGRESOS POR MES (último año) ─────────────────────────────────
$ingresosMes = $conn->query("
    SELECT 
        DATE_FORMAT(f.fecha, '%Y-%m') AS mes,
        DATE_FORMAT(f.fecha, '%b %Y') AS mes_label,
        COALESCE(SUM(d.subtotal), 0) AS total
    FROM facturas f
    JOIN detallefactura d ON f.id_detallefactura = d.id_detallefactura
    WHERE f.estado = 'pagada'
      AND f.fecha >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY DATE_FORMAT(f.fecha, '%Y-%m')
    ORDER BY mes ASC
");

$meses = [];
$totalesMes = [];
while ($row = $ingresosMes->fetch_assoc()) {
    $meses[] = $row['mes_label'];
    $totalesMes[] = (float)$row['total'];
}

// ── VENTAS POR ESTADO ─────────────────────────────────────────────
$estadosRes = $conn->query("
    SELECT estado, COUNT(*) AS cantidad
    FROM facturas
    GROUP BY estado
");
$estadosLabels = [];
$estadosCantidad = [];
while ($row = $estadosRes->fetch_assoc()) {
    $estadosLabels[] = ucfirst($row['estado']);
    $estadosCantidad[] = (int)$row['cantidad'];
}

// ── TOP 5 PRODUCTOS MÁS VENDIDOS ──────────────────────────────────
$topProductos = $conn->query("
    SELECT p.nombre, SUM(d.cantidad) AS total_vendido, SUM(d.subtotal) AS total_ingresos
    FROM detallefactura d
    JOIN productos p ON d.id_productos = p.id_productos
    JOIN facturas f ON d.id_facturas = f.id_facturas
    WHERE f.estado = 'pagada'
    GROUP BY d.id_productos
    ORDER BY total_vendido DESC
    LIMIT 5
");

// ── ÚLTIMAS 8 FACTURAS ────────────────────────────────────────────
$ultimasFacturas = $conn->query("
    SELECT f.id_facturas, f.estado, f.fecha,
           c.nombre AS nombre_cliente,
           p.nombre AS nombre_producto,
           d.cantidad, d.subtotal
    FROM facturas f
    JOIN clientes c ON f.id_clientes = c.id_clientes
    JOIN detallefactura d ON f.id_detallefactura = d.id_detallefactura
    JOIN productos p ON d.id_productos = p.id_productos
    ORDER BY f.fecha DESC
    LIMIT 8
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ingresos – InvoicePro</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --bg:        #0b0f1a;
    --surface:   #111827;
    --surface2:  #1a2235;
    --border:    #1e2d45;
    --accent:    #3b82f6;
    --accent2:   #10b981;
    --accent3:   #f59e0b;
    --danger:    #ef4444;
    --text:      #f1f5f9;
    --muted:     #64748b;
    --font:      'DM Sans', sans-serif;
    --mono:      'DM Mono', monospace;
}

body {
    font-family: var(--font);
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
    padding: 32px 24px;
}

/* ── HEADER ── */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 16px;
}

.header-left h1 {
    font-size: 28px;
    font-weight: 700;
    letter-spacing: -0.5px;
}

.header-left p {
    color: var(--muted);
    font-size: 14px;
    margin-top: 4px;
}

.header-right {
    display: flex;
    gap: 10px;
    align-items: center;
}

.badge {
    background: var(--surface2);
    border: 1px solid var(--border);
    color: var(--muted);
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-family: var(--mono);
}

.btn-back {
    background: var(--surface2);
    border: 1px solid var(--border);
    color: var(--text);
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: 0.2s;
}
.btn-back:hover { border-color: var(--accent); color: var(--accent); }

/* ── TARJETAS KPI ── */
.kpis {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.kpi {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 24px;
    position: relative;
    overflow: hidden;
    transition: transform 0.2s, border-color 0.2s;
}

.kpi:hover {
    transform: translateY(-2px);
    border-color: var(--accent);
}

.kpi::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 14px 14px 0 0;
}

.kpi.green::before  { background: var(--accent2); }
.kpi.blue::before   { background: var(--accent); }
.kpi.yellow::before { background: var(--accent3); }
.kpi.red::before    { background: var(--danger); }

.kpi-icon {
    font-size: 28px;
    margin-bottom: 12px;
}

.kpi-label {
    font-size: 12px;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.8px;
    font-weight: 600;
    margin-bottom: 6px;
}

.kpi-value {
    font-size: 26px;
    font-weight: 700;
    font-family: var(--mono);
    letter-spacing: -1px;
}

.kpi.green  .kpi-value { color: var(--accent2); }
.kpi.blue   .kpi-value { color: var(--accent); }
.kpi.yellow .kpi-value { color: var(--accent3); }
.kpi.red    .kpi-value { color: var(--danger); }

/* ── GRID GRÁFICOS ── */
.charts-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 16px;
    margin-bottom: 24px;
}

.card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 24px;
}

.card-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-title span { color: var(--text); }

/* ── BOTTOM GRID ── */
.bottom-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

/* ── TABLA ── */
table {
    width: 100%;
    border-collapse: collapse;
}

thead tr {
    border-bottom: 1px solid var(--border);
}

th {
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 0 0 12px 0;
    text-align: left;
}

td {
    padding: 12px 0;
    font-size: 13px;
    border-bottom: 1px solid var(--border);
    color: var(--text);
}

tr:last-child td { border-bottom: none; }

tr:hover td { color: var(--accent); }

.estado-badge {
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.pagada    { background: rgba(16,185,129,0.15); color: var(--accent2); }
.pendiente { background: rgba(245,158,11,0.15);  color: var(--accent3); }
.anulada   { background: rgba(239,68,68,0.15);   color: var(--danger); }

/* ── TOP PRODUCTOS ── */
.producto-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
}

.producto-row:last-child { border-bottom: none; }

.producto-rank {
    font-family: var(--mono);
    font-size: 12px;
    color: var(--muted);
    min-width: 20px;
}

.producto-info { flex: 1; }

.producto-nombre {
    font-size: 13px;
    font-weight: 500;
}

.producto-vendido {
    font-size: 11px;
    color: var(--muted);
    margin-top: 2px;
}

.producto-total {
    font-family: var(--mono);
    font-size: 13px;
    color: var(--accent2);
    font-weight: 500;
}

.bar-container {
    height: 4px;
    background: var(--border);
    border-radius: 2px;
    margin-top: 6px;
}

.bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--accent), var(--accent2));
    border-radius: 2px;
    transition: width 1s ease;
}

/* ── RESPONSIVE ── */
@media (max-width: 1024px) {
    .kpis { grid-template-columns: repeat(2, 1fr); }
    .charts-grid { grid-template-columns: 1fr; }
    .bottom-grid { grid-template-columns: 1fr; }
}

@media (max-width: 600px) {
    body { padding: 20px 16px; }
    .kpis { grid-template-columns: 1fr 1fr; }
    .kpi { padding: 16px; }
    .kpi-value { font-size: 20px; }
    .header-left h1 { font-size: 22px; }
}

@media (max-width: 400px) {
    .kpis { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="header-left">
        <h1>📊 Ingresos</h1>
        <p>Resumen financiero de InvoicePro</p>
    </div>
    <div class="header-right">
        <span class="badge"><?= date('d M Y') ?></span>
        <a href="../dashboard_admin.php" class="btn-back">← Volver</a>
    </div>
</div>

<!-- KPIs -->
<div class="kpis">
    <div class="kpi green">
        <div class="kpi-icon">💰</div>
        <div class="kpi-label">Ingresos Totales</div>
        <div class="kpi-value">$<?= number_format($totalIngresos, 0, ',', '.') ?></div>
    </div>
    <div class="kpi yellow">
        <div class="kpi-icon">⏳</div>
        <div class="kpi-label">Por Cobrar</div>
        <div class="kpi-value">$<?= number_format($totalPendiente, 0, ',', '.') ?></div>
    </div>
    <div class="kpi blue">
        <div class="kpi-icon">🧾</div>
        <div class="kpi-label">Total Facturas</div>
        <div class="kpi-value"><?= number_format($totalFacturas, 0, ',', '.') ?></div>
    </div>
    <div class="kpi red">
        <div class="kpi-icon">👥</div>
        <div class="kpi-label">Clientes</div>
        <div class="kpi-value"><?= number_format($totalClientes, 0, ',', '.') ?></div>
    </div>
</div>

<!-- GRÁFICOS PRINCIPALES -->
<div class="charts-grid">
    <div class="card">
        <div class="card-title">📈 <span>Ingresos por mes</span></div>
        <canvas id="chartMeses" height="100"></canvas>
    </div>
    <div class="card">
        <div class="card-title">🥧 <span>Facturas por estado</span></div>
        <canvas id="chartEstados" height="200"></canvas>
    </div>
</div>

<!-- BOTTOM: TABLA + TOP PRODUCTOS -->
<div class="bottom-grid">
    <div class="card">
        <div class="card-title">🕐 <span>Últimas facturas</span></div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th>Total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
            <?php while($f = $ultimasFacturas->fetch_assoc()): ?>
                <tr>
                    <td style="font-family:var(--mono); color:var(--muted)"><?= $f['id_facturas'] ?></td>
                    <td><?= htmlspecialchars($f['nombre_cliente']) ?></td>
                    <td style="color:var(--muted)"><?= htmlspecialchars($f['nombre_producto']) ?></td>
                    <td style="font-family:var(--mono); color:var(--accent2)">$<?= number_format($f['subtotal'], 0, ',', '.') ?></td>
                    <td><span class="estado-badge <?= $f['estado'] ?>"><?= ucfirst($f['estado']) ?></span></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="card-title">🏆 <span>Top productos vendidos</span></div>
        <?php
        $topData = [];
        while ($p = $topProductos->fetch_assoc()) $topData[] = $p;
        $maxVendido = !empty($topData) ? max(array_column($topData, 'total_vendido')) : 1;
        foreach ($topData as $i => $p):
            $pct = $maxVendido > 0 ? ($p['total_vendido'] / $maxVendido) * 100 : 0;
        ?>
        <div class="producto-row">
            <div class="producto-rank"><?= $i+1 ?></div>
            <div class="producto-info">
                <div class="producto-nombre"><?= htmlspecialchars($p['nombre']) ?></div>
                <div class="producto-vendido"><?= $p['total_vendido'] ?> unidades vendidas</div>
                <div class="bar-container">
                    <div class="bar-fill" style="width:<?= $pct ?>%"></div>
                </div>
            </div>
            <div class="producto-total">$<?= number_format($p['total_ingresos'], 0, ',', '.') ?></div>
        </div>
        <?php endforeach; ?>
        <?php if (empty($topData)): ?>
            <p style="color:var(--muted); font-size:13px; text-align:center; padding:20px 0">No hay datos aún</p>
        <?php endif; ?>
    </div>
</div>

<script>
// ── GRÁFICO BARRAS: INGRESOS POR MES ──
const meses = <?= json_encode($meses) ?>;
const totalesMes = <?= json_encode($totalesMes) ?>;

new Chart(document.getElementById('chartMeses'), {
    type: 'bar',
    data: {
        labels: meses,
        datasets: [{
            label: 'Ingresos',
            data: totalesMes,
            backgroundColor: 'rgba(59,130,246,0.7)',
            borderColor: '#3b82f6',
            borderWidth: 2,
            borderRadius: 6,
            hoverBackgroundColor: '#10b981',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' $' + ctx.parsed.y.toLocaleString('es-CO')
                }
            }
        },
        scales: {
            x: {
                ticks: { color: '#64748b', font: { size: 11 } },
                grid: { color: '#1e2d45' }
            },
            y: {
                ticks: {
                    color: '#64748b',
                    font: { size: 11 },
                    callback: v => '$' + v.toLocaleString('es-CO')
                },
                grid: { color: '#1e2d45' }
            }
        }
    }
});

// ── GRÁFICO DONA: ESTADOS ──
const estadosLabels = <?= json_encode($estadosLabels) ?>;
const estadosCantidad = <?= json_encode($estadosCantidad) ?>;

new Chart(document.getElementById('chartEstados'), {
    type: 'doughnut',
    data: {
        labels: estadosLabels,
        datasets: [{
            data: estadosCantidad,
            backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#64748b'],
            borderColor: '#111827',
            borderWidth: 3,
            hoverOffset: 8
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#94a3b8',
                    font: { size: 12 },
                    padding: 16,
                    usePointStyle: true
                }
            }
        },
        cutout: '65%'
    }
});
</script>

</body>
</html>