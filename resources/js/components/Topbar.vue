<template>
  <header class="h-14 sm:h-16 bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-4 sm:px-6 sticky top-0 shrink-0 z-30 transition-all duration-300">
    <!-- Left Section -->
    <div class="flex items-center space-x-4 min-w-0">
      <!-- Mobile Menu Toggle -->
      <button 
        @click="$emit('toggle-sidebar')"
        class="h-10 w-10 flex items-center justify-center hover:bg-slate-50 text-slate-500 hover:text-slate-900 rounded-xl transition-all group"
      >
        <svg class="h-6 w-6 transform group-active:scale-95 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      <!-- Connection Status Badge -->
      <div v-if="user?.code" class="hidden sm:flex items-center bg-sky-50 text-sky-600 px-3 py-1.5 rounded-xl text-[10px] font-black shadow-sm border border-sky-100 uppercase tracking-widest">
        <span class="w-1.5 h-1.5 bg-sky-500 rounded-full mr-2 animate-pulse"></span>
        {{ user.code }}
      </div>
    </div>

    <!-- Center Section: Search Bar (Global Command Hub) -->
    <div class="hidden md:flex flex-1 max-w-lg mx-12">
        <div class="relative w-full group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-slate-300 group-focus-within:text-[#00bcd4] transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input 
                type="text" 
                placeholder="Search resources, clients, or commands..." 
                class="w-full bg-slate-50/30 border border-slate-100/50 text-[11px] font-bold text-slate-600 placeholder:text-slate-300 placeholder:font-bold rounded-2xl pl-11 pr-4 py-2.5 outline-none focus:bg-white focus:border-[#00bcd4]/20 focus:ring-[6px] focus:ring-[#00bcd4]/5 transition-all duration-500 shadow-sm hover:border-slate-200"
            >
            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center">
                <div class="flex items-center space-x-1 bg-white border border-slate-100 rounded-lg px-2 py-0.5 shadow-sm opacity-60 group-hover:opacity-100 transition-opacity cursor-default">
                    <span class="text-[7px] font-black text-slate-400 uppercase tracking-tighter">Ctrl</span>
                    <span class="text-[7px] font-black text-slate-300 uppercase">+</span>
                    <span class="text-[7px] font-black text-slate-400 uppercase tracking-tighter">K</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Section -->
    <div class="flex items-center space-x-3 sm:space-x-5">
      <!-- Connection Method Selector (Sleek Minimalist) -->
      <div class="hidden xl:flex items-center">
         <button class="flex items-center space-x-2.5 bg-slate-100/30 border border-transparent hover:border-slate-200 px-4 py-2 rounded-xl transition-all duration-300 group">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.15em] group-hover:text-slate-900 transition-colors">PPPoE Access</span>
            <div class="h-1 w-1 rounded-full bg-slate-200 group-hover:bg-[#00bcd4] transition-colors"></div>
            <svg class="h-3 w-3 text-slate-300 group-hover:text-slate-900 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
            </svg>
         </button>
      </div>

      <!-- Live Identity Pill -->
      <div v-if="user?.code" class="hidden sm:flex items-center bg-slate-900 text-white px-4 py-2 rounded-2xl text-[10px] font-black shadow-xl shadow-slate-900/10 border border-slate-800 uppercase tracking-widest relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-r from-[#00bcd4]/0 via-[#00bcd4]/20 to-[#00bcd4]/0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
        <span class="w-1.5 h-1.5 bg-[#00bcd4] rounded-full mr-2.5 shadow-[0_0_8px_#00bcd4] animate-pulse"></span>
        <span class="relative z-10">{{ user.code }}</span>
      </div>

      <!-- Appearance Switch -->
      <button class="h-10 w-10 flex items-center justify-center bg-white hover:bg-slate-50 border border-slate-100 text-slate-400 hover:text-slate-900 rounded-xl transition-all duration-300 active:scale-90" title="Toggle Appearance">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
      </button>

      <!-- Vertical Accent -->
      <div class="h-8 w-[1px] bg-gradient-to-b from-transparent via-slate-100 to-transparent mx-1"></div>

      <!-- Profile Dropdown -->
      <ProfileDropdown />
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue';
import ProfileDropdown from './ProfileDropdown.vue';

defineEmits(['toggle-sidebar']);

const user = ref(JSON.parse(localStorage.getItem('user')) || null);
</script>
