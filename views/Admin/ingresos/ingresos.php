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
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
/* REEMPLAZA TODO TU <style> POR ESTE */

*,
*::before,
*::after{
    box-sizing:border-box;
    margin:0;
    padding:0;
}

:root{

    --bg:#f4f6f9;
    --surface:#ffffff;
    --surface2:#f8fafc;
    --border:#e2e8f0;

    --primary:#17345f;
    --primary-hover:#264c83;

    --green:#16a34a;
    --green-bg:#dcfce7;

    --blue:#2563eb;
    --blue-bg:#dbeafe;

    --orange:#ea580c;
    --orange-bg:#ffedd5;

    --red:#dc2626;
    --red-bg:#fee2e2;

    --text:#0f172a;
    --muted:#64748b;

    --font:'Inter', sans-serif;
}

body{
    font-family:var(--font);
    background:var(--bg);
    color:var(--text);
    min-height:100vh;
    padding:30px;
}

/* HEADER */

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:32px;
    flex-wrap:wrap;
    gap:16px;
}

.header-left h1{
    font-size:34px;
    font-weight:700;
    color:var(--text);
}

.header-left p{
    color:var(--muted);
    margin-top:6px;
    font-size:15px;
}

.header-right{
    display:flex;
    gap:12px;
    align-items:center;
}

.badge{
    background:white;
    border:1px solid var(--border);
    color:var(--muted);
    padding:12px 16px;
    border-radius:14px;
    font-size:13px;
    font-weight:600;
    box-shadow:0 2px 10px rgba(0,0,0,.04);
}

.btn-back{
    background:var(--primary);
    color:white;
    padding:12px 18px;
    border-radius:14px;
    text-decoration:none;
    font-size:14px;
    font-weight:600;
    transition:.3s;
}

.btn-back:hover{
    background:var(--primary-hover);
}

/* KPIs */

.kpis{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:18px;
    margin-bottom:24px;
}

.kpi{
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:24px;
    padding:26px;
    box-shadow:0 4px 18px rgba(0,0,0,.04);
    transition:.3s;
    position:relative;
    overflow:hidden;
}

.kpi:hover{
    transform:translateY(-3px);
}

.kpi::before{
    content:'';
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:5px;
}

.kpi.green::before{
    background:var(--green);
}

.kpi.yellow::before{
    background:var(--orange);
}

.kpi.blue::before{
    background:var(--blue);
}

.kpi.red::before{
    background:var(--red);
}

.kpi-icon{
    font-size:28px;
    margin-bottom:16px;
}

.kpi-label{
    font-size:13px;
    color:var(--muted);
    text-transform:uppercase;
    font-weight:600;
    letter-spacing:.5px;
    margin-bottom:8px;
}

.kpi-value{
    font-size:34px;
    font-weight:700;
}

.kpi.green .kpi-value{
    color:var(--green);
}

.kpi.yellow .kpi-value{
    color:var(--orange);
}

.kpi.blue .kpi-value{
    color:var(--blue);
}

.kpi.red .kpi-value{
    color:var(--red);
}

/* CHARTS */

.charts-grid{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:18px;
    margin-bottom:24px;
}

.card{
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:24px;
    padding:26px;
    box-shadow:0 4px 18px rgba(0,0,0,.04);
}

.card-title{
    font-size:16px;
    font-weight:700;
    margin-bottom:24px;
    color:var(--text);
    display:flex;
    align-items:center;
    gap:10px;
}

/* BOTTOM GRID */

.bottom-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:18px;
}

/* TABLE */

table{
    width:100%;
    border-collapse:collapse;
}

thead tr{
    border-bottom:1px solid var(--border);
}

th{
    padding-bottom:14px;
    text-align:left;
    font-size:12px;
    color:var(--muted);
    text-transform:uppercase;
    letter-spacing:.5px;
}

td{
    padding:16px 0;
    border-bottom:1px solid var(--border);
    font-size:14px;
    color:#334155;
}

tr:last-child td{
    border-bottom:none;
}

tbody tr:hover td{
    color:var(--primary);
}

/* BADGES */

.estado-badge{
    padding:7px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
}

.pagada{
    background:var(--green-bg);
    color:var(--green);
}

.pendiente{
    background:#fef3c7;
    color:#d97706;
}

.anulada{
    background:var(--red-bg);
    color:var(--red);
}

/* PRODUCTOS */

.producto-row{
    display:flex;
    align-items:center;
    gap:14px;
    padding:14px 0;
    border-bottom:1px solid var(--border);
}

.producto-row:last-child{
    border-bottom:none;
}

.producto-rank{
    width:34px;
    height:34px;
    background:var(--surface2);
    border-radius:10px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    color:var(--primary);
    font-size:13px;
}

.producto-info{
    flex:1;
}

.producto-nombre{
    font-size:14px;
    font-weight:600;
    color:var(--text);
}

.producto-vendido{
    font-size:12px;
    color:var(--muted);
    margin-top:4px;
}

.producto-total{
    font-size:14px;
    font-weight:700;
    color:var(--green);
}

.bar-container{
    height:6px;
    background:#e2e8f0;
    border-radius:999px;
    margin-top:10px;
    overflow:hidden;
}

.bar-fill{
    height:100%;
    background:linear-gradient(90deg,#17345f,#3b82f6);
    border-radius:999px;
}

/* RESPONSIVE */

@media(max-width:1100px){

    .kpis{
        grid-template-columns:repeat(2,1fr);
    }

    .charts-grid{
        grid-template-columns:1fr;
    }

    .bottom-grid{
        grid-template-columns:1fr;
    }
}

@media(max-width:700px){

    body{
        padding:20px;
    }

    .header{
        flex-direction:column;
        align-items:flex-start;
    }

    .kpis{
        grid-template-columns:1fr;
    }

    .kpi{
        padding:22px;
    }

    .kpi-value{
        font-size:28px;
    }

    .header-left h1{
        font-size:28px;
    }

    .card{
        padding:20px;
    }
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