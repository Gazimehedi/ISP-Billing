<template>
  <div class="relative">
    <button 
      @click="isOpen = !isOpen"
      class="flex items-center space-x-2 focus:outline-none group"
    >
      <div class="h-8 w-8 bg-[#f44336] rounded-full flex items-center justify-center text-white font-bold text-xs shadow-sm group-hover:shadow transition-shadow">
        SI
      </div>
      <svg 
        class="h-3 w-3 text-gray-400 group-hover:text-gray-600 transition-colors" 
        :class="{ 'rotate-180': isOpen }"
        fill="none" viewBox="0 0 24 24" stroke="currentColor"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>

    <!-- Dropdown Menu -->
    <div 
      v-if="isOpen" 
      class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-xl border border-gray-100 py-1 z-50 animate-fadeIn"
    >
      <div class="px-4 py-3 border-b border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">MERCHANT</p>
        <p class="text-xs font-bold text-gray-700 mt-0.5">Silicon ISP</p>
      </div>

      <div class="py-1">
        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors">
          <span class="mr-3 text-gray-400">👤</span> Profile
        </a>
        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors">
          <span class="mr-3 text-gray-400">📄</span> View Profile
        </a>
        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors border-b border-gray-50 pb-3">
          <span class="mr-3 text-gray-400">✏️</span> Edit Profile
        </a>
      </div>

      <div class="py-1">
          <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors">
            <span class="mr-3 text-gray-400">⚙️</span> Settings
          </a>
          <button 
            @click="handleLogout"
            class="w-full flex items-center px-4 py-2 text-xs text-red-600 hover:bg-red-50 transition-colors"
          >
            <span class="mr-3">🔓</span> Log out
          </button>
      </div>
    </div>

    <!-- Backdrop to close dropdown -->
    <div v-if="isOpen" @click="isOpen = false" class="fixed inset-0 z-40"></div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const isOpen = ref(false);
const router = useRouter();

const handleLogout = async () => {
    try {
        await axios.post('/logout');
        // Clear local storage/session if any
        localStorage.removeItem('is_auth');
        router.push('/login');
    } catch (error) {
        console.error('Logout failed:', error);
        // Fallback redirect
        router.push('/login');
    }
};
</script>

<style scoped>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
    animation: fadeIn 0.2s ease-out;
}
</style>
