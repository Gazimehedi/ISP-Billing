<template>
  <aside 
    :class="[
      'bg-white border-r border-gray-100 flex flex-col transition-all duration-300 shadow-sm',
      'fixed lg:relative h-full z-50',
      isMobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
      isCollapsed ? 'w-[72px]' : 'w-64'
    ]"
  >
    <!-- Logo/Header Section -->
    <div class="px-3.5 py-5 border-b border-gray-50 bg-white flex items-center shrink-0" :class="isCollapsed ? 'justify-center' : 'justify-between px-5'">
      <div class="flex items-center space-x-3.5 min-w-0">
        <div class="h-10 w-10 rounded-xl shadow-lg flex items-center justify-center shrink-0 overflow-hidden bg-white border border-slate-100 group-hover:scale-105 transition-all duration-300">
            <img v-if="user?.profile_pic_url" :src="user.profile_pic_url" class="w-full h-full object-cover">
            <div v-else class="w-full h-full bg-gradient-to-br from-[#00bcd4] to-[#01acc1] flex items-center justify-center">
                 <span class="text-white font-black text-sm tracking-tighter">{{ companyInitials }}</span>
            </div>
        </div>
        <div v-if="!isCollapsed" class="flex flex-col min-w-0">
          <span class="text-[15px] font-black text-slate-900 tracking-tight truncate leading-tight">{{ user?.company_name || '...' }}</span>
          <span class="text-[9px] font-bold text-[#00bcd4] uppercase tracking-[0.2em] mt-0.5">{{ user?.system_label || 'Core System' }}</span>
        </div>
      </div>
      
      <!-- Mobile Close Button -->
      <button 
        v-if="isMobileOpen"
        @click="$emit('close')"
        class="lg:hidden p-2 hover:bg-slate-50 text-slate-400 hover:text-slate-600 rounded-xl transition-all"
      >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Branch Selector -->
    <div v-if="!isCollapsed" class="px-5 py-5 shrink-0 bg-slate-50/30">
        <div class="relative group">
            <button class="w-full flex items-center justify-between px-4 py-3 bg-white border border-slate-100 rounded-2xl text-[10px] font-black text-slate-500 uppercase tracking-widest hover:border-[#00bcd4] hover:shadow-[0_4px_20px_rgba(0,188,212,0.1)] transition-all group-active:scale-95">
                <div class="flex items-center gap-3 truncate">
                   <div class="w-2 h-2 rounded-full bg-[#00bcd4] animate-pulse ring-4 ring-[#00bcd4]/10"></div>
                   <span class="truncate">{{ user?.branch_name || 'System Default' }}</span>
                </div>
                <svg class="h-4 w-4 text-slate-300 group-hover:text-[#00bcd4] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 space-y-1 premium-scrollbar no-scrollbar">
      <ul class="space-y-2">
        <SidebarLink to="/dashboard" icon="DashboardIcon" label="Dashboard" :is-collapsed="isCollapsed" />
        
        <SidebarGroup 
          v-for="group in menuGroups" 
          :key="group.label"
          :group="group" 
          :is-collapsed="isCollapsed"
          @requestExpand="$emit('toggle')"
        />
      </ul>
    </nav>

    <!-- Toggle Button (Desktop Only) -->
    <div v-if="!isMobileOpen" class="hidden lg:block border-t border-slate-50 bg-white shrink-0" :class="isCollapsed ? 'px-3 py-4' : 'px-5 py-4'">
      <button 
        @click="$emit('toggle')"
        class="w-full h-10 flex items-center justify-center text-slate-300 hover:text-[#00bcd4] hover:bg-slate-50 rounded-2xl transition-all group shadow-sm hover:shadow-md border border-slate-50 bg-white"
      >
        <svg class="h-5 w-5 transition-transform group-hover:scale-110" :class="{ 'rotate-180': isCollapsed }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
        </svg>
      </button>
    </div>
  </aside>
</template>

<script setup>
import SidebarLink from './SidebarLink.vue';
import SidebarGroup from './SidebarGroup.vue';
import { ref, onMounted, computed } from 'vue';

