<template>
  <div :class="['p-2 sm:p-3 rounded shadow-sm border-l-4 flex items-center justify-between transition-all duration-300 hover:scale-105 hover:shadow-lg cursor-pointer group', bgColor, borderColor]">
    <div class="min-w-0 flex-1">
      <p class="text-[9px] sm:text-[10px] font-black text-white/90 uppercase tracking-tight truncate">{{ label }}</p>
      <h3 class="text-base sm:text-lg md:text-xl font-black text-white leading-tight mt-0.5">{{ value }}</h3>
    </div>
    <div class="relative">
        <div class="p-1.5 sm:p-2 bg-white/20 rounded-lg text-white shrink-0 ml-1.5 sm:ml-2 group-hover:bg-white/30 transition-colors">
            <!-- Icon Map based on Label -->
            <svg v-if="label.includes('Client')" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <svg v-else-if="label.includes('Collection') || label.includes('Expenditure') || label.includes('Salary') || label.includes('Balance')" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <svg v-else class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <!-- Small Checkmark Badge from Demo -->
        <div class="absolute bottom-[-2px] right-[-2px] bg-white rounded-full p-[1px] shadow-sm">
            <svg class="h-1.5 w-1.5 sm:h-2 sm:w-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>
  </div>
</template>


<script setup>
defineProps({
  label: String,
  value: String,
  bgColor: String,
  borderColor: String
});
</script>
