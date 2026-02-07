<template>
  <div class="px-2 sm:px-6 py-4 space-y-6 max-w-[1600px] mx-auto min-h-screen bg-[#f8fafc]">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-3">
           <div class="p-2 bg-[#00bcd4]/10 rounded-xl">
             <svg class="h-6 w-6 text-[#00bcd4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
             </svg>
           </div>
           SMS Gateway Configuration
        </h1>
        <p class="text-[10px] text-slate-400 font-bold ml-[52px] -mt-1 uppercase tracking-[0.2em]">Provider Control Panel</p>
      </div>

      <!-- Provider Status Badge -->
      <div v-if="config.is_active" class="flex items-center gap-3 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
          <div class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
          </div>
          <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Gateway Active</span>
      </div>
    </div>

    <!-- Provider Tabs -->
    <div class="bg-white p-1.5 rounded-2xl shadow-sm border border-slate-100 flex gap-2">
       <button 
        @click="activeProvider = 'bulksmsbd'"
        :class="['px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all', activeProvider === 'bulksmsbd' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600']"
       > BulksmsBD </button>
       <button 
        @click="activeProvider = 'mimsms'"
        :class="['px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all', activeProvider === 'mimsms' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600']"
       > MimSMS </button>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      
      <!-- Left Column: Configuration Form -->
      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
          <!-- Header -->
          <div class="bg-slate-900 px-8 py-6 flex items-center justify-between border-b border-slate-800">
            <div class="flex items-center gap-4">
              <div v-if="activeProvider === 'bulksmsbd'" class="bg-white/5 p-2 rounded-xl border border-white/10 flex items-center justify-center">
                <div class="flex items-center gap-1.5 px-2 py-0.5">
                  <div class="w-2.5 h-2.5 bg-[#00bcd4] rounded-sm transform rotate-45"></div>
                  <span class="text-white text-[13px] font-black tracking-tight leading-none italic uppercase">BulkSMS<span class="text-[#00bcd4]">BD</span></span>
                </div>
              </div>
              <div v-else class="bg-white/5 p-2 rounded-xl border border-white/10 flex items-center justify-center">
                <div class="flex items-center gap-1.5 px-2 py-0.5">
                  <div class="w-1.5 h-4 bg-[#00bcd4] rounded-full"></div>
                  <span class="text-white text-[13px] font-black tracking-tight leading-none uppercase">Mim<span class="text-[#00bcd4]">SMS</span></span>
                </div>
              </div>
              <div>
                <h3 class="text-xs font-black text-white uppercase tracking-[0.15em]">{{ activeProvider === 'bulksmsbd' ? 'BulksmsBD.com' : 'MimSMS.com' }} Settings</h3>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Configuration & API Credentials</p>
              </div>
            </div>
            
            <div class="flex items-center bg-slate-800 p-1 rounded-xl border border-slate-700">
                <button 
                  @click="config.is_active = false"
                  :class="['px-5 py-2 text-[10px] font-black rounded-lg transition-all', !config.is_active ? 'bg-red-500 text-white shadow-lg' : 'text-slate-500 hover:text-slate-300']"
                >DISABLE</button>
                <button 
                  @click="config.is_active = true"
                  :class="['px-5 py-2 text-[10px] font-black rounded-lg transition-all', config.is_active ? 'bg-green-500 text-white shadow-lg' : 'text-slate-500 hover:text-slate-300']"
                >ENABLE</button>
            </div>
          </div>

          <!-- Form Body -->
          <div v-if="loading" class="p-20 flex flex-col items-center justify-center gap-4">
             <div class="animate-spin h-10 w-10 border-4 border-[#00bcd4] border-t-transparent rounded-full"></div>
             <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] animate-pulse">Syncing Provider Data...</span>
          </div>

          <div v-else class="p-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <!-- API Key -->
              <div class="space-y-2">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                  <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                  Master API Key
                </label>
                <div class="relative">
                   <input 
                    v-model="config.api_key"
                    :type="showApiKey ? 'text' : 'password'"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-[#00bcd4] focus:ring-4 focus:ring-[#00bcd4]/5 outline-none transition-all font-mono text-sm"
                    placeholder="Enter your Provider API Key"
                   >
                   <button @click="showApiKey = !showApiKey" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#00bcd4] transition-colors">
                      <svg v-if="!showApiKey" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                      <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L14.59 5.17m-4.59 4.59l4.59 4.59m-9.18 4.6l9.18-9.18" /></svg>
                   </button>
                </div>
              </div>

              <!-- Sender ID -->
              <div class="space-y-2">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                  <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                  Sender ID / Masking
                </label>
                <input 
                  v-model="config.sender_id"
                  type="text" 
                  class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-[#00bcd4] focus:ring-4 focus:ring-[#00bcd4]/5 outline-none transition-all font-bold text-slate-700"
                  placeholder="e.g., MH-ISP"
                >
              </div>
            </div>

            <!-- Endpoint URL -->
            <div class="space-y-2">
              <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                API Endpoint URL
              </label>
              <input 
                v-model="config.api_url"
                type="url" 
                class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-[#00bcd4] focus:ring-4 focus:ring-[#00bcd4]/5 outline-none transition-all text-slate-500 text-sm font-medium"
                :placeholder="activeProvider === 'bulksmsbd' ? 'http://bulksmsbd.net/api/smsapi' : 'https://api.mimsms.com/api/SmsSending/Send'"
              >
            </div>

            <!-- Form Action -->
            <div class="flex justify-end border-t border-slate-50 pt-6">
               <button 
                @click="saveConfig"
                :disabled="saving"
                class="group px-14 py-4 bg-slate-900 text-white font-black uppercase tracking-[0.2em] text-[10px] rounded-[20px] shadow-2xl shadow-slate-900/30 hover:bg-slate-800 hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-4 disabled:opacity-50"
               >
                 <div v-if="saving" class="animate-spin h-4 w-4 border-2 border-[#00bcd4] border-t-transparent rounded-full"></div>
                 <svg v-else class="h-4 w-4 text-[#00bcd4] group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                 <span>{{ saving ? 'Syncing...' : 'Save Configuration' }}</span>
               </button>
            </div>
          </div>
        </div>

        <!-- Documentation Card -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-[1px] rounded-3xl overflow-hidden shadow-2xl">
           <div class="bg-slate-900 px-8 py-8 flex gap-6">
              <div class="h-16 w-16 bg-[#00bcd4]/10 rounded-2xl flex items-center justify-center shrink-0 border border-[#00bcd4]/20 shadow-inner">
                <svg class="h-8 w-8 text-[#00bcd4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              <div class="space-y-3">
                 <h4 class="text-sm font-black text-white uppercase tracking-widest">{{ activeProvider }} Integration Guide</h4>
                 <p class="text-xs text-slate-400 leading-relaxed font-medium"> 
                   To obtain your API Key and Sender ID, log in to your 
                   <a :href="activeProvider === 'bulksmsbd' ? 'https://bulksmsbd.net/login' : 'https://mim-sms.com/login'" target="_blank" class="text-[#00bcd4] underline decoration-dotted">
                     {{ activeProvider }} Dashboard
                   </a>. 
                   Navigate to the API settings and copy the Master API Key. Ensure your Sender ID is approved before use.
                 </p>
                 <div class="flex gap-4 pt-2">
                    <div class="flex items-center gap-2 text-[10px] font-black text-slate-500 uppercase">
                       <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                       Masking Supported
                    </div>
                    <div class="flex items-center gap-2 text-[10px] font-black text-slate-500 uppercase">
                       <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                       99.9% Uptime
                    </div>
                 </div>
              </div>
           </div>
        </div>
      </div>

      <!-- Right Column: Balance & Stats -->
      <div class="space-y-6">
        <!-- Balance Card -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
           <div class="absolute top-0 right-0 p-4">
              <button @click="refreshBalance" :disabled="checkingBalance" class="p-2 hover:bg-slate-50 rounded-xl transition-colors text-slate-400 hover:text-[#00bcd4]">
                 <svg :class="['h-5 w-5', checkingBalance ? 'animate-spin' : '']" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
              </button>
           </div>
           
           <div class="p-8 pt-10 text-center space-y-4">
              <div class="inline-flex p-4 bg-[#00bcd4]/10 rounded-full mb-2">
                 <svg class="h-8 w-8 text-[#00bcd4]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
              </div>
              <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] leading-none">Available Credit</p>
              <div v-if="balanceData" class="space-y-1">
                 <h2 class="text-3xl font-black text-slate-800">৳{{ balanceData.balance || balanceData.data || '0.00' }}</h2>
                 <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">~{{ Math.floor((balanceData.balance || balanceData.data) / 0.50) }} Message Capacity</p>
              </div>
              <div v-else class="space-y-1">
                 <h2 class="text-3xl font-black text-slate-300">৳--.--</h2>
                 <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Connect API to check</p>
              </div>
              
              <div class="pt-4">
                 <a :href="activeProvider === 'bulksmsbd' ? 'https://bulksmsbd.net/recharge' : 'https://mim-sms.com/recharge'" target="_blank" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#00bcd4] text-white text-[10px] font-black rounded-xl uppercase tracking-widest shadow-lg shadow-cyan-500/30 hover:scale-105 transition-transform">
                    Instant Recharge
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                 </a>
              </div>
           </div>
        </div>

        <!-- System Settings -->
        <div class="bg-slate-900 rounded-3xl p-8 space-y-6 shadow-2xl shadow-slate-900/40 relative overflow-hidden">
           <div class="absolute top-0 left-0 w-32 h-32 bg-[#00bcd4]/10 rounded-full blur-3xl -ml-16 -mt-16"></div>
           
           <div class="relative z-10 space-y-5">
              <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-3">
                 <div class="w-1.5 h-1.5 rounded-full bg-[#00bcd4]"></div>
                 Service Metadata
              </h4>
              
              <div class="space-y-4">
                 <div class="flex items-center justify-between p-3 bg-slate-800/50 rounded-2xl border border-slate-700/50 group hover:border-[#00bcd4]/30 transition-colors">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Provider ID</span>
                    <span class="text-xs font-black text-[#00bcd4] uppercase">{{ activeProvider }}</span>
                 </div>
                 <div class="flex items-center justify-between p-3 bg-slate-800/50 rounded-2xl border border-slate-700/50 group hover:border-[#00bcd4]/30 transition-colors">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Protocol</span>
                    <span class="text-xs font-black text-white">Rest API / JSON</span>
                 </div>
                 <div class="flex items-center justify-between p-3 bg-slate-800/50 rounded-2xl border border-slate-700/50 group hover:border-[#00bcd4]/30 transition-colors">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Latency</span>
                    <span class="text-xs font-black text-green-400">&lt; 100ms</span>
                 </div>
              </div>

              <div class="pt-2">
                 <button class="w-full py-4 rounded-2xl border-2 border-slate-800 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] hover:bg-slate-800 hover:text-white transition-all">
                    View Integration Log
                 </button>
              </div>
           </div>
        </div>

        <!-- Test SMS Card -->
        <div class="bg-white rounded-3xl p-8 space-y-5 shadow-sm border border-slate-100">
           <div class="flex items-center gap-3 mb-2">
              <div class="p-2 bg-indigo-50 rounded-lg">
                 <svg class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
              </div>
              <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Send Test SMS</h4>
           </div>
           
           <div class="space-y-4">
              <div class="space-y-1">
                 <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                 <input 
                  v-model="testSms.number"
                  placeholder="88017XXXXXXXX" 
                  class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold focus:bg-white focus:border-[#00bcd4] outline-none transition-all"
                 >
              </div>
              <button 
                @click="sendTestSms"
                :disabled="sendingTest || !config.is_active"
                class="w-full py-3.5 bg-indigo-500 text-white text-[9px] font-black rounded-xl uppercase tracking-[0.2em] hover:bg-indigo-600 active:scale-95 transition-all shadow-lg shadow-indigo-200 disabled:opacity-50 flex items-center justify-center gap-3"
              >
                <div v-if="sendingTest" class="animate-spin h-3.5 w-3.5 border-2 border-white border-t-transparent rounded-full"></div>
                <span>{{ sendingTest ? 'Sending...' : 'Transmit Test' }}</span>
              </button>
              <p class="text-[8px] text-slate-400 font-medium leading-relaxed italic">* Uses the currently enabled gateway. Messaging rates apply.</p>
           </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const activeProvider = ref('bulksmsbd');
const loading = ref(true);
const saving = ref(false);
const checkingBalance = ref(false);
const showApiKey = ref(false);
const sendingTest = ref(false);

const config = ref({
  api_key: '',
  sender_id: '',
  api_url: '',
  is_active: true
});

const testSms = ref({
  number: '',
  message: 'This is a test message from MH-ISP billing system.'
});

const balanceData = ref(null);

const notify = (msg, type = 'success') => {
  Swal.fire({
    icon: type,
    title: msg,
    toast: true,
    position: 'top-end',
    timer: 3000,
    timerProgressBar: true,
    showConfirmButton: false,
    background: '#ffffff',
    iconColor: type === 'success' ? '#00bcd4' : '#f44336'
  });
};

const fetchConfig = async () => {
  loading.value = true;
  balanceData.value = null;
  try {
    const res = await axios.get(`/api/config/sms-gateway?provider=${activeProvider.value}`);
    if (res.data) {
      config.value = {
        api_key: res.data.api_key || '',
        sender_id: res.data.sender_id || '',
        api_url: res.data.api_url || (activeProvider.value === 'bulksmsbd' ? 'http://bulksmsbd.net/api/smsapi' : 'https://api.mimsms.com/api/SmsSending/Send'),
        is_active: !!res.data.is_active
      };
      if (config.value.api_key) {
          refreshBalance();
      }
    } else {
        config.value = {
            api_key: '',
            sender_id: '',
            api_url: activeProvider.value === 'bulksmsbd' ? 'http://bulksmsbd.net/api/smsapi' : 'https://api.mimsms.com/api/SmsSending/Send',
            is_active: true
        };
    }
  } catch (err) {
    console.error("Failed to fetch config", err);
  } finally {
    loading.value = false;
  }
};

const saveConfig = async () => {
  if (!config.value.api_key || !config.value.sender_id) {
    return notify('Please fill in API Key and Sender ID', 'error');
  }

  saving.value = true;
  try {
    await axios.post('/api/config/sms-gateway', {
        ...config.value,
        provider: activeProvider.value
    });
    notify('Gateway configuration saved successfully', 'success');
    refreshBalance();
  } catch (err) {
    console.error("Failed to save config", err);
    notify('Failed to save configuration', 'error');
  } finally {
    saving.value = false;
  }
};

const refreshBalance = async () => {
    if (!config.value.api_key) return;
    
    checkingBalance.value = true;
    try {
        const res = await axios.get(`/api/config/sms-gateway/balance?provider=${activeProvider.value}`);
        if (res.data && (res.data.status === 'success' || res.data.balance !== undefined || res.data.data !== undefined)) {
            balanceData.value = res.data;
        } else {
            notify(res.data.error || 'Failed to check balance', 'error');
        }
    } catch (err) {
        console.error("Balance check failed", err);
        notify('Balance check failed', 'error');
    } finally {
        checkingBalance.value = false;
    }
};

const sendTestSms = async () => {
  if (!testSms.value.number || !testSms.value.message) {
    return notify('Please enter phone number and message', 'error');
  }

  sendingTest.value = true;
  try {
    const res = await axios.post('/api/config/sms-gateway/test', testSms.value);
    if (res.data.status === 'success' || res.data.response_code) {
      notify('Test SMS sent successfully', 'success');
    } else {
      notify(res.data.message || 'Failed to send test SMS', 'error');
    }
  } catch (err) {
    console.error("Test SMS failed", err);
    notify('Test SMS failed', 'error');
  } finally {
    sendingTest.value = false;
  }
};

watch(activeProvider, () => {
    fetchConfig();
});

onMounted(() => {
  fetchConfig();
});
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
