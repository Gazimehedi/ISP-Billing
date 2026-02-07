<template>
  <div class="px-2 sm:px-6 py-4 space-y-6 max-w-[1600px] mx-auto min-h-screen bg-[#f8fafc]">
    <!-- Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-3">
          <div class="p-2 bg-[#00bcd4]/10 rounded-xl text-[#00bcd4]">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
          </div>
          Message Activity Log
        </h1>
        <p class="text-[10px] text-slate-400 font-bold ml-[52px] -mt-1 uppercase tracking-[0.2em]">Transmission History & Status</p>
      </div>

      <div class="flex items-center gap-3">
        <button 
          @click="fetchData" 
          class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-[#00bcd4] hover:border-[#00bcd4] transition-all shadow-sm"
          title="Refresh Logs"
        >
          <svg :class="['h-5 w-5', loading ? 'animate-spin' : '']" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
        </button>
        <button 
          @click="clearLogs" 
          class="px-5 py-2.5 bg-red-50 text-red-500 border border-red-100 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all shadow-sm flex items-center gap-2"
        >
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
          Clear History
        </button>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div v-for="(stat, key) in statsConfig" :key="key" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-5 group hover:shadow-md transition-all">
        <div :class="['p-4 rounded-2xl group-hover:scale-110 transition-transform', stat.bg]">
          <component :is="stat.icon" :class="['h-6 w-6', stat.color]" />
        </div>
        <div>
          <h3 class="text-2xl font-black text-slate-800 tracking-tight">{{ stats[key] || 0 }}</h3>
          <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-0.5">{{ stat.label }}</p>
        </div>
      </div>
    </div>

    <!-- Log Table & Filters -->
    <div class="bg-white rounded-[32px] shadow-sm border border-slate-100 overflow-hidden">
      <!-- Filters Header -->
      <div class="p-6 border-b border-slate-50 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="flex flex-wrap items-center gap-4">
          <div class="relative w-full sm:w-64">
            <svg class="h-4 w-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input 
              v-model="filters.search"
              @input="debouncedFetch"
              placeholder="Search number or content..." 
              class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-[11px] font-bold focus:bg-white focus:border-[#00bcd4] outline-none transition-all placeholder:text-slate-400"
            >
          </div>
          
          <select 
            v-model="filters.status"
            @change="fetchData"
            class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-[11px] font-black uppercase text-slate-600 outline-none hover:bg-slate-100 transition-all cursor-pointer"
          >
            <option value="">All Status</option>
            <option value="success">Success</option>
            <option value="failed">Failed</option>
          </select>

          <select 
            v-model="filters.provider"
            @change="fetchData"
            class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-[11px] font-black uppercase text-slate-600 outline-none hover:bg-slate-100 transition-all cursor-pointer"
          >
            <option value="">All Providers</option>
            <option value="bulksmsbd">BulksmsBD</option>
            <option value="mimsms">MimSMS</option>
          </select>
        </div>

        <div class="flex items-center gap-4">
           <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Displaying {{ logs.data?.length || 0 }} of {{ logs.total || 0 }} entries</span>
        </div>
      </div>

      <!-- Table Content -->
      <div class="overflow-x-auto no-scrollbar">
        <table class="w-full border-collapse">
          <thead>
            <tr class="bg-slate-50/50">
              <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Recipient</th>
              <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest leading-normal">Message Content</th>
              <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Gateways</th>
              <th class="px-8 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Parts</th>
              <th class="px-8 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
              <th class="px-8 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Date & Time</th>
              <th class="px-8 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <tr v-if="loading && !logs.data" v-for="i in 5" :key="i" class="animate-pulse">
              <td v-for="j in 7" :key="j" class="px-8 py-5"><div class="h-4 bg-slate-100 rounded w-full"></div></td>
            </tr>
            <tr v-else-if="logs.data?.length === 0">
               <td colspan="7" class="px-8 py-20 text-center">
                  <div class="flex flex-col items-center justify-center gap-4 text-slate-300">
                    <svg class="h-16 w-16 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                    <span class="text-xs font-black uppercase tracking-[0.2em]">No logs found in this scope</span>
                  </div>
               </td>
            </tr>
            <tr v-for="log in logs.data" :key="log.id" class="group hover:bg-slate-50 transition-colors">
              <td class="px-8 py-5 whitespace-nowrap">
                <div class="flex items-center gap-3">
                   <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-500">
                     {{ log.number.slice(-2) }}
                   </div>
                   <span class="text-xs font-black text-slate-700 tracking-tight">{{ log.number }}</span>
                </div>
              </td>
              <td class="px-8 py-5 max-w-md">
                <p class="text-[11px] font-medium text-slate-600 line-clamp-2 leading-relaxed">
                  {{ log.content }}
                </p>
              </td>
              <td class="px-8 py-5">
                <div class="flex flex-col gap-1">
                  <span class="text-[10px] font-black text-slate-900 uppercase tracking-wider">{{ log.provider }}</span>
                  <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ log.type }}</span>
                </div>
              </td>
              <td class="px-8 py-5 text-center">
                <span class="px-2.5 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-black">{{ log.sms_count }}</span>
              </td>
              <td class="px-8 py-5 text-center">
                <span :class="['inline-flex px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest', log.status === 'success' ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500']">
                   {{ log.status }}
                </span>
              </td>
              <td class="px-8 py-5 text-right whitespace-nowrap">
                <span class="text-[10px] font-black text-slate-400">{{ formatDate(log.created_at) }}</span>
              </td>
              <td class="px-8 py-5 text-center">
                 <button 
                  @click="viewDetails(log)"
                  class="p-2 text-slate-300 hover:text-[#00bcd4] hover:bg-[#00bcd4]/10 rounded-lg transition-all"
                 >
                   <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                 </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div v-if="logs.total > 0" class="p-6 bg-slate-50/50 border-t border-slate-50 flex items-center justify-between">
        <div class="flex items-center gap-2">
           <button 
            @click="changePage(logs.current_page - 1)" 
            :disabled="logs.current_page === 1"
            class="p-2 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-[#00bcd4] disabled:opacity-30 disabled:pointer-events-none transition-all shadow-sm"
           >
             <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
           </button>
           <span class="text-[10px] font-black text-slate-900 uppercase px-4">Page {{ logs.current_page }} of {{ logs.last_page }}</span>
           <button 
            @click="changePage(logs.current_page + 1)" 
            :disabled="logs.current_page === logs.last_page"
            class="p-2 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-[#00bcd4] disabled:opacity-30 disabled:pointer-events-none transition-all shadow-sm"
           >
             <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
           </button>
        </div>
        <div class="flex items-center gap-2">
           <span class="text-[9px] font-black text-slate-400 uppercase">Records Per Page:</span>
           <select 
            v-model="filters.per_page" 
            @change="fetchData"
            class="bg-transparent text-[10px] font-black text-slate-600 outline-none cursor-pointer"
           >
             <option :value="50">50</option>
             <option :value="100">100</option>
             <option :value="500">500</option>
             <option :value="1000">1000</option>
           </select>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, markRaw } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import moment from 'moment';

