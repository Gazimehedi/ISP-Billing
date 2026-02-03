<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
     <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh]">
        
        <!-- Header -->
        <div class="bg-indigo-600 px-6 py-4 flex items-center justify-between shrink-0">
           <div class="flex items-center gap-3">
               <div class="bg-white/20 p-2 rounded-lg text-white">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
               </div>
               <div>
                  <h2 class="text-lg font-bold text-white">Billing History</h2>
                  <p class="text-indigo-200 text-xs">{{ clientName }} ({{ clientCode }})</p>
               </div>
           </div>
           <button @click="close" class="text-white/70 hover:text-white transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
           </button>
        </div>

        <!-- Error State -->
        <div v-if="error" class="p-6 text-center text-red-500">
            <p class="font-bold">{{ error }}</p>
            <button @click="fetchHistory" class="mt-2 text-indigo-600 underline text-sm">Retry</button>
        </div>

        <!-- Summary Cards -->
        <div v-else-if="summary.monthly_fee !== undefined" class="p-6 grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 border-b border-gray-100 shrink-0">
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Monthly Fee</p>
                <p class="text-xl font-black text-gray-800">৳{{ summary.monthly_fee }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Due</p>
                <p class="text-xl font-black text-red-500">৳{{ summary.due }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Balance</p>
                <p class="text-xl font-black text-emerald-500">৳{{ summary.balance }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Last Payment</p>
                <p class="text-sm font-bold text-indigo-600 truncate">{{ summary.last_payment || 'N/A' }}</p>
            </div>
        </div>

        <!-- Loading Skeleton (Optional or keep simple loader) -->
        <div v-else-if="loading" class="flex-1 p-6 flex items-center justify-center">
             <div class="w-8 h-8 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
        </div>

        <!-- History List -->
        <div v-if="!error && summary.monthly_fee !== undefined" class="flex-1 overflow-y-auto p-6">
            <div v-if="loading" class="flex flex-col items-center justify-center py-10 text-gray-400">
                <!-- Refreshing state -->
                <div class="w-6 h-6 border-2 border-indigo-500 border-t-transparent rounded-full animate-spin mb-2"></div>
            </div>

            <div v-else-if="history.length === 0" class="text-center py-10 text-gray-400 text-sm">
                No billing history found.
            </div>

            <div v-else class="relative space-y-4">
                 <!-- Timeline Line -->
                 <div class="absolute left-8 top-4 bottom-4 w-0.5 bg-gray-100"></div>

                 <div v-for="item in history" :key="item.type + item.id" class="relative pl-16 group">
                     <!-- Icon -->
                     <div class="absolute left-4 top-2 md:top-3 w-8 h-8 rounded-full border-2 bg-white flex items-center justify-center z-10"
                        :class="{
                            'border-red-100 text-red-500': item.type === 'invoice',
                            'border-emerald-100 text-emerald-500': item.type !== 'invoice'
                        }"
                     >
                        <svg v-if="item.type === 'invoice'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                     </div>

                     <!-- Card -->
                     <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-1">
                             <div>
                                 <h4 class="font-bold text-gray-800 text-sm">{{ item.description }}</h4>
                                 <p class="text-xs text-gray-500">{{ item.transaction_id }}</p>
                             </div>
                             <span class="font-black text-sm"
                                :class="item.type === 'invoice' ? 'text-red-500' : 'text-emerald-500'"
                             >
                                {{ item.type === 'invoice' ? '-' : '+' }}৳{{ item.amount }}
                             </span>
                        </div>
                        <div class="flex justify-between items-center text-xs mt-2">
                             <span class="text-gray-400 font-medium">{{ formatDate(item.date) }}</span>
                             <span class="px-2 py-0.5 rounded uppercase tracking-wider font-bold text-[10px]"
                                :class="{
                                    'bg-emerald-100 text-emerald-600': ['paid', 'success', 'approved'].includes(item.status),
                                    'bg-red-100 text-red-600': ['unpaid', 'failure', 'failed'].includes(item.status),
                                    'bg-amber-100 text-amber-600': ['partial', 'pending'].includes(item.status)
                                }"
                             >
                                {{ item.status }}
                             </span>
                        </div>
                     </div>
                 </div>
            </div>
        </div>

     </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    isOpen: Boolean,
    clientId: [String, Number],
    clientName: String,
    clientCode: String
});

const emit = defineEmits(['close']);

const loading = ref(false);
const error = ref(null);
const history = ref([]);
const summary = ref({});

const fetchHistory = async () => {
    if (!props.clientId) return;
    loading.value = true;
    error.value = null;
    history.value = [];
    summary.value = {}; // Reset to empty to avoid stale data
    
    try {
        const res = await axios.get(`/api/config/clients/${props.clientId}/billing-history`);
        history.value = res.data.history;
        summary.value = res.data.client_summary;
    } catch (e) {
        console.error("Failed to fetch billing history", e);
        error.value = "Failed to load billing history. Please try again.";
    } finally {
        loading.value = false;
    }
};

watch(() => props.isOpen, (newVal) => {
    if (newVal) {
        fetchHistory();
    }
});

const close = () => {
    emit('close');
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};
</script>
