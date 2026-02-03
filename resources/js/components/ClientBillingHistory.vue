<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <!-- Error State -->
        <div v-if="error" class="text-center text-red-500 py-10">
            <p class="font-bold">{{ error }}</p>
            <button @click="fetchHistory" class="mt-2 text-indigo-600 underline text-sm">Retry</button>
        </div>

        <div v-else class="space-y-8">
            <!-- Loading State -->
            <div v-if="loading" class="flex flex-col items-center justify-center py-20">
                <div class="w-10 h-10 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin mb-3"></div>
                <span class="text-gray-400 text-sm font-medium">Loading billing history...</span>
            </div>

            <!-- Content -->
            <template v-else>
                <!-- Summary Cards -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-indigo-50 p-6 rounded-2xl border border-indigo-100">
                        <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-1">Monthly Fee</p>
                        <p class="text-2xl font-black text-indigo-900">৳{{ summary.monthly_fee }}</p>
                    </div>
                    <div class="bg-red-50 p-6 rounded-2xl border border-red-100">
                        <p class="text-[10px] font-bold text-red-400 uppercase tracking-widest mb-1">Total Due</p>
                        <p class="text-2xl font-black text-red-600">৳{{ summary.due }}</p>
                    </div>
                    <div class="bg-emerald-50 p-6 rounded-2xl border border-emerald-100">
                        <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-1">Balance</p>
                        <p class="text-2xl font-black text-emerald-600">৳{{ summary.balance }}</p>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Last Payment</p>
                        <p class="text-lg font-bold text-gray-700 truncate">{{ summary.last_payment || 'N/A' }}</p>
                    </div>
                </div>

                <!-- History List -->
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b pb-4 mb-6">Transaction History</h3>
                    
                    <div v-if="history.length === 0" class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        <p class="text-gray-400 font-medium">No transaction history found</p>
                    </div>

                    <div v-else class="space-y-4">
                        <div v-for="item in history" :key="item.type + item.id" class="flex items-center gap-4 group p-4 rounded-xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                            <!-- Icon -->
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                                :class="{
                                    'bg-red-100 text-red-500': item.type === 'invoice',
                                    'bg-emerald-100 text-emerald-500': item.type !== 'invoice'
                                }"
                            >
                                <svg v-if="item.type === 'invoice'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>

                            <!-- Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-center mb-1">
                                    <h4 class="font-bold text-gray-800 text-sm truncate">{{ item.description }}</h4>
                                    <span class="font-black text-sm"
                                        :class="item.type === 'invoice' ? 'text-red-500' : 'text-emerald-500'"
                                    >
                                        {{ item.type === 'invoice' ? '-' : '+' }}৳{{ item.amount }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-400 font-mono">{{ item.transaction_id }}</span>
                                    <div class="flex items-center gap-3">
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
            </template>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    clientId: {
        type: [String, Number],
        required: true
    }
});

const loading = ref(false);
const error = ref(null);
const history = ref([]);
const summary = ref({});

const fetchHistory = async () => {
    if (!props.clientId) return;
    loading.value = true;
    error.value = null;
    history.value = [];
    summary.value = {};
    
    try {
        const res = await axios.get(`/api/config/clients/${props.clientId}/billing-history`);
        history.value = res.data.history;
        summary.value = res.data.client_summary;
    } catch (e) {
        console.error("Failed to fetch billing history", e);
        error.value = "Failed to load billing history.";
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchHistory();
});

watch(() => props.clientId, () => {
    fetchHistory();
});

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};
</script>
