<template>
  <div class="flex h-screen bg-[#f8f9fa] font-sans overflow-hidden relative">
    <!-- Mobile Sidebar Backdrop -->
    <div 
      v-if="isSidebarOpen && isMobile" 
      @click="closeSidebar"
      class="fixed inset-0 bg-black/50 z-40 lg:hidden transition-opacity"
    ></div>

    <!-- Sidebar -->
    <Sidebar 
      :is-collapsed="isSidebarCollapsed" 
      :is-mobile-open="isSidebarOpen"
      @toggle="toggleSidebar" 
      @close="closeSidebar"
    />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">
      <!-- Topbar -->
      <Topbar @toggle-sidebar="toggleSidebar" />

      <!-- Page Content -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300">
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import Sidebar from './Sidebar.vue';
import Topbar from './Topbar.vue';

const isSidebarCollapsed = ref(false);
const isSidebarOpen = ref(false);
const isMobile = ref(false);

const checkMobile = () => {
  isMobile.value = window.innerWidth < 1024;
  if (!isMobile.value) {
    isSidebarOpen.value = false;
  }
};

const toggleSidebar = () => {
  if (isMobile.value) {
    isSidebarOpen.value = !isSidebarOpen.value;
  } else {
    isSidebarCollapsed.value = !isSidebarCollapsed.value;
  }
};

const closeSidebar = () => {
  isSidebarOpen.value = false;
};

onMounted(() => {
  checkMobile();
  window.addEventListener('resize', checkMobile);
});

onUnmounted(() => {
  window.removeEventListener('resize', checkMobile);
});
</script>

<style>
/* Custom blue glow effect like demo */
body {
    position: relative;
    background: #f8f9fa;
}
body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 0px 0px, rgba(14, 165, 233, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 100% 0px, rgba(14, 165, 233, 0.05) 0%, transparent 40%);
    pointer-events: none;
    z-index: 0;
}
</style>
