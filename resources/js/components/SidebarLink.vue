<template>
  <li>
    <router-link 
      :to="to" 
      class="flex items-center transition-all duration-300 group relative overflow-hidden"
      :class="[
        isCollapsed 
          ? 'w-11 h-11 mx-auto justify-center rounded-xl my-1' 
          : 'space-x-3 px-3.5 py-2.5 rounded-xl mx-2 my-0.5',
        $route.path === to 
          ? 'bg-slate-900 text-white shadow-lg' 
          : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'
      ]"
    >
      <!-- Active Indicator Glow at the VERY edge (Full Width Mode) -->
      <div v-if="$route.path === to && !isCollapsed" class="absolute left-[-8px] top-2.5 bottom-2.5 w-1.5 bg-[#00bcd4] rounded-r-full shadow-[2px_0_10px_rgba(0,188,212,0.6)]"></div>
      
      <!-- Indicator for Collapsed Mode (Minimal) -->
      <div v-if="$route.path === to && isCollapsed" class="absolute left-0 top-2 bottom-2 w-1 bg-[#00bcd4] rounded-r-full shadow-[0_0_8px_#00bcd4]"></div>

      <div :class="['flex-shrink-0 w-5.5 h-5.5 flex items-center justify-center transition-colors duration-300', $route.path === to ? 'text-[#00bcd4]' : 'text-slate-400 group-hover:text-slate-900']">
        <!-- Dashboard Icon -->
        <svg v-if="icon === 'DashboardIcon'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
      </div>

      <span v-if="!isCollapsed" class="text-[11.5px] font-extrabold uppercase tracking-wider truncate leading-none">{{ label }}</span>

      <!-- Tooltip for collapsed state -->
      <div v-if="isCollapsed" class="absolute left-[calc(100%+12px)] px-3 py-2 bg-slate-900 text-white text-[10px] font-black rounded-lg opacity-0 group-hover:opacity-100 transition-all pointer-events-none whitespace-nowrap z-50 uppercase tracking-widest shadow-2xl">
        {{ label }}
      </div>
    </router-link>
  </li>
</template>

<script setup>
defineProps({
  to: String,
  icon: String,
  label: String,
  isCollapsed: Boolean
});
</script>
