<template>
  <aside 
    :class="[
      'bg-white border-r border-gray-100 flex flex-col transition-all duration-300 shadow-sm',
      'fixed lg:relative h-full z-50',
      isMobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
      isCollapsed ? 'w-16' : 'w-52'
    ]"
  >
    <!-- Logo/Header Section -->
    <div class="px-3 py-3 border-b border-gray-50 bg-white flex items-center justify-between shrink-0">
      <div class="flex items-center space-x-2.5 min-w-0">
        <div class="h-8 w-8 bg-[#00bcd4] rounded shadow-sm flex items-center justify-center shrink-0">
            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </div>
        <span v-if="!isCollapsed" class="text-base font-bold text-gray-800 tracking-tight truncate">MH-ISP</span>
      </div>
      
      <!-- Mobile Close Button -->
      <button 
        v-if="isMobileOpen"
        @click="$emit('close')"
        class="lg:hidden p-1 hover:bg-gray-100 rounded transition-colors"
      >
        <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Branch Selector -->
    <div v-if="!isCollapsed" class="px-2 py-3 border-b border-gray-50 bg-[#fbfbfb] shrink-0">
        <div class="relative group">
            <button class="w-full flex items-center justify-between px-2 py-1.5 bg-white border border-gray-100 rounded text-[11px] text-gray-600 hover:bg-gray-50 transition-colors">
                <span class="truncate pr-2">Silicon Demo (Basic)</span>
                <svg class="h-3 w-3 text-gray-400 group-hover:text-gray-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-2 py-3 space-y-1 scrollbar-thin scrollbar-thumb-gray-200">
      <ul class="space-y-0.5">
        <SidebarLink to="/dashboard" icon="DashboardIcon" label="Dashboard" :is-collapsed="isCollapsed" />
        <SidebarGroup 
          v-for="group in menuGroups" 
          :key="group.label"
          :group="group" 
          :is-collapsed="isCollapsed" 
        />
      </ul>
    </nav>

    <!-- Toggle Button (Desktop Only) -->
    <div v-if="!isMobileOpen" class="hidden lg:block px-2 py-2 border-t border-gray-50 shrink-0">
      <button 
        @click="$emit('toggle')"
        class="w-full flex items-center justify-center py-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded transition-all"
      >
        <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-180': isCollapsed }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
        </svg>
      </button>
    </div>
  </aside>
</template>

<script setup>
import SidebarLink from './SidebarLink.vue';
import SidebarGroup from './SidebarGroup.vue';
import { ref } from 'vue';

defineProps({
  isCollapsed: Boolean,
  isMobileOpen: Boolean
});

defineEmits(['toggle', 'close']);

const menuGroups = ref([
  {
    label: 'Configuration',
    icon: 'ConfigIcon',
    items: [
      { label: 'Mikrotik Router', to: '/configuration/router' },
      { label: 'Zone', to: '/configuration/zone' },
      { label: 'Sub Zone', to: '/configuration/sub-zone' },
      { label: 'Box', to: '/configuration/box' },
      { label: 'Client Type', to: '/configuration/client-type' },
      { label: 'Connection Type', to: '/configuration/connection-type' }
    ]
  },
  {
    label: 'OLT Configuration',
    icon: 'OltIcon',
    items: [
      { label: 'OLT Management', to: '/olt/management' },
      { label: 'OLT Users', to: '/olt/users' }
    ]
  },
  {
    label: 'Client',
    icon: 'ClientIcon',
    items: [
      { label: 'All Clients', to: '/client/all' },
      { label: 'Add Client', to: '/client/add' }
    ]
  },
  {
    label: 'Other Client',
    icon: 'ClientIcon',
    items: []
  },
  {
    label: 'Report',
    icon: 'ReportIcon',
    items: []
  },
  {
    label: 'Employee',
    icon: 'EmployeeIcon',
    items: []
  },
  {
    label: 'Reseller',
    icon: 'ResellerIcon',
    items: []
  }
]);
</script>
