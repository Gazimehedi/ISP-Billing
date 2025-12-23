<template>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4">
    <!-- Expected Revenue -->
    <div class="bg-white p-3 sm:p-4 rounded shadow-sm border border-gray-50 flex flex-col transition-all hover:scale-[1.02] hover:shadow-md cursor-pointer">
      <h3 class="text-xs font-black text-gray-700 text-center mb-2 uppercase tracking-tight">Expected: 999,800 Tk</h3>
      <div class="flex-1 flex flex-col sm:flex-row items-center justify-between py-1 relative">
        <!-- Donut on the left/top -->
        <div class="w-full sm:w-1/2 flex justify-center">
            <apexchart type="donut" height="150" :options="expectedOptions" :series="expectedSeries"></apexchart>
        </div>
        <!-- Legend on the right/bottom -->
        <div class="w-full sm:w-1/2 sm:pl-2 space-y-1.5 mt-2 sm:mt-0">
            <div class="flex items-center space-x-2 text-[9px] font-bold">
                <div class="w-2.5 h-2.5 bg-[#00bcd4] rounded-sm shrink-0"></div>
                <span class="text-gray-500 uppercase truncate">Monthly Fee (999k)</span>
            </div>
            <div class="flex items-center space-x-2 text-[9px] font-bold">
                <div class="w-2.5 h-2.5 bg-[#f44336] rounded-sm shrink-0"></div>
                <span class="text-gray-500 uppercase truncate">Monthly Fee Due (0)</span>
            </div>
            <div class="flex items-center space-x-2 text-[9px] font-bold">
                <div class="w-2.5 h-2.5 bg-[#e0e0e0] rounded-sm border border-gray-100 shrink-0"></div>
                <span class="text-gray-400 uppercase truncate">Connection Due (0)</span>
            </div>
        </div>
      </div>
      <!-- Filters -->
      <div class="flex flex-wrap justify-center gap-2 sm:space-x-4 mt-3 border-t border-gray-50 pt-3">
          <label class="flex items-center space-x-1.5 text-[9px] font-black text-gray-600 bg-[#e0f2f1] px-2 py-1 rounded cursor-pointer border border-[#80cbc4]">
              <input type="checkbox" checked class="form-checkbox h-2.5 w-2.5 text-[#009688]">
              <span>Active</span>
          </label>
          <label class="flex items-center space-x-1.5 text-[9px] font-black text-gray-600 bg-[#fff3e0] px-2 py-1 rounded cursor-pointer border border-[#ffcc80]">
              <input type="checkbox" checked class="form-checkbox h-2.5 w-2.5 text-[#ff9800]">
              <span>Expired</span>
          </label>
          <label class="flex items-center space-x-1.5 text-[9px] font-black text-gray-400 bg-gray-50 px-2 py-1 rounded cursor-pointer border border-gray-100 opacity-60">
              <input type="checkbox" class="form-checkbox h-2.5 w-2.5 text-gray-400">
              <span>Inactive</span>
          </label>
      </div>
    </div>

    <!-- Collected Revenue -->
    <div class="bg-white p-3 sm:p-4 rounded shadow-sm border border-gray-50 flex flex-col transition-all hover:scale-[1.02] hover:shadow-md cursor-pointer">
      <h3 class="text-xs font-black text-gray-700 text-center mb-2 uppercase tracking-tight">Collected: 1,342,070 Tk</h3>
      <div class="flex-1 flex flex-col items-center justify-center py-1 relative">
        <apexchart type="radialBar" height="160" :options="collectedOptions" :series="[134]"></apexchart>
        <div class="absolute inset-0 flex items-center justify-center top-6">
            <span class="text-2xl sm:text-2xl font-black text-gray-700">134%</span>
        </div>
      </div>
      <div class="flex flex-wrap justify-center gap-2 sm:space-x-6 mt-3 border-t border-gray-50 pt-3">
          <label class="flex items-center space-x-2 text-[9px] font-black text-[#009688] cursor-pointer">
              <input type="checkbox" checked class="form-checkbox h-3 w-3 text-[#009688] rounded-sm">
              <span>Monthly Fee (1342070)</span>
          </label>
          <label class="flex items-center space-x-2 text-[9px] font-black text-gray-400 cursor-pointer">
              <input type="checkbox" class="form-checkbox h-3 w-3 text-gray-300 rounded-sm">
              <span>Connection Fee (600)</span>
          </label>
      </div>
    </div>

    <!-- Mikrotik Monitoring -->
    <div class="bg-white p-3 sm:p-4 rounded shadow-sm border border-gray-50 transition-all hover:shadow-md">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-xs font-black text-gray-700 uppercase tracking-tight">Mikrotik Client Monitoring</h3>
        <button class="text-[#009688] hover:bg-[#e0f2f1] p-1.5 rounded-full transition-colors">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
        </button>
      </div>
      <div class="flex space-x-2 h-[130px]">
          <!-- Left Large Card -->
          <div class="flex-1 bg-[#03a9f4] rounded-md p-2 text-white flex flex-col items-center justify-center text-center transition-all hover:scale-[1.02]">
               <div class="bg-white/20 p-2 rounded-full mb-1">
                   <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
               </div>
               <div class="text-xl sm:text-2xl font-black leading-tight">3698</div>
               <div class="text-[9px] sm:text-[10px] font-bold uppercase opacity-90 tracking-tighter">Total Clients</div>
          </div>
          
          <!-- Right Stats Grid -->
          <div class="w-[100px] flex flex-col space-y-2">
              <div class="flex-1 bg-[#4caf50] rounded-md p-1.5 text-white flex flex-col items-center justify-center transition-all hover:scale-[1.02]">
                  <div class="text-sm sm:text-base font-black leading-none">0</div>
                  <div class="text-[8px] sm:text-[9px] font-bold uppercase opacity-80 tracking-tighter">Online</div>
              </div>
              <div class="flex-1 bg-[#f44336] rounded-md p-1.5 text-white flex flex-col items-center justify-center transition-all hover:scale-[1.02]">
                  <div class="text-sm sm:text-base font-black leading-none">3698</div>
                  <div class="text-[8px] sm:text-[9px] font-bold uppercase opacity-80 tracking-tighter">Offline</div>
              </div>
          </div>
      </div>
      
      <!-- Bottom Row -->
      <div class="grid grid-cols-2 gap-2 mt-2">
          <div class="bg-[#009688] rounded-md px-2 sm:px-3 py-2 text-white flex items-center justify-between transition-all hover:scale-[1.02]">
              <div class="text-xs sm:text-sm font-black">10000</div>
              <div class="text-[8px] sm:text-[9px] font-bold uppercase opacity-80 text-right leading-tight tracking-tighter">Software<br>Offline</div>
          </div>
          <div class="bg-[#4caf50] rounded-md px-2 sm:px-3 py-2 text-white flex items-center justify-between transition-all hover:scale-[1.02]">
              <div class="text-xs sm:text-sm font-black">0</div>
              <div class="text-[8px] sm:text-[9px] font-bold uppercase opacity-80 text-right leading-tight tracking-tighter">Software<br>Online</div>
          </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue';

const expectedSeries = [999800, 0, 0];
const expectedOptions = {
  chart: { type: 'donut', toolbar: { show: false } },
  labels: ['Monthly Fee', 'Monthly Fee Due', 'Connection Due'],
  colors: ['#00bcd4', '#f44336', '#e0e0e0'],
  legend: { show: false },
  stroke: { width: 0 },
  plotOptions: {
    pie: {
      donut: { size: '75%', labels: { show: false } }
    }
  },
  dataLabels: { enabled: false }
};

const collectedOptions = {
  chart: { type: 'radialBar', toolbar: { show: false } },
  plotOptions: {
    radialBar: {
      startAngle: -110,
      endAngle: 110,
      hollow: { size: '65%', background: 'transparent' },
      track: { background: '#f1f5f9', strokeWidth: '100%', margin: 0 },
      dataLabels: {
        name: { show: false },
        value: { show: false }
      }
    }
  },
  colors: ['#4caf50'], // Green color as per demo
  stroke: { lineCap: 'butt' }
};
</script>

