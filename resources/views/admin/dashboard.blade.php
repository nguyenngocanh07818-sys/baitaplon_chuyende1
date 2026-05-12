@extends('layouts.admin')

@section('title', 'Dashboard')

@section('meta')
    <meta name="description" content="Bảng điều khiển quản trị - Xem tổng quan doanh thu, đơn hàng và báo cáo theo danh mục, ngày, tháng, năm.">
    <meta name="keywords" content="dashboard, admin, doanh thu, báo cáo, đơn hàng, thống kê">
    <meta name="robots" content="index, follow">
@endsection

@section('breadcrumb')
    <strong>Bảng điều khiển</strong>
@endsection

@push('styles')
<style>
.hk-card {
    box-shadow: 0 8px 20px rgba(255, 126, 184, 0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hk-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(255, 126, 184, 0.25);
}
.hk-btn {
    @apply px-5 py-3 text-sm font-semibold text-white;
    background: #ff7eb8;
    border-radius: 9999px;
    transition: transform 0.3s ease, background 0.3s ease;
}
.hk-btn:hover {
    background: #ff5fa7;
    transform: translateY(-2px);
}
.hk-stat-card {
    @apply p-4 rounded-lg bg-gradient-to-br from-cyan-50 to-pink-50;
    transition: transform 0.3s ease;
}
.hk-stat-card:hover {
    transform: scale(1.02);
}
canvas {
    max-height: 350px !important;
    width: 100% !important;
}
.chart-container {
    position: relative;
    padding: 1rem;
    background: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}
.chart-title {
    @apply text-lg font-semibold text-slate-800 mb-4;
}
</style>
@endpush

@section('content')
<div class="hk-card bg-white rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4">Bảng điều khiển</h1>
    <p class="mb-6 text-slate-600">Xin chào, <strong>{{ auth()->user()->name }}</strong> — Tổng quan thống kê doanh thu và đơn hàng.</p>

    <!-- Tổng quan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="hk-stat-card">
            <h3 class="text-base font-semibold text-cyan-900">Tổng đơn hàng</h3>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($totalOrders) }}</p>
        </div>
        <div class="hk-stat-card">
            <h3 class="text-base font-semibold text-cyan-900">Doanh thu 7 ngày</h3>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($revenueByDate->sum('total_revenue'), 0, ',', '.') }}₫</p>
        </div>
        <div class="hk-stat-card">
            <h3 class="text-base font-semibold text-cyan-900">Doanh thu 12 tháng</h3>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($revenueByMonth->sum('total_revenue'), 0, ',', '.') }}₫</p>
        </div>
    </div>

    <!-- Biểu đồ doanh thu theo danh mục (Cột) -->
    <div class="hk-card chart-container mb-6">
        <h3 class="chart-title">Doanh thu theo danh mục</h3>
        <canvas id="categoryChart"></canvas>
    </div>

    <!-- Biểu đồ doanh thu theo ngày (Đường) -->
    <div class="hk-card chart-container mb-6">
        <h3 class="chart-title">Doanh thu theo ngày (7 ngày gần nhất)</h3>
        <canvas id="dateChart"></canvas>
    </div>

    <!-- Biểu đồ tỷ lệ doanh thu theo danh mục (Tròn) -->
    <div class="hk-card chart-container">
        <h3 class="chart-title">Tỷ lệ doanh thu theo danh mục</h3>
        <canvas id="pieChart"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dữ liệu từ Blade
const categoryRevenue = @json($categoryRevenue);
const revenueByDate = @json($revenueByDate);

// Palette màu hiện đại
const colorPalette = [
    'rgba(255, 126, 184, 0.7)', // Hồng chính (#ff7eb8)
    'rgba(75, 192, 192, 0.7)',  // Xanh cyan
    'rgba(255, 206, 86, 0.7)',  // Vàng
    'rgba(54, 162, 235, 0.7)',  // Xanh dương
    'rgba(153, 102, 255, 0.7)', // Tím
];

// Hàm định dạng tiền tệ
const formatCurrency = (value) => {
    return value.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
};

// Biểu đồ cột: Doanh thu theo danh mục
const categoryChart = new Chart(document.getElementById('categoryChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: categoryRevenue.map(item => item.category_name || 'Không xác định'),
        datasets: [{
            label: 'Doanh thu',
            data: categoryRevenue.map(item => parseFloat(item.total_revenue)),
            backgroundColor: colorPalette[0],
            borderColor: 'rgba(255, 126, 184, 1)',
            borderWidth: 1,
            borderRadius: 8,
            barThickness: 30,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            duration: 1000,
            easing: 'easeOutQuart',
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                ticks: {
                    callback: formatCurrency,
                    font: { size: 12 },
                },
            },
            x: {
                grid: { display: false },
                ticks: { font: { size: 12 } },
            },
        },
        plugins: {
            legend: { display: false },
            title: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleFont: { size: 14 },
                bodyFont: { size: 12 },
                callbacks: {
                    label: (context) => `Doanh thu: ${formatCurrency(context.raw)}`,
                },
            },
        },
    },
});

// Biểu đồ đường: Doanh thu theo ngày
const dateChart = new Chart(document.getElementById('dateChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: revenueByDate.map(item => new Date(item.date).toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit' })),
        datasets: [{
            label: 'Doanh thu',
            data: revenueByDate.map(item => parseFloat(item.total_revenue)),
            fill: {
                target: 'origin',
                above: 'rgba(75, 192, 192, 0.2)',
            },
            borderColor: colorPalette[1],
            backgroundColor: colorPalette[1],
            tension: 0.3,
            pointRadius: 5,
            pointHoverRadius: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            duration: 1000,
            easing: 'easeOutQuart',
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                ticks: {
                    callback: formatCurrency,
                    font: { size: 12 },
                },
            },
            x: {
                grid: { display: false },
                ticks: { font: { size: 12 } },
            },
        },
        plugins: {
            legend: { display: false },
            title: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleFont: { size: 14 },
                bodyFont: { size: 12 },
                callbacks: {
                    label: (context) => `Doanh thu: ${formatCurrency(context.raw)}`,
                },
            },
        },
    },
});

// Biểu đồ tròn: Tỷ lệ doanh thu theo danh mục
const pieChart = new Chart(document.getElementById('pieChart').getContext('2d'), {
    type: 'pie',
    data: {
        labels: categoryRevenue.map(item => item.category_name || 'Không xác định'),
        datasets: [{
            data: categoryRevenue.map(item => parseFloat(item.total_revenue)),
            backgroundColor: colorPalette,
            borderColor: '#fff',
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            duration: 1000,
            easing: 'easeOutQuart',
        },
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    font: { size: 12 },
                    padding: 20,
                },
            },
            title: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleFont: { size: 14 },
                bodyFont: { size: 12 },
                callbacks: {
                    label: (context) => {
                        const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                        const percentage = ((context.raw / total) * 100).toFixed(1);
                        return `${context.label}: ${formatCurrency(context.raw)} (${percentage}%)`;
                    },
                },
            },
        },
    },
});
</script>
@endpush