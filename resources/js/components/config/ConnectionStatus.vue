<script setup>
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    routerId: {
        type: [Number, String],
        required: true
    }
});

const status = ref('unknown'); // unknown, checking, connected, failed
const message = ref('');

const checkConnection = async () => {
    status.value = 'checking';
    message.value = '';
    try {
        await axios.post(`/api/config/mikrotik-routers/${props.routerId}/test-connection`);
        status.value = 'connected';
    } catch (error) {
        status.value = 'failed';
        message.value = error.response?.data?.message || 'Connection Failed';
        console.error(message.value);
    }
};
</script>

<template>
    <div class="flex items-center justify-center">
        <!-- Unknown: Show Check Button -->
        <button v-if="status === 'unknown'" @click="checkConnection" class="text-[#00bcd4] hover:text-[#00acc1] text-xs font-medium focus:outline-none flex items-center transition-colors">
             <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
             </svg>
            Check
        </button>

        <!-- Checking: Spinner -->
        <div v-if="status === 'checking'" class="text-gray-500">
             <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <!-- Connected: Green Check -->
        <div v-if="status === 'connected'" class="text-green-500 flex items-center" title="Connected">
             <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
             </svg>
             <span class="ml-1 text-xs font-medium">Online</span>
        </div>

        <!-- Failed: Red X + Retry -->
        <div v-if="status === 'failed'" class="flex items-center space-x-2">
             <span class="text-red-500 cursor-help" :title="message">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
             </span>
             <button @click="checkConnection" class="text-gray-400 hover:text-gray-600" title="Retry">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
             </button>
        </div>
    </div>
</template>
