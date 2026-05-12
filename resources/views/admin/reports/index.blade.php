@extends('layouts.admin')
@section('title','Báo cáo')
@section('breadcrumb') <h2>Báo cáo doanh thu</h2> @endsection

@section('content')
<div class="card">
  <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px">
    <div class="btn">Tổng đơn: <strong>{{ number_format($totalOrders) }}</strong></div>
    <div class="btn">7 ngày gần nhất: <strong>{{ number_format($revenueByDate->sum('total_revenue'),0,',','.') }}₫</strong></div>
    <div class="btn">12 tháng: <strong>{{ number_format($revenueByMonth->sum('total_revenue'),0,',','.') }}₫</strong></div>
  </div>

  <h3 style="margin-top:18px">Doanh thu theo danh mục</h3>
  <table>
    <thead><tr><th>Category ID</th><th>Tổng doanh thu</th><th>Tổng SL</th></tr></thead>
    <tbody>
      @forelse($categoryRevenue as $r)
        <tr>
          <td>{{ $r->category_id }}</td>
          <td>{{ number_format((float)$r->total_revenue,0,',','.') }}₫</td>
          <td>{{ (int)$r->total_qty }}</td>
        </tr>
      @empty
        <tr><td colspan="3">Không có dữ liệu</td></tr>
      @endforelse
    </tbody>
  </table>

  <h3 style="margin-top:18px">Doanh thu theo ngày</h3>
  <table>
    <thead><tr><th>Ngày</th><th>Doanh thu</th><th>Số đơn</th></tr></thead>
    <tbody>
      @foreach($revenueByDate as $r)
        <tr>
          <td>{{ \Carbon\Carbon::parse($r->date)->format('d/m/Y') }}</td>
          <td>{{ number_format((float)$r->total_revenue,0,',','.') }}₫</td>
          <td>{{ (int)$r->order_count }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <h3 style="margin-top:18px">Doanh thu theo tháng</h3>
  <table>
    <thead><tr><th>Tháng (YYYY-MM)</th><th>Doanh thu</th><th>Số đơn</th></tr></thead>
    <tbody>
      @foreach($revenueByMonth as $r)
        <tr>
          <td>{{ $r->month }}</td>
          <td>{{ number_format((float)$r->total_revenue,0,',','.') }}₫</td>
          <td>{{ (int)$r->order_count }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <h3 style="margin-top:18px">Doanh thu theo năm</h3>
  <table>
    <thead><tr><th>Năm</th><th>Doanh thu</th><th>Số đơn</th></tr></thead>
    <tbody>
      @foreach($revenueByYear as $r)
        <tr>
          <td>{{ $r->year }}</td>
          <td>{{ number_format((float)$r->total_revenue,0,',','.') }}₫</td>
          <td>{{ (int)$r->order_count }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
