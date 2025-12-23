<template>
  <li>
    <router-link 
      :to="to" 
      class="flex items-center space-x-2.5 px-3 py-2 rounded transition-all duration-200 group relative"
      :class="[
        $route.path === to 
          ? 'bg-[#00bcd4] text-white shadow-[0_2px_10px_rgba(0,188,212,0.3)]' 
          : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800'
      ]"
    >
      <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center">
        <!-- Dashboard Icon Placeholder -->
        <svg v-if="icon === 'DashboardIcon'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
      </div>
      <span v-if="!isCollapsed" class="text-[13px] font-semibold tracking-tight">{{ label }}</span>
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
