<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Premium Hero Banner -->
        <div class="dashboard-welcome-banner relative overflow-hidden rounded-3xl p-6 md:p-8 mb-8">

            <!-- Decorative background circle (matches your theme) -->
            <div class="dashboard-welcome-glow absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full" aria-hidden="true"></div>

            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="max-w-2xl">
                    <p class="dashboard-welcome-kicker text-xs font-bold tracking-widest uppercase mb-3">Operations overview</p>
                    <h1 class="text-3xl md:text-4xl font-bold mb-3" style="font-family: 'Playfair Display', serif;">
                        Welcome back, {{ Auth::user()->name }}.
                    </h1>
                    <p class="dashboard-welcome-copy text-lg md:text-base leading-relaxed">
                        Keep your rental catalogue, customer requests, and upcoming events moving seamlessly in one place.
                    </p>
                </div>
                <div class="flex-shrink-0 rounded-2xl p-4 shadow-lg" style="background: linear-gradient(135deg, #f97316 0%, #ec4899 100%); color: white;">
                    <x-lucide-shirt width="42" height="42" stroke-width="1.5" />
                </div>
            </div>
        </div>

        <!-- Illustrative charts: replace these sample values with report data before using for business decisions. -->
        <div class="mb-3 text-right text-xs font-medium text-gray-500">Charts show illustrative sample data</div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <section class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 lg:col-span-2" aria-labelledby="revenue-chart-title">
                <div class="flex flex-wrap justify-between items-center gap-2 mb-4">
                    <h3 id="revenue-chart-title" class="text-lg font-bold text-gray-900">Revenue Overview</h3>
                    <span class="text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-md">Sample data</span>
                </div>
                <div id="revenueChart" aria-label="Illustrative monthly revenue chart"></div>
            </section>

            <section class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100" aria-labelledby="reservation-chart-title">
                <div class="flex justify-between items-center gap-2 mb-4">
                    <h3 id="reservation-chart-title" class="text-lg font-bold text-gray-900">Reservation Status</h3>
                    <span class="text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-md">Sample data</span>
                </div>
                <div id="statusChart" class="flex justify-center" aria-label="Illustrative reservation status chart"></div>
            </section>
        </div>

        <section class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8" aria-labelledby="top-items-chart-title">
            <div class="flex flex-wrap justify-between items-center gap-2 mb-4">
                <h3 id="top-items-chart-title" class="text-lg font-bold text-gray-900">Top Rented Items &amp; Packages</h3>
                <span class="text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-md">Sample data</span>
            </div>
            <div id="topItemsChart" aria-label="Illustrative most rented items chart"></div>
        </section>

        <!-- Bento Box Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

            <!-- Inventory Card -->
            <a class="group relative overflow-hidden bg-white rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl border border-gray-100" href="{{ route('admin.rental-items.index') }}">
                <div class="flex items-center gap-4 mb-4">
                    <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-orange-50 text-orange-500 transition-transform group-hover:scale-110">
                        <x-lucide-shirt width="24" height="24" />
                    </span>
                    <span class="text-xs font-bold tracking-widest uppercase text-gray-400">Inventory</span>
                </div>
                <strong class="block text-xl font-bold text-gray-900 mb-2">Rental items</strong>
                <span class="text-sm text-gray-500 leading-relaxed">Manage the catalogue and availability.</span>
            </a>

            <!-- Reservations Card -->
            <a class="group relative overflow-hidden bg-white rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl border border-gray-100" href="{{ route('admin.reservations.index') }}">
                <div class="flex items-center gap-4 mb-4">
                    <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-pink-50 text-pink-500 transition-transform group-hover:scale-110">
                        <x-lucide-calendar-check width="24" height="24" />
                    </span>
                    <span class="text-xs font-bold tracking-widest uppercase text-gray-400">Bookings</span>
                </div>
                <strong class="block text-xl font-bold text-gray-900 mb-2">Reservations</strong>
                <span class="text-sm text-gray-500 leading-relaxed">Review upcoming event requirements.</span>
            </a>

            <!-- Reminders Card (Gradient) -->
            <a class="group relative overflow-hidden rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
               style="background: linear-gradient(135deg, #4a1525 0%, #8c2e48 100%); color: white;"
               href="{{ route('admin.notifications.index') }}">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-5"></div>
                <div class="relative z-10 flex items-center gap-4 mb-4">
                    <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/10 text-white transition-transform group-hover:scale-110">
                        <x-lucide-bell width="24" height="24" />
                    </span>
                    <span class="text-xs font-bold tracking-widest uppercase text-pink-200">Follow-up</span>
                </div>
                <strong class="relative z-10 block text-xl font-bold mb-2">Reminders</strong>
                <span class="relative z-10 text-sm text-pink-100 leading-relaxed">Keep customers informed at the right time.</span>
            </a>

            <!-- Reports Card (Wide) -->
            <a class="group relative overflow-hidden bg-white rounded-2xl p-6 md:p-8 lg:col-span-3 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl border border-gray-100 flex flex-col md:flex-row md:items-center justify-between" href="{{ route('admin.reports.index') }}">
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-yellow-50 text-yellow-600 transition-transform group-hover:scale-110">
                            <x-lucide-chart-column width="24" height="24" />
                        </span>
                        <span class="text-xs font-bold tracking-widest uppercase text-gray-400">Business intelligence</span>
                    </div>
                    <strong class="block text-xl font-bold text-gray-900 mb-2">Reports &amp; transactions</strong>
                    <span class="text-sm text-gray-500 leading-relaxed max-w-2xl block">Compare reservations, payments, item demand, and financial performance.</span>
                </div>

                <!-- Ready for Analytics Placeholder -->
                <div class="mt-6 md:mt-0 flex items-center gap-2 text-sm font-semibold transition-colors text-orange-500 group-hover:text-orange-600">
                    View Analytics <span class="text-lg">&rarr;</span>
                </div>
            </a>
        </div>

        <!-- Workspace Status Note -->
        <div class="flex items-center gap-4 bg-white/60 backdrop-blur-sm border border-gray-200 rounded-xl p-4 px-6 shadow-sm">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>
            <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                <strong class="text-sm text-gray-900">Workspace status:</strong>
                <span class="text-sm text-gray-600">Your admin workspace is ready for today’s work.</span>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof ApexCharts === 'undefined') {
                console.error('ApexCharts did not load; dashboard charts are unavailable.');
                return;
            }

            const revenueElement = document.querySelector('#revenueChart');
            const statusElement = document.querySelector('#statusChart');
            const topItemsElement = document.querySelector('#topItemsChart');

            if (!revenueElement || !statusElement || !topItemsElement) {
                console.error('Dashboard chart containers are missing.');
                return;
            }

            new ApexCharts(revenueElement, {
                series: [{ name: 'Sample revenue', data: [15000, 22000, 18000, 28000, 25000, 35000, 42000] }],
                chart: { type: 'area', height: 300, fontFamily: 'DM Sans, sans-serif', toolbar: { show: false } },
                colors: ['#ec4899'],
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'], axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { colors: '#9ca3af' } } },
                yaxis: { labels: { style: { colors: '#9ca3af' }, formatter: function (value) { return '₱' + value.toLocaleString(); } } },
                grid: { borderColor: '#f3f4f6', strokeDashArray: 4 },
                tooltip: { theme: 'light' }
            }).render();

            new ApexCharts(statusElement, {
                series: [44, 55, 13],
                labels: ['Completed', 'Active', 'Pending'],
                chart: { type: 'donut', height: 300, fontFamily: 'DM Sans, sans-serif' },
                colors: ['#f97316', '#ec4899', '#c9a35f'],
                plotOptions: { pie: { donut: { size: '75%', labels: { show: true, total: { show: true, label: 'Sample total', color: '#6b7280', formatter: function (chart) { return chart.globals.seriesTotals.reduce(function (total, value) { return total + value; }, 0); } } } } } },
                dataLabels: { enabled: false },
                legend: { position: 'bottom', markers: { radius: 12 } },
                stroke: { show: false }
            }).render();

            new ApexCharts(topItemsElement, {
                series: [{ name: 'Sample rentals', data: [45, 38, 30, 25, 15] }],
                chart: { type: 'bar', height: 350, fontFamily: 'DM Sans, sans-serif', toolbar: { show: false } },
                plotOptions: { bar: { horizontal: true, borderRadius: 6, distributed: true, barHeight: '60%' } },
                colors: ['#ec4899', '#f97316', '#c9a35f', '#8c2e48', '#4a1525'],
                dataLabels: { enabled: true, style: { colors: ['#fff'] }, offsetX: 0 },
                xaxis: { categories: ['Royal Blue Gown', 'Classic Wedding Package', 'Red Velvet Suit', 'Emerald Gown', 'Silver Debut Package'], labels: { style: { colors: '#9ca3af' } } },
                yaxis: { labels: { style: { colors: '#111827', fontWeight: 600 } } },
                grid: { borderColor: '#f3f4f6', strokeDashArray: 4 },
                legend: { show: false }
            }).render();
        });
    </script>

</x-app-layout>
