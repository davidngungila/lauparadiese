@extends('admin.layouts.app')

@section('title', 'Finance Reports - ' . config('app.name'))

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="mb-0">
            <i class="ri-bar-chart-line me-2"></i>Finance Reports
          </h4>
          <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="window.print()">
              <i class="ri-printer-line me-1"></i>Print
            </button>
            <button class="btn btn-success" onclick="exportToExcel()">
              <i class="ri-file-excel-line me-1"></i>Export Excel
            </button>
            <button class="btn btn-danger" onclick="exportToPDF()">
              <i class="ri-file-pdf-line me-1"></i>Export PDF
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filters -->
  <div class="card mb-4">
    <div class="card-header">
      <h5 class="mb-0">Filters</h5>
    </div>
    <div class="card-body">
      <form method="GET" action="{{ route('admin.finance.revenue-reports') }}" id="filterForm">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Date From</label>
            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">Date To</label>
            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
          </div>
          <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
              <i class="ri-search-line me-1"></i>Generate Report
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Financial Summary Cards -->
  <div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card card-border-shadow-success">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-success">
                <i class="ri-money-dollar-circle-line"></i>
              </span>
            </div>
            <div>
              <h4 class="mb-0">${{ number_format($stats['total_revenue'], 2) }}</h4>
              <small class="text-muted">Total Revenue</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card card-border-shadow-danger">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-danger">
                <i class="ri-arrow-down-circle-line"></i>
              </span>
            </div>
            <div>
              <h4 class="mb-0">${{ number_format($stats['total_expenses'], 2) }}</h4>
              <small class="text-muted">Total Expenses</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card card-border-shadow-primary">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-primary">
                <i class="ri-trending-up-line"></i>
              </span>
            </div>
            <div>
              <h4 class="mb-0">${{ number_format($stats['profit'], 2) }}</h4>
              <small class="text-muted">Net Profit</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card card-border-shadow-info">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-info">
                <i class="ri-percent-line"></i>
              </span>
            </div>
            <div>
              <h4 class="mb-0">{{ number_format($stats['profit_margin'], 2) }}%</h4>
              <small class="text-muted">Profit Margin</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Transaction Statistics -->
  <div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card card-border-shadow-secondary">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-secondary">
                <i class="ri-file-list-line"></i>
              </span>
            </div>
            <div>
              <h4 class="mb-0">{{ number_format($stats['transaction_count']) }}</h4>
              <small class="text-muted">Transactions</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card card-border-shadow-warning">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-warning">
                <i class="ri-receipt-line"></i>
              </span>
            </div>
            <div>
              <h4 class="mb-0">{{ number_format($stats['expense_count']) }}</h4>
              <small class="text-muted">Expenses</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card card-border-shadow-info">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-info">
                <i class="ri-invoice-line"></i>
              </span>
            </div>
            <div>
              <h4 class="mb-0">{{ number_format($stats['invoice_count']) }}</h4>
              <small class="text-muted">Invoices</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card card-border-shadow-success">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-success">
                <i class="ri-calculator-line"></i>
              </span>
            </div>
            <div>
              <h4 class="mb-0">${{ number_format($stats['avg_transaction'], 2) }}</h4>
              <small class="text-muted">Avg Transaction</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts Row -->
  <div class="row mb-4">
    <!-- Revenue vs Expenses Chart -->
    <div class="col-lg-8 mb-4">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Revenue vs Expenses</h5>
          <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-outline-primary active" onclick="showDailyFinance()">Daily</button>
            <button type="button" class="btn btn-outline-primary" onclick="showMonthlyFinance()">Monthly</button>
          </div>
        </div>
        <div class="card-body">
          <div id="revenueExpensesChart" style="min-height: 350px;"></div>
        </div>
      </div>
    </div>

    <!-- Payment Methods Chart -->
    <div class="col-lg-4 mb-4">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Payment Methods</h5>
        </div>
        <div class="card-body">
          <div id="paymentMethodsChart" style="min-height: 350px;"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Revenue Status & Expense Categories -->
  <div class="row mb-4">
    <div class="col-lg-6 mb-4">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Revenue by Status</h5>
        </div>
        <div class="card-body">
          <div id="revenueStatusChart" style="min-height: 300px;"></div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 mb-4">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Expense Categories</h5>
        </div>
        <div class="card-body">
          <div id="expenseCategoriesChart" style="min-height: 300px;"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Detailed Tables -->
  <div class="row mb-4">
    <!-- Payment Methods Breakdown -->
    <div class="col-lg-6 mb-4">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Payment Methods Breakdown</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Method</th>
                  <th>Count</th>
                  <th>Amount</th>
                  <th>%</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $totalPaymentAmount = $paymentMethodBreakdown->sum('total');
                @endphp
                @forelse($paymentMethodBreakdown as $method)
                <tr>
                  <td>{{ ucfirst(str_replace('_', ' ', $method->payment_method)) }}</td>
                  <td><span class="badge bg-secondary">{{ $method->count }}</span></td>
                  <td><strong>${{ number_format($method->total, 2) }}</strong></td>
                  <td>
                    <div class="progress" style="height: 20px;">
                      <div class="progress-bar" role="progressbar" style="width: {{ ($method->total / max($totalPaymentAmount, 1)) * 100 }}%">
                        {{ number_format(($method->total / max($totalPaymentAmount, 1)) * 100, 1) }}%
                      </div>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="4" class="text-center text-muted">No data available</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Expense Categories -->
    <div class="col-lg-6 mb-4">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Expense Categories</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Category</th>
                  <th>Count</th>
                  <th>Amount</th>
                  <th>%</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $totalExpenseAmount = $expenseCategories->sum('total');
                @endphp
                @forelse($expenseCategories as $category)
                <tr>
                  <td>{{ ucfirst(str_replace('_', ' ', $category->expense_category)) }}</td>
                  <td><span class="badge bg-warning">{{ $category->count }}</span></td>
                  <td><strong>${{ number_format($category->total, 2) }}</strong></td>
                  <td>
                    <div class="progress" style="height: 20px;">
                      <div class="progress-bar bg-danger" role="progressbar" style="width: {{ ($category->total / max($totalExpenseAmount, 1)) * 100 }}%">
                        {{ number_format(($category->total / max($totalExpenseAmount, 1)) * 100, 1) }}%
                      </div>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="4" class="text-center text-muted">No data available</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Revenue & Expenses Tables -->
  <div class="row mb-4">
    <!-- Daily Revenue -->
    <div class="col-lg-6 mb-4">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Daily Revenue</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Transactions</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody>
                @forelse($revenue as $day)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</td>
                  <td><span class="badge bg-info">{{ $day->transaction_count }}</span></td>
                  <td><strong>${{ number_format($day->total, 2) }}</strong></td>
                </tr>
                @empty
                <tr>
                  <td colspan="3" class="text-center text-muted">No revenue data available</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Daily Expenses -->
    <div class="col-lg-6 mb-4">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Daily Expenses</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody>
                @forelse($expenses as $expense)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}</td>
                  <td><strong class="text-danger">${{ number_format($expense->total, 2) }}</strong></td>
                </tr>
                @empty
                <tr>
                  <td colspan="2" class="text-center text-muted">No expense data available</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Invoice Status -->
  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">Invoice Status Summary</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Status</th>
              <th>Count</th>
              <th>Total Amount</th>
            </tr>
          </thead>
          <tbody>
            @forelse($invoices as $invoice)
            <tr>
              <td>
                <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'pending' ? 'warning' : 'danger') }}">
                  {{ ucfirst($invoice->status) }}
                </span>
              </td>
              <td>{{ $invoice->count }}</td>
              <td><strong>${{ number_format($invoice->total, 2) }}</strong></td>
            </tr>
            @empty
            <tr>
              <td colspan="3" class="text-center text-muted">No invoice data available</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.44.0/dist/apexcharts.css" />
