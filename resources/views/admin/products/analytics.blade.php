@extends('layouts.dashboard')

@section('dashboard-content')

<div class="p-6 space-y-8">

    <!--  SUMMARY CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white p-4 rounded-xl shadow">
            <h3 class="text-gray-500">Most Viewed Products</h3>
            <p class="text-2xl font-bold">{{ $topProducts->count() }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <h3 class="text-gray-500">Top Sales</h3>
            <p class="text-2xl font-bold">
                {{ $topProducts->sum('sales_count') }}
            </p>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <h3 class="text-gray-500">Total Views</h3>
            <p class="text-2xl font-bold">
                {{ $mostViewed->sum('views') }}
            </p>
        </div>

    </div>

    <!--  PRODUCT ANALYTICS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Top Selling -->
        <div class="bg-white p-4 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-4">🔥 Top Selling Products</h2>

            @foreach($topProducts as $product)
            <div class="flex justify-between border-b py-2">
                <span>{{ $product->name }}</span>
                <span class="font-bold text-green-600">
                    {{ $product->sales_count }}
                </span>
            </div>
            @endforeach
        </div>

        <!-- Most Viewed -->
        <div class="bg-white p-4 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-4"> Most Viewed Products</h2>

            @foreach($mostViewed as $product)
            <div class="flex justify-between border-b py-2">
                <span>{{ $product->name }}</span>
                <span class="font-bold text-blue-600">
                    {{ $product->views }}
                </span>
            </div>
            @endforeach
        </div>

    </div>

    <!--  ORDER STATUS CHART -->
    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-lg font-semibold mb-4"> Order Status</h2>
        <div style="height: 120px; width: 120px; margin: auto;">
            <canvas id="orderChart"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    new Chart(document.getElementById('orderChart'), {
        type: 'doughnut',
        data: {
            labels: {
                !!json_encode($orderState - > keys()) !!
            },
            datasets: [{
                data: {
                    !!json_encode($orderState - > values()) !!
                }
            }]
        },
        options: {
            responseive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display:false
                }
            }
        }
    });
</script>

@endsection