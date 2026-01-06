<template>
  <div class="p-6 bg-[#eff3f6] min-h-screen font-sans">
    <!-- Header Summary Section -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 text-[#0f3443]">
          <h1 class="text-xl font-bold">PPPoE Clients :</h1>
          <span class="bg-emerald-500 text-white text-xs px-2 py-0.5 rounded font-bold">{{ meta.total }}</span>
        </div>
        <div class="flex items-center gap-4 mt-1 text-xs font-bold">
          <span class="text-gray-500">Bill : <span class="text-gray-700">{{ summary.total_bill }}</span></span>
          <span class="text-[#067a73]">Balance : <span>{{ summary.total_balance }}</span></span>
        </div>
      </div>

      <!-- Center Search Bar -->
      <div class="flex-grow max-w-xl mx-auto w-full">
        <div class="relative group">
          <span class="absolute inset-y-0 left-4 flex items-center">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </span>
          <input 
            v-model="filters.search" 
            @input="handleSearch"
            type="text" 
            placeholder="Search clients..." 
            class="w-full pl-12 pr-4 py-2 bg-white border border-gray-200 rounded-full text-sm focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 outline-none transition-all shadow-sm"
          >
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center gap-2">
        <button @click="fetchClients" class="p-2 border border-emerald-500 text-emerald-500 rounded-md hover:bg-emerald-50 transition-colors" title="Refresh">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
        </button>
        <button @click="showFilterModal = true" class="flex items-center gap-2 px-3 py-1.5 border border-sky-500 text-sky-500 rounded-md hover:bg-sky-50 transition-colors text-xs font-bold">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
          Filter
        </button>
        <button class="p-2 border border-sky-400 text-sky-400 rounded-md hover:bg-sky-50 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
        </button>
        <button @click="$router.push('/client/add')" class="p-2 border border-emerald-500 bg-emerald-500 text-white rounded-md hover:bg-emerald-600 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </button>
        <button class="p-2 bg-[#0f3443] text-white rounded-md hover:bg-[#0a2530] transition-colors flex items-center gap-2 text-xs font-bold">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
          Visibility
        </button>
      </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-[#f8fafc] text-gray-700 text-[11px] uppercase font-bold border-b border-gray-100">
              <th class="px-4 py-4 w-10 text-center"><input type="checkbox" @change="selectAll($event)" class="rounded text-sky-500 h-3.5 w-3.5 border-gray-300"></th>
              <th class="px-4 py-4">Name</th>
              <th class="px-4 py-4">PPPoE</th>
              <th class="px-4 py-4">Zone</th>
              <th class="px-4 py-4">Package</th>
              <th class="px-4 py-4">Bill</th>
              <th class="px-4 py-4">All Date</th>
              <th class="px-4 py-4">Day Left</th>
              <th class="px-4 py-4">Status</th>
              <th class="px-4 py-4 text-center">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-if="loading" class="animate-pulse">
              <td colspan="10" class="px-6 py-20 text-center text-gray-400">
                 <div class="flex flex-col items-center gap-2">
                   <div class="w-8 h-8 border-4 border-sky-500 border-t-transparent rounded-full animate-spin"></div>
                   <span class="text-xs font-medium">Fetching client data...</span>
                 </div>
              </td>
            </tr>
            <tr v-else-if="clients.length === 0">
              <td colspan="10" class="px-6 py-20 text-center text-gray-500 text-xs italic">No clients found matching your criteria.</td>
            </tr>
            <tr v-for="client in clients" :key="client.id" class="hover:bg-gray-50/80 transition-colors text-xs group">
              <td class="px-4 py-3 text-center">
                <input type="checkbox" v-model="selectedClients" :value="client.id" class="rounded text-sky-500 h-3.5 w-3.5 border-gray-300">
              </td>
              <td class="px-4 py-3">
                <div class="font-bold text-emerald-600 hover:underline cursor-pointer">{{ client.client_id_display }}</div>
                <div class="font-medium text-gray-700 mt-0.5">{{ client.name }}</div>
              </td>
              <td class="px-4 py-3">
                <div class="text-sky-500 font-bold">{{ client.service_profile?.name || 'Test New' }}</div>
                <div class="text-gray-400 font-medium text-[10px] mt-0.5">{{ client.username }}</div>
                <div class="text-gray-300 text-[10px] leading-none">********</div>
              </td>
              <td class="px-4 py-3">
                <div class="text-gray-700 font-bold uppercase">{{ client.zone?.name }}</div>
                <div class="text-gray-400 text-[10px] mt-0.5">{{ client.sub_zone?.name }}</div>
              </td>
              <td class="px-4 py-3">
                <span class="text-gray-600 font-medium">{{ client.package?.name }}</span>
              </td>
              <td class="px-4 py-3 leading-tight">
                <div><span class="text-gray-400">Bill:</span> <span class="text-gray-700 font-bold">{{ client.monthly_fee }}</span></div>
                <div><span class="text-gray-400">Balance:</span> <span class="text-emerald-500 font-bold">{{ client.monthly_fee_due }}</span></div>
                <div><span class="text-gray-400">Due:</span> <span class="text-red-500 font-bold">0</span></div>
              </td>
              <td class="px-4 py-3">
                <div class="text-gray-600 font-medium">{{ formatDate(client.created_at) }}</div>
              </td>
              <td class="px-4 py-3">
                <span class="text-emerald-600 font-bold">31</span>
              </td>
              <td class="px-4 py-3 space-y-1">
                 <div class="inline-block px-3 py-0.5 bg-emerald-500 text-white rounded text-[9px] font-bold tracking-wider w-full text-center">PAID</div>
                 <div class="inline-block px-3 py-0.5 bg-teal-600 text-white rounded text-[9px] font-bold tracking-wider w-full text-center">ACTIVE</div>
                 <div class="inline-block px-3 py-0.5 bg-indigo-600 text-white rounded text-[9px] font-bold tracking-wider w-full text-center">MONTHLY FIXED</div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="flex items-center justify-center gap-2">
                   <button class="p-1.5 text-sky-500 hover:bg-sky-50 rounded-md transition-colors border border-sky-100">
                     <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                   </button>
                   <button class="relative inline-flex items-center h-4 w-8 rounded-full bg-emerald-500 transition-colors">
                      <span class="inline-block h-3 w-3 translate-x-4 rounded-full bg-white transition-transform"></span>
                   </button>
                   <div class="relative inline-block text-left">
                     <button @click="toggleAction(client.id)" class="text-gray-400 hover:text-gray-600 p-1">
                       <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path></svg>
                     </button>
                   </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination Footer -->
      <div class="px-6 py-3 border-t border-gray-50 bg-white flex items-center justify-between text-xs text-gray-500 font-medium">
        <div class="flex items-center gap-4">
           <span>Rows per page:</span>
           <select v-model="filters.per_page" @change="fetchClients" class="border-0 bg-transparent py-0 focus:ring-0 cursor-pointer">
             <option :value="10">10</option>
             <option :value="20">20</option>
             <option :value="50">50</option>
             <option :value="100">100</option>
           </select>
        </div>
        
        <div class="flex items-center gap-6">
           <span>{{ meta.from }}-{{ meta.to }} of {{ meta.total }}</span>
           <div class="flex gap-2">
             <button @click="changePage(meta.current_page - 1)" :disabled="meta.current_page <= 1" class="px-2 py-1 disabled:opacity-30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
             </button>
             <button @click="changePage(meta.current_page + 1)" :disabled="meta.current_page >= meta.last_page" class="px-2 py-1 disabled:opacity-30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
             </button>
           </div>
           <div class="flex items-center gap-2">
              <span>Go to page:</span>
              <input type="number" v-model="filters.page" @keyup.enter="fetchClients" class="w-12 h-6 border border-gray-100 rounded text-center focus:ring-1 focus:ring-sky-500 outline-none">
           </div>
        </div>
      </div>
    </div>

    <!-- Filter Modal (Simplified for now) -->
    <div v-if="showFilterModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
       <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden">
          <div class="bg-[#0f4340] px-6 py-3 text-white flex items-center justify-between">
             <span class="font-bold text-sm uppercase">Client Filter</span>
             <button @click="showFilterModal = false">
                <svg class="w-5 h-5 text-white/70 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
             </button>
          </div>
          <div class="p-6 grid grid-cols-2 gap-4">
             <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Zone</label>
                <select v-model="filters.zone_id" class="w-full rounded-2xl border-transparent bg-[#f3f6f9] text-gray-700 text-[11px] py-1.5 px-4 outline-none">
                   <option value="">All Zones</option>
                   <option v-for="zone in dropdowns.zones" :key="zone.id" :value="zone.id">{{ zone.name }}</option>
                </select>
             </div>
             <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Status</label>
                <select v-model="filters.status" class="w-full rounded-2xl border-transparent bg-[#f3f6f9] text-gray-700 text-[11px] py-1.5 px-4 outline-none">
                   <option value="">All Status</option>
                   <option value="active">Active</option>
                   <option value="inactive">Inactive</option>
                </select>
             </div>
             <!-- More filters can be added here -->
          </div>
          <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
             <button @click="resetFilters" class="px-6 py-1.5 border border-gray-300 rounded-lg text-xs font-bold text-gray-500 hover:bg-white transition-colors uppercase">Reset</button>
             <button @click="applyFilters" class="px-6 py-1.5 bg-[#0f4340] text-white rounded-lg text-xs font-bold hover:bg-[#0a2f2d] transition-colors uppercase">Submit</button>
          </div>
       </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const clients = ref([]);
const loading = ref(false);
const showFilterModal = ref(false);
const selectedClients = ref([]);
const summary = ref({ total_bill: 0, total_balance: 0 });
const meta = ref({ current_page: 1, last_page: 1, total: 0, from: 0, to: 0 });

const dropdowns = ref({
    zones: [],
    packages: []
});

const filters = ref({
    search: '',
    status: '',
    payment_status: '',
    zone_id: '',
    package_id: '',
    page: 1,
    per_page: 50
});

const fetchClients = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/api/clients', { params: filters.value });
        clients.value = res.data.clients.data;
        summary.value = res.data.summary;
        meta.value = {
            current_page: res.data.clients.current_page,
            last_page: res.data.clients.last_page,
            total: res.data.clients.total,
            from: res.data.clients.from,
            to: res.data.clients.to
        };
    } catch (error) {
        console.error("Failed to fetch clients", error);
    } finally {
        loading.value = false;
    }
};

