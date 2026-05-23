@extends('layouts.admin')

@section('title', 'Dashboard')
@section('meta')
    <meta name="description" content="Bảng điều khiển quản trị - Tổng quan doanh thu, đơn hàng và phân tích kinh doanh.">
    <meta name="keywords" content="dashboard, admin, doanh thu, phân tích, đơn hàng, thống kê">
    <meta name="robots" content="index, follow">
@endsection

@section('breadcrumb')
    <nav class="flex items-center gap-2 text-sm">
        <span class="text-slate-400"><i class="fas fa-home"></i></span>
        <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
        <span class="text-slate-600 font-medium">Bảng điều khiển</span>
    </nav>
@endsection

@section('content')
<div class="space-y-6" x-data="dashboardData()" x-init="init()">
    {{-- Welcome Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Xin chào, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="mt-1 text-sm text-slate-500">Đây là tổng quan về hoạt động kinh doanh của bạn.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-emerald-50 text-emerald-700 rounded-full border border-emerald-200">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                Đang hoạt động
            </span>
            <button class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                <i class="fas fa-download mr-2"></i>Xuất báo cáo
            </button>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Total Orders --}}
        <div class="bg-white rounded-xl p-6 border border-slate-200 hover:shadow-lg transition-all duration-300 group cursor-pointer">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-shopping-bag text-indigo-600 text-xl"></i>
                </div>
                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>12.5%
                </span>
            </div>
            <h3 class="text-sm font-medium text-slate-500 mb-1">Tổng đơn hàng</h3>
            <p class="text-3xl font-bold text-slate-800">{{ number_format($totalOrders) }}</p>
            <p class="text-xs text-slate-400 mt-2">So với tháng trước</p>
        </div>

        {{-- 7-Day Revenue --}}
        <div class="bg-white rounded-xl p-6 border border-slate-200 hover:shadow-lg transition-all duration-300 group cursor-pointer">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-violet-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-chart-line text-violet-600 text-xl"></i>
                </div>
                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>8.2%
                </span>
            </div>
            <h3 class="text-sm font-medium text-slate-500 mb-1">Doanh thu 7 ngày</h3>
            <p class="text-3xl font-bold text-slate-800">{{ number_format($revenueByDate->sum('total_revenue'), 0, ',', '.') }}₫</p>
            <p class="text-xs text-slate-400 mt-2">So với tuần trước</p>
        </div>

        {{-- Conversion Rate --}}
        <div class="bg-white rounded-xl p-6 border border-slate-200 hover:shadow-lg transition-all duration-300 group cursor-pointer">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-percentage text-amber-600 text-xl"></i>
                </div>
                <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">
                    <i class="fas fa-arrow-down text-xs mr-1"></i>0.5%
                </span>
            </div>
            <h3 class="text-sm font-medium text-slate-500 mb-1">Tỷ lệ chuyển đổi</h3>
            <p class="text-3xl font-bold text-slate-800">3.24%</p>
            <p class="text-xs text-slate-400 mt-2">So với hôm qua</p>
        </div>

        {{-- 12-Month Revenue --}}
        <div class="bg-white rounded-xl p-6 border border-slate-200 hover:shadow-lg transition-all duration-300 group cursor-pointer">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-calendar-check text-emerald-600 text-xl"></i>
                </div>
                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>22.4%
                </span>
            </div>
            <h3 class="text-sm font-medium text-slate-500 mb-1">Doanh thu 12 tháng</h3>
            <p class="text-3xl font-bold text-slate-800">{{ number_format($revenueByMonth->sum('total_revenue'), 0, ',', '.') }}₫</p>
            <p class="text-xs text-slate-400 mt-2">So với năm trước</p>
        </div>
    </div>

    {{-- Charts Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Bar Chart --}}
        <div class="lg:col-span-2 bg-white rounded-xl p-6 border border-slate-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">Doanh thu theo danh mục</h3>
                    <p class="text-sm text-slate-500">Tổng quan 30 ngày qua</p>
                </div>
            </div>
            <div class="h-80">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        {{-- Doughnut Chart --}}
        <div class="bg-white rounded-xl p-6 border border-slate-200">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-slate-800">Phân bổ danh mục</h3>
                <p class="text-sm text-slate-500">Tỷ trọng doanh thu</p>
            </div>
            <div class="h-72">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Line Chart --}}
    <div class="bg-white rounded-xl p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-slate-800">Xu hướng doanh thu</h3>
                <p class="text-sm text-slate-500">7 ngày gần nhất</p>
            </div>
        </div>
        <div class="h-80">
            <canvas id="dateChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function dashboardData() {
    return {
        init() {
            this.initCharts();
        },
        initCharts() {
            const categoryRevenue = @json($categoryRevenue);
            const revenueByDate = @json($revenueByDate);

            Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
            Chart.defaults.font.size = 12;

            const formatCurrency = (value) => {
                if (value >= 1_000_000_000) return (value / 1_000_000_000).toFixed(1) + 'B ₫';
                if (value >= 1_000_000) return (value / 1_000_000).toFixed(1) + 'M ₫';
                if (value >= 1_000) return (value / 1_000).toFixed(0) + 'K ₫';
                return value.toLocaleString('vi-VN') + '₫';
            };

            const indigo = '#6366f1';
            const gridColor = '#f1f5f9';

            // Category Bar Chart
            const categoryCtx = document.getElementById('categoryChart')?.getContext('2d');
            if (categoryCtx) {
                new Chart(categoryCtx, {
                    type: 'bar',
                    data: {
                        labels: categoryRevenue.map(item => item.category_name || 'Khác'),
                        datasets: [{
                            data: categoryRevenue.map(item => parseFloat(item.total_revenue)),
                            backgroundColor: [
                                'rgba(99, 102, 241, 0.8)',
                                'rgba(139, 92, 246, 0.8)',
                                'rgba(79, 70, 229, 0.7)',
                                'rgba(165, 180, 252, 0.8)',
                                'rgba(199, 210, 254, 0.9)',
                            ],
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                cornerRadius: 8,
                                callbacks: {
                                    label: (ctx) => ` ${formatCurrency(ctx.raw)}`,
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: gridColor },
                                ticks: { callback: (val) => formatCurrency(val) }
                            },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // Line Chart
            const dateCtx = document.getElementById('dateChart')?.getContext('2d');
            if (dateCtx && revenueByDate.length) {
                const gradient = dateCtx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
                gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

                new Chart(dateCtx, {
                    type: 'line',
                    data: {
                        labels: revenueByDate.map(item => {
                            const d = new Date(item.date);
                            return d.toLocaleDateString('vi-VN', { weekday: 'short', day: '2-digit' });
                        }),
                        datasets: [{
                            data: revenueByDate.map(item => parseFloat(item.total_revenue)),
                            borderColor: indigo,
                            backgroundColor: gradient,
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2,
                            pointBackgroundColor: indigo,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                cornerRadius: 8,
                                callbacks: {
                                    label: (ctx) => ` Doanh thu: ${formatCurrency(ctx.raw)}`,
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: gridColor },
                                ticks: { callback: (val) => formatCurrency(val) }
                            },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // Doughnut Chart
            const pieCtx = document.getElementById('pieChart')?.getContext('2d');
            if (pieCtx) {
                new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: categoryRevenue.map(item => item.category_name || 'Khác'),
                        datasets: [{
                            data: categoryRevenue.map(item => parseFloat(item.total_revenue)),
                            backgroundColor: [
                                'rgba(99, 102, 241, 0.8)',
                                'rgba(139, 92, 246, 0.8)',
                                'rgba(79, 70, 229, 0.7)',
                                'rgba(165, 180, 252, 0.8)',
                                'rgba(199, 210, 254, 0.9)',
                            ],
                            borderColor: '#fff',
                            borderWidth: 3,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 16,
                                    usePointStyle: true,
                                    font: { size: 11 }
                                }
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                cornerRadius: 8,
                                callbacks: {
                                    label: (ctx) => {
                                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                        const pct = ((ctx.raw / total) * 100).toFixed(1);
                                        return ` ${ctx.label}: ${formatCurrency(ctx.raw)} (${pct}%)`;
                                    }
                                }
                            }
                        },
                        cutout: '65%',
                    }
                });
            }
        }
    }
}
</script>
@endpush