<template>
  <header class="h-11 sm:h-12 bg-white border-b border-gray-100 flex items-center justify-between px-2 sm:px-3 md:px-4 shadow-sm shrink-0 z-30">
    <!-- Left Section -->
    <div class="flex items-center space-x-2 sm:space-x-3 min-w-0">
      <!-- Mobile Menu Toggle -->
      <button 
        @click="$emit('toggle-sidebar')"
        class="p-1.5 sm:p-2 hover:bg-gray-50 rounded transition-colors"
      >
        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      <!-- Badge -->
      <div class="hidden sm:flex items-center bg-[#03a9f4] text-white px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-bold shadow-sm">
        M-1492
      </div>
    </div>

    <!-- Right Section -->
    <div class="flex items-center space-x-1 sm:space-x-2 md:space-x-3">
      <!-- PPPoE Dropdown - Hidden on small mobile -->
      <div class="hidden md:block relative">
        <select class="appearance-none bg-gray-50 border border-gray-100 rounded px-2 sm:px-3 py-1 sm:py-1.5 text-[10px] sm:text-xs text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#00bcd4] pr-6 sm:pr-8">
          <option>PPPoE</option>
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center px-1 sm:px-2 pointer-events-none text-gray-400">
          <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </div>
      </div>

      <!-- Add User Icon -->
      <button class="p-1.5 sm:p-2 hover:bg-gray-50 rounded-full transition-colors">
        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
      </button>

      <!-- Dark Mode Icon -->
      <button class="p-1.5 sm:p-2 hover:bg-gray-50 rounded-full transition-colors">
        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
      </button>

      <!-- Profile Dropdown -->
      <ProfileDropdown />
    </div>
  </header>
</template>

<script setup>
import ProfileDropdown from './ProfileDropdown.vue';

defineEmits(['toggle-sidebar']);
</script>
