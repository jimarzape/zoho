@section('headers')
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
@endsection
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-10">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="mb-5">
                    <h2 class="text-lg font-semibold">Sales Overview</h2>
                </div>
               <div x-data="chartComponent">
                <canvas id="salesChart" width="400" height="400"></canvas>
            </div>
            </div>
        </div>
    </div>
</x-app-layout>


@section('scripts')
<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('chartComponent', () => ({
      init() {
        // Ensure the DOM is ready
        this.$nextTick(() => {
          var ctx = document.getElementById('salesChart').getContext('2d');
          var myChart = new Chart(ctx, {
            type: 'line',
            data: {
              labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
              datasets: [{
                label: 'Sales',
                data: [65, 59, 80, 81, 56, 55, 40],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
              }]
            },
            options: {
              scales: {
                y: {
                  beginAtZero: true
                }
              }
            }
          });
        });
      }
    }));
  });
</script>

@endsection