<style>
  @media print {
    .btn, .card-header .d-flex, .card-header .btn-group {
      display: none !important;
    }
    .card {
      border: none;
      box-shadow: none;
    }
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.44.0/dist/apexcharts.min.js"></script>
<script>
  // Revenue vs Expenses Chart
  const dailyRevenue = @json($revenue);
  const dailyExpenses = @json($expenses);
  const monthlyRevenue = @json($monthlyRevenue);
  
  let financeChart;
  
  function initFinanceChart(revenueData, expenseData, type) {
    const revenueDates = revenueData.map(item => type === 'daily' ? item.date : item.month);
    const expenseDates = expenseData.map(item => item.date);
    const allDates = [...new Set([...revenueDates, ...expenseDates])].sort();
    
    const revenueAmounts = allDates.map(date => {
      const item = revenueData.find(r => (type === 'daily' ? r.date : r.month) === date);
      return item ? parseFloat(item.total || 0) : 0;
    });
    
    const expenseAmounts = allDates.map(date => {
      const item = expenseData.find(e => e.date === date);
      return item ? parseFloat(item.total || 0) : 0;
    });
    
    const labels = allDates.map(date => {
      if (type === 'daily') {
        return new Date(date).toLocaleDateString();
      }
      return date;
    });
    
    const options = {
      series: [{
        name: 'Revenue',
        type: 'column',
        data: revenueAmounts
      }, {
        name: 'Expenses',
        type: 'column',
        data: expenseAmounts
      }, {
        name: 'Profit',
        type: 'line',
        data: revenueAmounts.map((rev, i) => rev - expenseAmounts[i])
      }],
      chart: {
        height: 350,
        type: 'line',
        toolbar: { show: true }
      },
      stroke: {
        width: [0, 0, 4]
      },
      dataLabels: {
        enabled: true,
        enabledOnSeries: [2]
      },
      labels: labels,
      xaxis: {
        type: 'category'
      },
      yaxis: {
        title: { text: 'Amount ($)' }
      },
      colors: ['#28a745', '#dc3545', '#3ea572'],
      legend: {
        position: 'top'
      }
    };
    
    if (financeChart) {
      financeChart.destroy();
    }
    
    financeChart = new ApexCharts(document.querySelector("#revenueExpensesChart"), options);
    financeChart.render();
  }
  
  function showDailyFinance() {
    initFinanceChart(dailyRevenue, dailyExpenses, 'daily');
    document.querySelectorAll('#revenueExpensesChart').closest('.card').querySelectorAll('.btn-group button').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
  }
  
  function showMonthlyFinance() {
    initFinanceChart(monthlyRevenue, [], 'monthly');
    document.querySelectorAll('#revenueExpensesChart').closest('.card').querySelectorAll('.btn-group button').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
  }
  
  // Payment Methods Chart
  const paymentMethodsData = @json($paymentMethodBreakdown);
  
  const paymentMethodsOptions = {
    series: paymentMethodsData.map(item => parseFloat(item.total)),
    chart: {
      type: 'donut',
      height: 350
    },
    labels: paymentMethodsData.map(item => item.payment_method.replace('_', ' ').toUpperCase()),
    colors: ['#3ea572', '#2d7a5f', '#6cbe8f', '#17a2b8', '#ffc107'],
    legend: {
      position: 'bottom'
    },
    dataLabels: {
      enabled: true,
      formatter: function(val) {
        return val.toFixed(1) + '%';
      }
    }
  };
  
  const paymentMethodsChart = new ApexCharts(document.querySelector("#paymentMethodsChart"), paymentMethodsOptions);
  paymentMethodsChart.render();
  
  // Revenue Status Chart
  const revenueStatusData = @json($revenueByStatus);
  
  const revenueStatusOptions = {
    series: revenueStatusData.map(item => parseFloat(item.total)),
    chart: {
      type: 'bar',
      height: 300
    },
    plotOptions: {
      bar: {
        horizontal: true
      }
    },
    labels: revenueStatusData.map(item => item.status.toUpperCase()),
    colors: ['#28a745', '#ffc107', '#dc3545', '#17a2b8'],
    dataLabels: {
      enabled: true,
      formatter: function(val) {
        return '$' + val.toFixed(2);
      }
    }
  };
  
  const revenueStatusChart = new ApexCharts(document.querySelector("#revenueStatusChart"), revenueStatusOptions);
  revenueStatusChart.render();
  
  // Expense Categories Chart
  const expenseCategoriesData = @json($expenseCategories);
  
  const expenseCategoriesOptions = {
    series: expenseCategoriesData.map(item => parseFloat(item.total)),
    chart: {
      type: 'pie',
      height: 300
    },
    labels: expenseCategoriesData.map(item => item.expense_category.replace('_', ' ').toUpperCase()),
    colors: ['#dc3545', '#fd7e14', '#ffc107', '#20c997', '#17a2b8'],
    legend: {
      position: 'bottom'
    },
    dataLabels: {
      enabled: true,
      formatter: function(val) {
        return val.toFixed(1) + '%';
      }
    }
  };
  
  const expenseCategoriesChart = new ApexCharts(document.querySelector("#expenseCategoriesChart"), expenseCategoriesOptions);
  expenseCategoriesChart.render();
  
  // Initialize with daily data
  initFinanceChart(dailyRevenue, dailyExpenses, 'daily');
  
  // Export functions
  function exportToExcel() {
    // Create CSV from all tables
    let csv = 'Finance Report\n';
    csv += 'Date Range: {{ $dateFrom }} to {{ $dateTo }}\n\n';
    csv += 'Summary\n';
    csv += 'Total Revenue,${{ number_format($stats["total_revenue"], 2) }}\n';
    csv += 'Total Expenses,${{ number_format($stats["total_expenses"], 2) }}\n';
    csv += 'Net Profit,${{ number_format($stats["profit"], 2) }}\n';
    csv += 'Profit Margin,{{ number_format($stats["profit_margin"], 2) }}%\n\n';
    
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'finance-report-{{ $dateFrom }}-to-{{ $dateTo }}.csv';
    link.click();
  }
  
  function exportToPDF() {
    window.print();
  }
</script>
@endpush
@endsection
