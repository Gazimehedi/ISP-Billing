<template>
  <li class="relative">
    <button 
      @click="toggleGroup"
      class="flex items-center transition-all duration-300 group relative overflow-hidden"
      :class="[
        isCollapsed 
          ? 'w-11 h-11 mx-auto justify-center rounded-xl my-1' 
          : 'w-[calc(100%-16px)] mx-2 space-x-3 px-3.5 py-2.5 rounded-xl my-0.5 justify-between',
        isActive 
          ? 'bg-slate-900 text-white shadow-lg shadow-slate-200' 
          : (isOpen && !isCollapsed ? 'bg-slate-50 text-slate-800' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900')
      ]"
    >
      <!-- Active indicator bar (Full Width Mode) -->
      <div v-if="isActive && !isCollapsed" class="absolute left-[-8px] top-2.5 bottom-2.5 w-1.5 bg-[#00bcd4] rounded-r-full shadow-[2px_0_10px_rgba(0,188,212,0.6)]"></div>
      
      <!-- Indicator for Collapsed Mode -->
      <div v-if="isActive && isCollapsed" class="absolute left-0 top-2 bottom-2 w-1 bg-[#00bcd4] rounded-r-full shadow-[0_0_8px_#00bcd4]"></div>

      <div class="flex items-center min-w-0 pointer-events-none" :class="{ 'space-x-3': !isCollapsed }">
        <div :class="['flex-shrink-0 w-5.5 h-5.5 flex items-center justify-center transition-colors duration-300', isActive || (isOpen && !isCollapsed) ? 'text-[#00bcd4]' : 'text-slate-400 group-hover:text-slate-900']">
           <!-- Group Icons -->
           <svg v-if="group.icon === 'ConfigIcon'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
           </svg>
           <svg v-else-if="group.icon === 'OltIcon'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
           </svg>
           <svg v-else-if="group.icon === 'ClientIcon'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
           </svg>
           <svg v-else-if="group.icon === 'EmployeeIcon'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
           </svg>
           <svg v-else-if="group.icon === 'ReportIcon'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
           </svg>
           <svg v-else-if="group.icon === 'ResellerIcon'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
           </svg>
           <svg v-else-if="group.icon === 'BillingIcon'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
           </svg>
           <svg v-else-if="group.icon === 'MessageIcon'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
           </svg>
           <svg v-else class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
           </svg>
        </div>
        <span v-if="!isCollapsed" class="text-[11px] font-black uppercase tracking-[0.1em] truncate leading-tight">{{ group.label }}</span>
      </div>
      
      <svg 
        v-if="!isCollapsed"
        class="w-3.5 h-3.5 transform transition-all duration-300 shrink-0" 
        :class="[isOpen ? 'rotate-180 text-[#00bcd4]' : 'text-slate-300 group-hover:text-slate-600', isActive ? 'text-[#00bcd4]' : '']"
        fill="none" viewBox="0 0 24 24" stroke="currentColor"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
      </svg>

      <!-- Tooltip for collapsed state (Parent Icon) -->
      <div v-if="isCollapsed" class="absolute left-[calc(100%+12px)] px-3 py-2 bg-slate-900 text-white text-[10px] font-black rounded-lg opacity-0 group-hover:opacity-100 transition-all pointer-events-none whitespace-nowrap z-50 uppercase tracking-widest shadow-2xl">
        {{ group.label }}
      </div>
    </button>

    <!-- Sub items -->
    <transition
      @before-enter="beforeEnter"
      @enter="enter"
      @leave="leave"
    >
      <ul v-if="isOpen && !isCollapsed" class="overflow-hidden mt-1.5 space-y-0.5 ml-8 border-l-2 border-[#00bcd4]/10 pl-2 pr-4">
        <li v-for="item in group.items" :key="item.label">
          <router-link 
            :to="item.to" 
            class="flex items-center gap-3 px-3.5 py-2.5 text-[10.5px] font-bold transition-all rounded-xl relative group/sub truncate"
            :class="[
               $route.path === item.to 
               ? 'text-[#00bcd4] bg-[#00bcd4]/10 font-black shadow-sm ring-1 ring-[#00bcd4]/20' 
               : 'text-slate-400 hover:text-slate-900 hover:bg-slate-50'
            ]"
          >
            <div 
              v-if="$route.path === item.to" 
              class="absolute left-[-10px] top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-[#00bcd4] shadow-[0_0_10px_#00bcd4]"
            ></div>
            
            <!-- Sub Item Icons -->
            <div :class="['flex-shrink-0 w-4 h-4 flex items-center justify-center transition-colors', $route.path === item.to ? 'text-[#00bcd4]' : 'text-slate-300 group-hover/sub:text-slate-500']" v-if="item.icon">
                <svg v-if="item.icon === 'RouterIcon'" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                <svg v-else-if="item.icon === 'MapIcon'" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                <svg v-else-if="item.icon === 'CubeIcon'" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                <svg v-else-if="item.icon === 'GiftIcon'" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" /></svg>
                <svg v-else-if="item.icon === 'LinkIcon'" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                <svg v-else-if="item.icon === 'UserTagIcon'" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                <svg v-else-if="item.icon === 'ServerIcon'" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7m-7 0v4" /></svg>
                <svg v-else-if="item.icon === 'UsersIcon'" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                <svg v-else-if="item.icon === 'ChartIcon'" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                <svg v-else-if="item.icon === 'GlobeIcon'" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                <svg v-else-if="item.icon === 'TemplateIcon'" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>
                <svg v-else-if="item.icon === 'DocumentIcon'" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01" /></svg>
                <svg v-else-if="item.icon === 'ReceiptIcon'" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 14l2 2 4-4m5-6v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2z" /></svg>
                <svg v-else-if="item.icon === 'InboxIcon'" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                <svg v-else-if="item.icon === 'MoneyIcon'" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <svg v-else-if="item.icon === 'CalendarIcon'" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" /></svg>
                <svg v-else-if="item.icon === 'LockIcon'" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                <!-- Default Dot for items without a specific icon -->
                <div v-else class="w-1.5 h-1.5 rounded-full bg-slate-300 group-hover/sub:bg-slate-500"></div>
            </div>

            <span class="truncate leading-[1]">{{ item.label }}</span>
          </router-link>
        </li>
      </ul>
    </transition>
  </li>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import { useRoute } from 'vue-router';

