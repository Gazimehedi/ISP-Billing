<script setup>
import { ref, onUnmounted } from 'vue';

const props = defineProps({
  items: {
    type: Array, // { label, icon, class, onClick }
    default: () => []
  }
});

const isOpen = ref(false);
const menuStyle = ref({});

const openMenu = (event) => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        const rect = event.currentTarget.getBoundingClientRect();
        // Calculate position (aligned right)
        // Adjust left to place tooltip to the left of the button
        menuStyle.value = {
            top: `${rect.bottom + 5}px`,
            left: `${rect.right - 160}px`, // 160px width
            position: 'absolute'
        };
        // Close on scroll
        window.addEventListener('scroll', closeMenu, true);
        window.addEventListener('resize', closeMenu);
    }
};

const closeMenu = () => {
    isOpen.value = false;
    window.removeEventListener('scroll', closeMenu, true);
    window.removeEventListener('resize', closeMenu);
};

const handleAction = (item) => {
    closeMenu();
    if (item.onClick) item.onClick();
};

onUnmounted(() => {
    window.removeEventListener('scroll', closeMenu, true);
    window.removeEventListener('resize', closeMenu);
});
</script>

<template>
  <div class="relative inline-block text-left">
    <button 
        @click.stop="openMenu" 
        class="text-gray-400 hover:text-gray-600 focus:outline-none p-1 rounded-full hover:bg-gray-100 transition-colors"
        title="More Actions"
    >
        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
        </svg>
    </button>

    <Teleport to="body">
        <div v-if="isOpen" class="fixed inset-0 z-40" @click="closeMenu"></div>
        <div 
            v-if="isOpen" 
            :style="[menuStyle, { zIndex: 50, width: '160px' }]"
            class="bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden"
        >
            <div class="py-1">
                <button 
                    v-for="(item, index) in items" 
                    :key="index"
                    @click="handleAction(item)"
                    class="group flex w-full items-center px-4 py-2 text-sm hover:bg-gray-50 transition-colors"
                    :class="item.class || 'text-gray-700'"
                >
                    <span v-if="item.icon" class="mr-3 h-5 w-5 flex-shrink-0" v-html="item.icon"></span>
                    {{ item.label }}
                </button>
            </div>
        </div>
    </Teleport>
  </div>
</template>
