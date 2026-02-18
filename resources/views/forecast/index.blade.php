@extends('layout.conquer')@section('title', 'Forecast')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Halaman Chart Forecast</h1>

    <form action="{{ route('forecasts.sales_post') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="produk_id">Pilih Produk:</label>
            <select name="produk_id" id="produk_id" class="form-control">
                @foreach ($produks as $produk)
                    <option value="{{ $produk->id }}"
                        {{ old('produk_id', session('selected_id')) == $produk->id ? 'selected' : '' }}>
                        {{ $produk->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <option value="sma" selected>SMA</option>
            <option value="arima">ARIMA</option>
        </select>

        <button type="submit" class="btn btn-primary mb-3">Create Sales Data</button>
    </form>

    @if (session('selected_id'))
        <a href="{{ route('forecasts.forecast', ['id' => session('selected_id')]) }}" class="btn btn-primary mb-3">
            Create Forecast
        </a>
    @endif

    <h2>Forecast Result</h2>

    @if (isset($forecast) && count($forecast))
        <ul>
            @foreach ($historical as $date => $qty)
                <li>{{ \Carbon\Carbon::parse($date)->format('F Y') }}: {{ $qty }} units (Actual)</li>
            @endforeach
            @foreach ($forecast as $date => $qty)
                <li>{{ \Carbon\Carbon::parse($date)->format('F Y') }}: {{ round($qty) }} units (Forecasted)</li>
            @endforeach
        </ul>

        <!-- Chart Container -->
        <canvas id="forecastChart" height="100"></canvas>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('forecastChart').getContext('2d');

            const labels = @json(array_merge(array_keys($historical), array_keys($forecast)));
            const actualData = @json(array_values($historical));
            const forecastData = Array(@json(count($historical))).fill(null).concat(@json(array_values($forecast)));

            const forecastChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Actual Sales',
                            data: actualData,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: false,
                            pointRadius: 4,
                        },
                        {
                            label: 'Forecasted Sales',
                            data: forecastData,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: false,
                            pointRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Quantity'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Timeline'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` ${Math.round(context.parsed.y)} units`;
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @else
        <p>No forecast data available.</p>
    @endif

    @if (isset($mape))
        <h3>MAPE: {{ $mape }}%</h3>
    @endif
    </script>
    </div>
@endsection