const props = defineProps({
  group: Object,
  isCollapsed: Boolean
});

const emit = defineEmits(['requestExpand']);

const isOpen = ref(false);
const route = useRoute();

const isActive = computed(() => {
    return props.group.items.some(item => route.path === item.to);
});

const toggleGroup = () => {
  if (props.isCollapsed) {
    emit('requestExpand');
    return;
  }
  isOpen.value = !isOpen.value;
};

const checkActive = () => {
    if (isActive.value && !props.isCollapsed) {
        isOpen.value = true;
    } else {
        isOpen.value = false;
    }
};

watch(() => route.path, checkActive);
watch(() => props.isCollapsed, (newVal) => {
    if (newVal) isOpen.value = false; 
    else checkActive();
});
onMounted(checkActive);

// Smooth height animations
const beforeEnter = (el) => {
  el.style.height = '0';
  el.style.opacity = '0';
};
const enter = (el) => {
  el.style.transition = 'all 0.35s cubic-bezier(0.4, 0, 0.2, 1)';
  el.style.height = el.scrollHeight + 'px';
  el.style.opacity = '1';
};
const leave = (el) => {
  el.style.transition = 'all 0.25s cubic-bezier(0.4, 0, 0.2, 1)';
  el.style.height = '0';
  el.style.opacity = '0';
};
</script>

<style scoped>
/* Perfect alignment adjustments */
</style>
