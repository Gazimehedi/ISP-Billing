<template>
  <div class="bg-white p-3 sm:p-4 rounded shadow-sm border border-gray-50">
    <!-- Header with Filters -->
    <div class="flex flex-col space-y-2 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between mb-3 sm:mb-4">
      <h3 class="text-xs font-black text-gray-700 uppercase tracking-tight">Collection</h3>
      
      <div class="flex flex-col xs:flex-row items-stretch xs:items-center gap-2 sm:gap-3">
          <!-- Month/Year Dropdown -->
          <div class="relative flex-1 xs:flex-initial">
              <select class="w-full appearance-none bg-white border border-gray-100 rounded px-2 sm:px-3 py-1 text-[10px] sm:text-[11px] text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#00bcd4] pr-7 sm:pr-8">
                  <option>December 2025</option>
              </select>
              <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-400">
                  <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
              </div>
          </div>

          <!-- Buttons -->
          <div class="flex border border-gray-100 rounded overflow-hidden">
              <button class="flex-1 xs:flex-initial px-2 sm:px-3 py-1 text-[10px] sm:text-[11px] font-bold text-gray-500 hover:bg-gray-50 border-r border-gray-100 uppercase">Week</button>
              <button class="flex-1 xs:flex-initial px-2 sm:px-3 py-1 text-[10px] sm:text-[11px] font-bold text-white bg-[#009688] uppercase">Month</button>
              <button class="flex-1 xs:flex-initial px-2 sm:px-3 py-1 text-[10px] sm:text-[11px] font-bold text-gray-500 hover:bg-gray-50 border-l border-gray-100 uppercase">Year</button>
          </div>
      </div>
    </div>

    <!-- Chart -->
    <div class="h-[200px] sm:h-[250px]">
      <apexchart type="bar" height="100%" :options="chartOptions" :series="chartSeries"></apexchart>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue';

const chartSeries = [{
  name: 'Collection',
  data: [0, 0, 0, 0, 650000, 0, 0, 0, 0, 1338240, 700, 0, 0, 0, 351510, 0, 0, 0, 0, 0, 0, 150, 0, 0, 0, 0, 0, 0, 0, 0, 0]
}];

const chartOptions = {
  chart: { type: 'bar', toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
  plotOptions: {
    bar: { borderRadius: 2, columnWidth: '55%' }
  },
  dataLabels: { enabled: false },
  stroke: { show: true, width: 2, colors: ['transparent'] },
  xaxis: {
    categories: Array.from({ length: 31 }, (_, i) => i + 1),
    axisBorder: { show: false },
    axisTicks: { show: false },
    labels: { style: { fontSize: '9px', fontWeight: 600, colors: '#94a3b8' } }
  },
  yaxis: { 
    labels: { 
        style: { fontSize: '9px', fontWeight: 600, colors: '#94a3b8' },
        formatter: (val) => val >= 1000 ? (val / 1000).toFixed(0) + 'k' : val 
    } 
  },
  grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
  fill: { opacity: 1, colors: ['#009688'] },
  tooltip: {
    y: { formatter: (val) => val.toLocaleString() + ' Tk' }
  }
};
</script>