defineProps({
  isCollapsed: Boolean,
  isMobileOpen: Boolean
});

defineEmits(['toggle', 'close']);

const menuGroups = ref([
  {
    label: 'Client',
    icon: 'ClientIcon',
    items: [
      { label: 'All Client', to: '/client/all', icon: 'UsersIcon' },
      { label: 'Add Client', to: '/client/add', icon: 'UserTagIcon' }
    ]
  },
  {
      label: 'Other Client',
      icon: 'ClientIcon',
      items: []
  },
  {
    label: 'Billing',
    icon: 'BillingIcon',
    items: [
      { label: 'Billing List', to: '/billing/list', icon: 'MoneyIcon' },
      { label: 'Daily Bill Collection', to: '/billing/daily-collection', icon: 'CalendarIcon' }
    ]
  },
  {
    label: 'Message',
    icon: 'MessageIcon',
    items: [
      { label: 'SMS Gateway', to: '/message/gateway', icon: 'GlobeIcon' },
      { label: 'SMS Template', to: '/message/template', icon: 'TemplateIcon' },
      { label: 'Bulk Message', to: '/message/bulk', icon: 'InboxIcon' },
      { label: 'Message Log', to: '/message/log', icon: 'DocumentIcon' },
      { label: 'Message Invoice', to: '/message/invoice', icon: 'ReceiptIcon' }
    ]
  },
  {
    label: 'Configuration',
    icon: 'ConfigIcon',
    items: [
      { label: 'Mikrotik Router', to: '/configuration/router', icon: 'RouterIcon' },
      { label: 'Zone', to: '/configuration/zone', icon: 'MapIcon' },
      { label: 'Sub Zone', to: '/configuration/sub-zone', icon: 'MapIcon' },
      { label: 'Box', to: '/configuration/box', icon: 'CubeIcon' },
      { label: 'Package', to: '/configuration/package', icon: 'GiftIcon' },
      { label: 'Client Type', to: '/configuration/client-type', icon: 'UserTagIcon' },
      { label: 'Connection Type', to: '/configuration/connection-type', icon: 'LinkIcon' }
    ]
  },
  {
    label: 'OLT Configuration',
    icon: 'OltIcon',
    items: [
      { label: 'OLT List', to: '/olt/list', icon: 'ServerIcon' },
      { label: 'OLT Users', to: '/olt-configuration/olt-users', icon: 'UsersIcon' },
      { label: 'Service Profiles', to: '/olt-configuration/service-profiles', icon: 'TemplateIcon' },
      { label: 'Monitoring', to: '/olt/monitoring', icon: 'ChartIcon' }
    ]
  },
  {
    label: 'Employee',
    icon: 'EmployeeIcon',
    items: [
      { label: 'ALL Employee', to: '/employee/all', icon: 'UsersIcon' },
      { label: 'Role Management', to: '/employee-setting/role', icon: 'LockIcon' }
    ]
  },
  {
    label: 'Reseller',
    icon: 'ResellerIcon',
    items: []
  },
  {
    label: 'Report',
    icon: 'ReportIcon',
    items: []
  }
]);

const user = ref(JSON.parse(localStorage.getItem('user')) || null);
const axiosFallback = window.axios || import('axios');

const fetchUser = async () => {
    try {
        const res = await (window.axios ? window.axios.get('/api/user') : axiosFallback.get('/api/user'));
        user.value = res.data;
    } catch (e) {
        console.error("Sidebar user fetch failed", e);
    }
};

const companyInitials = computed(() => {
    if (!user.value?.company_name) return '...';
    const names = user.value.company_name.split(' ');
    if (names.length >= 2) {
        return (names[0][0] + names[1][0]).toUpperCase();
    }
    return user.value.company_name.substring(0, 2).toUpperCase();
});

onMounted(() => {
    fetchUser();
});
</script>

<style scoped>
.premium-scrollbar::-webkit-scrollbar {
  width: 4px;
}

.premium-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

.premium-scrollbar::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 20px;
  transition: all 0.3s;
}

.premium-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #00bcd4;
}

/* For Firefox */
.premium-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: #cbd5e1 transparent;
}
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
