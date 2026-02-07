<template>
  <div class="relative">
    <!-- Compact Trigger Button -->
    <button 
      @click="isOpen = !isOpen"
      class="flex items-center space-x-3 p-1 rounded-2xl hover:bg-slate-50 transition-all duration-300 group relative z-50"
    >
      <!-- Initials-based Avatar -->
      <div 
        class="h-9 w-9 rounded-xl flex items-center justify-center bg-slate-900 text-white font-black text-[10px] shadow-lg shadow-slate-900/10 transform group-hover:scale-105 transition-all duration-300 ring-2 ring-white"
      >
        <span class="tracking-tighter">{{ userInitials }}</span>
      </div>
      
      <div class="hidden md:flex flex-col items-start leading-none pr-2">
          <div class="flex items-center space-x-1.5">
              <span class="text-[11px] font-black text-slate-800 uppercase tracking-tight group-hover:text-[#00bcd4] transition-colors">{{ user?.name?.split(' ')[0] || 'User' }}</span>
              <svg 
                class="h-3 w-3 text-slate-400 group-hover:text-slate-900 transition-all" 
                :class="{ 'rotate-180': isOpen }" 
                fill="none" viewBox="0 0 24 24" stroke="currentColor"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
              </svg>
          </div>
          <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ user?.role || 'Admin' }}</span>
      </div>
    </button>

    <!-- Simple & Professional Dropdown -->
    <transition
      enter-active-class="transition duration-150 ease-out"
      enter-from-class="transform scale-95 opacity-0 translate-y-1"
      enter-to-class="transform scale-100 opacity-100 translate-y-0"
      leave-active-class="transition duration-100 ease-in"
      leave-from-class="transform scale-100 opacity-100 translate-y-0"
      leave-to-class="transform scale-95 opacity-0 translate-y-1"
    >
      <div 
        v-if="isOpen" 
        class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-[0_4px_25px_rgba(0,0,0,0.1)] border border-slate-100 z-50 overflow-hidden"
      >
        <!-- User Info Strip -->
        <div class="px-5 py-4 border-b border-slate-50 bg-slate-50/20">
            <p class="text-[12px] font-black text-slate-900 truncate uppercase tracking-tight">{{ user?.name }}</p>
            <p class="text-[9px] font-bold text-slate-400 truncate mt-0.5 tracking-tighter uppercase">{{ user?.mobile }}</p>
        </div>

        <div class="px-2 py-2">
          <!-- Profile Links -->
          <router-link :to="{ name: 'ViewProfile' }" class="flex items-center space-x-3 px-3 py-2.5 text-[11px] font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-xl transition-all group/item">
             <svg class="w-4 h-4 text-slate-300 group-hover/item:text-[#00bcd4]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
             <span>My Profile</span>
          </router-link>

          <router-link :to="{ name: 'EditProfile' }" class="flex items-center space-x-3 px-3 py-2.5 text-[11px] font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-xl transition-all group/item">
             <svg class="w-4 h-4 text-slate-300 group-hover/item:text-[#00bcd4]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
             <span>Settings</span>
          </router-link>

          <div class="h-[1px] bg-slate-50 mx-2 my-1"></div>

          <button 
            @click="handleLogout" 
            class="w-full flex items-center space-x-3 px-3 py-2.5 text-[11px] font-black text-rose-500 hover:bg-rose-50 rounded-xl transition-all group/item"
          >
             <svg class="w-4 h-4 text-rose-300 group-hover/item:text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
             <span class="uppercase tracking-widest">Logout</span>
          </button>
        </div>
      </div>
    </transition>

    <!-- Backdrop to close dropdown -->
    <div v-if="isOpen" @click="isOpen = false" class="fixed inset-0 z-40 bg-transparent"></div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';

const isOpen = ref(false);
const router = useRouter();
const route = useRoute();
const user = ref(JSON.parse(localStorage.getItem('user')) || null);
const axios = window.axios;

// Close dropdown on route change
watch(() => route.path, () => {
    isOpen.value = false;
});

const userInitials = computed(() => {
    if (!user.value?.name) return '??';
    const names = user.value.name.trim().split(/\s+/);
    if (names.length >= 2) {
        return (names[0][0] + names[names.length - 1][0]).toUpperCase();
    }
    return user.value.name.substring(0, 2).toUpperCase();
});

const fetchUser = async () => {
    try {
        const res = await (window.axios ? window.axios.get('/api/user') : (await axios).get('/api/user'));
        user.value = res.data;
        localStorage.setItem('user', JSON.stringify(res.data));
    } catch (e) {
        console.error("Profile dropdown user fetch failed", e);
    }
};

onMounted(() => {
    fetchUser();
});

const handleLogout = async () => {
    try {
        await axios.post('/logout');
        localStorage.removeItem('is_auth');
        localStorage.removeItem('user');
        window.location.href = '/login';
    } catch (error) {
        console.error('Logout failed:', error);
        localStorage.removeItem('is_auth');
        localStorage.removeItem('user');
        window.location.href = '/login';
    }
};
</script>

<style scoped>
/* Scoped styles are minimal for a professional clean look */
</style>
