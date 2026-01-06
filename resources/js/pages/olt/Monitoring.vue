<template>
  <div class="p-6 space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">OLT Monitoring Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Real-time monitoring of all OLT devices and connected ONUs</p>
      </div>
      <button 
        @click="syncSignals" 
        :disabled="syncing"
        class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
      >
        <svg v-if="syncing" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>{{ syncing ? 'Syncing...' : 'Sync All Signals' }}</span>
      </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Total OLTs</p>
            <p class="text-3xl font-bold text-gray-800">{{ summary.total_olts }}</p>
            <p class="text-xs text-emerald-600 mt-1">{{ summary.active_olts }} Active</p>
          </div>
          <div class="p-3 bg-blue-50 rounded-lg">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Active ONUs</p>
            <p class="text-3xl font-bold text-emerald-600">{{ summary.online_onus }}</p>
            <p class="text-xs text-gray-500 mt-1">of {{ summary.total_onus }} total</p>
          </div>
          <div class="p-3 bg-emerald-50 rounded-lg">
            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Offline ONUs</p>
            <p class="text-3xl font-bold text-red-600">{{ summary.offline_onus }}</p>
            <p class="text-xs text-gray-500 mt-1">Requires attention</p>
          </div>
          <div class="p-3 bg-red-50 rounded-lg">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Signal Alerts</p>
            <p class="text-3xl font-bold text-orange-600">{{ summary.unresolved_alerts }}</p>
            <p class="text-xs text-red-600 mt-1">{{ summary.critical_alerts }} Critical</p>
          </div>
          <div class="p-3 bg-orange-50 rounded-lg">
            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Alerts -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-lg font-bold text-gray-800">Recent Alerts</h2>
        <div class="flex gap-2">
          <button 
            @click="filterAlerts('all')" 
            :class="alertFilter === 'all' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600'"
            class="px-3 py-1 rounded-lg text-xs font-bold transition-colors"
          >
            All
          </button>
          <button 
            @click="filterAlerts('critical')" 
            :class="alertFilter === 'critical' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-600'"
            class="px-3 py-1 rounded-lg text-xs font-bold transition-colors"
          >
            Critical
          </button>
          <button 
            @click="filterAlerts('warning')" 
            :class="alertFilter === 'warning' ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-600'"
            class="px-3 py-1 rounded-lg text-xs font-bold transition-colors"
          >
            Warning
          </button>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50 text-xs font-bold text-gray-600 uppercase border-b border-gray-100">
              <th class="px-6 py-3 text-left">Time</th>
              <th class="px-6 py-3 text-left">ONU</th>
              <th class="px-6 py-3 text-left">Type</th>
              <th class="px-6 py-3 text-left">Severity</th>
              <th class="px-6 py-3 text-left">Message</th>
              <th class="px-6 py-3 text-center">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-if="alerts.length === 0">
              <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">
                No alerts found
              </td>
            </tr>
            <tr v-for="alert in alerts" :key="alert.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 text-xs text-gray-600">{{ formatDate(alert.created_at) }}</td>
              <td class="px-6 py-4 text-xs font-medium text-gray-800">{{ alert.olt_user?.name || 'N/A' }}</td>
              <td class="px-6 py-4">
                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-medium">{{ alert.type }}</span>
              </td>
              <td class="px-6 py-4">
                <span 
                  :class="{
                    'bg-red-100 text-red-700': alert.severity === 'critical',
                    'bg-orange-100 text-orange-700': alert.severity === 'warning',
                    'bg-blue-100 text-blue-700': alert.severity === 'info'
                  }"
                  class="px-2 py-1 rounded text-xs font-bold uppercase"
                >
                  {{ alert.severity }}
                </span>
              </td>
              <td class="px-6 py-4 text-xs text-gray-600">{{ alert.message }}</td>
              <td class="px-6 py-4 text-center">
                <button 
                  v-if="!alert.is_resolved"
                  @click="resolveAlert(alert.id)"
                  class="px-3 py-1 bg-emerald-600 text-white rounded text-xs font-bold hover:bg-emerald-700 transition-colors"
                >
                  Resolve
                </button>
                <span v-else class="text-xs text-emerald-600 font-medium">Resolved</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const summary = ref({
  total_olts: 0,
  active_olts: 0,
  total_onus: 0,
  online_onus: 0,
  offline_onus: 0,
  unresolved_alerts: 0,
  critical_alerts: 0
});

const alerts = ref([]);
const syncing = ref(false);
const alertFilter = ref('all');

const fetchSummary = async () => {
  try {
    const res = await axios.get('/api/config/monitoring/summary');
    summary.value = res.data;
  } catch (error) {
    console.error('Failed to fetch monitoring summary', error);
  }
};

const fetchAlerts = async () => {
  try {
    const params = {
      unresolved_only: true
    };
    
    if (alertFilter.value !== 'all') {
      params.severity = alertFilter.value;
    }

    const res = await axios.get('/api/config/monitoring/alerts', { params });
    alerts.value = res.data.data || res.data;
  } catch (error) {
    console.error('Failed to fetch alerts', error);
  }
};

const syncSignals = async () => {
  syncing.value = true;
  try {
    const res = await axios.post('/api/config/monitoring/sync-signals');
    await Swal.fire({
      icon: 'success',
      title: 'Sync Complete',
      text: res.data.message,
      timer: 2000
    });
    await fetchSummary();
    await fetchAlerts();
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Sync Failed',
      text: error.response?.data?.message || 'Failed to sync signals'
    });
  } finally {
    syncing.value = false;
  }
};

const resolveAlert = async (alertId) => {
  try {
    await axios.post(`/api/config/monitoring/alerts/${alertId}/resolve`);
    await Swal.fire({
      icon: 'success',
      title: 'Alert Resolved',
      timer: 1500,
      showConfirmButton: false
    });
    await fetchAlerts();
    await fetchSummary();
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Failed to resolve alert',
      text: error.response?.data?.message || 'An error occurred'
    });
  }
};

const filterAlerts = (filter) => {
  alertFilter.value = filter;
  fetchAlerts();
};

const formatDate = (dateStr) => {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  return date.toLocaleString('en-US', { 
    month: 'short', 
    day: 'numeric', 
    hour: 'numeric', 
    minute: '2-digit',
    hour12: true 
  });
};

onMounted(() => {
  fetchSummary();
  fetchAlerts();
  
  // Auto-refresh every 30 seconds
  setInterval(() => {
    fetchSummary();
    fetchAlerts();
  }, 30000);
});
</script>