const loading = ref(false);
const logs = ref({});
const stats = ref({});
const filters = reactive({
  search: '',
  status: '',
  provider: '',
  per_page: 50,
  page: 1
});

const statsConfig = {
  total: { label: 'Total Logs', icon: markRaw({ template: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>' }), color: 'text-indigo-500', bg: 'bg-indigo-50' },
  success: { label: 'Success', icon: markRaw({ template: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' }), color: 'text-green-500', bg: 'bg-green-50' },
  failed: { label: 'Failed Transmissions', icon: markRaw({ template: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' }), color: 'text-red-500', bg: 'bg-red-50' },
  today_count: { label: 'Today', icon: markRaw({ template: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' }), color: 'text-[#00bcd4]', bg: 'bg-[#00bcd4]/10' }
};

const fetchData = async () => {
  loading.value = true;
  try {
    const [statsRes, logsRes] = await Promise.all([
      axios.get('/api/config/sms-logs/stats'),
      axios.get('/api/config/sms-logs', { params: filters })
    ]);
    stats.value = statsRes.data;
    logs.value = logsRes.data;
  } catch (err) {
    console.error("Failed to fetch logs", err);
  } finally {
    loading.value = false;
  }
};

let timer;
const debouncedFetch = () => {
  clearTimeout(timer);
  timer = setTimeout(() => {
    filters.page = 1;
    fetchData();
  }, 500);
};

const changePage = (page) => {
  filters.page = page;
  fetchData();
};

const formatDate = (date) => {
  return moment(date).format('DD MMM YYYY, hh:mm A');
};

const viewDetails = (log) => {
  let responseData = '';
  try {
     responseData = JSON.parse(log.response_msg);
  } catch(e) {
     responseData = log.response_msg;
  }

  Swal.fire({
    title: `<span class="text-xl font-black uppercase text-slate-800">Log Details</span>`,
    html: `
      <div class="text-left space-y-4 pt-4">
        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
           <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Recipient</p>
           <p class="text-xs font-black text-slate-800">${log.number}</p>
        </div>
        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
           <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Message</p>
           <p class="text-xs font-medium text-slate-600 leading-relaxed">${log.content}</p>
        </div>
        <div class="p-4 bg-slate-800 rounded-2xl border border-slate-700">
           <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2">Raw API Gateway Response</p>
           <pre class="text-[10px] text-green-400 font-mono overflow-auto max-h-32">${JSON.stringify(responseData, null, 2)}</pre>
        </div>
      </div>
    `,
    showCloseButton: true,
    showConfirmButton: false,
    width: '32rem',
    background: '#ffffff',
    customClass: {
      popup: 'rounded-[32px] border-none shadow-2xl'
    }
  });
};

const clearLogs = async () => {
  const result = await Swal.fire({
    title: 'Wipe Log History?',
    text: "This will permanently delete all tracking records.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#f44336',
    cancelButtonColor: '#94a3b8',
    confirmButtonText: 'Yes, Wipe It',
    customClass: {
      popup: 'rounded-[32px]',
      confirmButton: 'rounded-xl font-black uppercase tracking-widest text-[10px] px-6 py-3',
      cancelButton: 'rounded-xl font-black uppercase tracking-widest text-[10px] px-6 py-3',
    }
  });

  if (result.isConfirmed) {
    try {
      await axios.post('/api/config/sms-logs/clear');
      Swal.fire({ title: 'Wiped!', icon: 'success', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
      fetchData();
    } catch (err) {
      Swal.fire('Error!', 'Failed to clear logs.', 'error');
    }
  }
};

onMounted(() => {
  fetchData();
});
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