const fetchDropdowns = async () => {
    try {
        const [zonesRes, pkgsRes] = await Promise.all([
            axios.get('/api/zones'),
            axios.get('/api/packages')
        ]);
        dropdowns.value.zones = zonesRes.data;
        dropdowns.value.packages = pkgsRes.data;
    } catch (e) {
        console.error("Failed to fetch dropdowns", e);
    }
}

const handleSearch = (() => {
    let timeout;
    return () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            filters.value.page = 1;
            fetchClients();
        }, 500);
    };
})();

const changePage = (page) => {
    filters.value.page = page;
    fetchClients();
};

const applyFilters = () => {
    filters.value.page = 1;
    fetchClients();
    showFilterModal.value = false;
};

const resetFilters = () => {
    filters.value = {
        search: '',
        status: '',
        payment_status: '',
        zone_id: '',
        package_id: '',
        page: 1,
        per_page: 50
    };
    fetchClients();
}

const selectAll = (event) => {
    if (event.target.checked) {
        selectedClients.value = clients.value.map(c => c.id);
    } else {
        selectedClients.value = [];
    }
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true });
}

onMounted(() => {
    fetchClients();
    fetchDropdowns();
});
</script>

<style scoped>
input[type="checkbox"] {
  @apply focus:ring-0 focus:ring-offset-0 transition-all cursor-pointer;
}
/* Table Header Sticky (Optional) */
thead th {
  @apply sticky top-0 z-10;
}
</style>
