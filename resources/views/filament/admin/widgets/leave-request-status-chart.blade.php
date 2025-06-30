{{-- Pastikan ini baris pertama, tanpa spasi di atas --}}
<x-filament::widget>
    <x-filament::card class="col-span-1"> {{-- Card untuk widget --}}
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ $this->getHeading() }} {{-- Judul Widget dari kelas PHP --}}
            </h2>
            <div class="flex space-x-2">
                {{-- Tombol Filter Waktu --}}
                @foreach ($this->getFilters() as $filterKey => $filterLabel)
                    <button
                        wire:click="setFilter('{{ $filterKey }}')" {{-- Memanggil setFilter() --}}
                        class="@if($filter === $filterKey) fi-btn fi-btn-sm fi-btn-primary @else fi-btn fi-btn-sm fi-btn-secondary @endif"
                    >
                        {{ $filterLabel }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Canvas untuk Grafik --}}
        <div style="position: relative; height:30vh; width:100%">
            <canvas
                x-data="{
                    chartInstance: null,
                    chartData: {{ Js::from($this->getChartData()) }},
                    chartOptions: {{ Js::from($this->getChartOptions()) }},

                    init() {
                        this.initChart();

                        // Listener Livewire untuk memperbarui grafik saat filter berubah
                        Livewire.on('chartUpdated', () => {
                            this.updateChart();
                        });
                    },

                    initChart() {
                        this.chartInstance = new Chart(this.$el, {
                            type: 'doughnut', // <<< TIPE GRAFIK SECARA EKSPLISIT DOUGHNUT
                            data: {
                                labels: this.chartData.labels,
                                datasets: [{
                                    label: 'Jumlah Izin',
                                    data: this.chartData.data,
                                    backgroundColor: this.chartData.backgroundColor,
                                    borderColor: this.chartData.borderColor,
                                    borderWidth: this.chartData.borderWidth,
                                    hoverOffset: this.chartData.hoverOffset,
                                }],
                            },
                            options: this.chartOptions
                        });
                    },

                    updateChart() {
                        // Ambil data terbaru dari PHP
                        const newChartData = {{ Js::from($this->getChartData()) }}; 
                        this.chartInstance.data.labels = newChartData.labels;
                        this.chartInstance.data.datasets[0].data = newChartData.data;
                        this.chartInstance.data.datasets[0].backgroundColor = newChartData.backgroundColor;
                        this.chartInstance.update();
                    },

                    destroy() {
                        if (this.chartInstance) {
                            this.chartInstance.destroy();
                        }
                    }
                }"
                wire:ignore
            ></canvas>
        </div>
    </x-filament::card>
</x-filament::widget>

@script
    {{-- Memuat Chart.js dari CDN. Ini penting karena widget ini custom dan tidak otomatis memuatnya. --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    <script>
        // Callback untuk Livewire.dispatch('chartSegmentClicked')
        Livewire.on('chartSegmentClicked', (data) => {
            // Notifikasi sudah dikirim dari metode onChartSegmentClicked di PHP
            // console.log('Klik segmen:', data.label, data.value); 
        });
    </script>
@endscript
