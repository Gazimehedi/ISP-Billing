<template>
    <div class="space-y-6 p-4 bg-[#f8fafc] min-h-screen">
        <!-- Silicon Styled Header & Toolbar -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wider">OLT Users :</h2>
                    <span class="bg-[#e8f5e9] text-[#2e7d32] px-2 py-0.5 rounded border border-[#c8e6c9] text-xs font-bold">
                        {{ pagination.total || 0 }}
                    </span>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <!-- Search -->
                    <div class="relative min-w-[250px]">
                        <input 
                            v-model="searchQuery" 
                            type="text" 
                            placeholder="Search clients..." 
                            class="w-full pl-9 pr-3 py-1.5 bg-[#fcfcfc] border border-gray-200 rounded text-xs focus:outline-none focus:border-cyan-500 transition-all font-medium"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 absolute left-3 top-2 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>

                    <!-- Action Buttons -->
                    <button @click="fetchUsers" class="p-1.5 border border-green-500 text-green-500 rounded hover:bg-green-50 transition-colors" title="Refresh">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>

                    <button @click="showFilterModal = true" class="flex items-center gap-1 px-3 py-1.5 border border-cyan-500 text-cyan-500 rounded text-xs font-bold hover:bg-cyan-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                        </svg>
                        Filter
                    </button>

                    <button @click="exportData" class="p-1.5 border border-yellow-500 text-yellow-500 rounded hover:bg-yellow-50 transition-colors" title="Download">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </button>

                    <div class="relative">
                        <button @click="showVisibilityMenu = !showVisibilityMenu" class="flex items-center gap-1 px-3 py-1.5 bg-[#1e293b] text-white rounded text-xs font-bold hover:bg-slate-900 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            Visibility
                        </button>
                        <!-- Visibility Dropdown -->
                        <div v-if="showVisibilityMenu" class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded shadow-lg z-50 p-2">
                            <div v-for="col in columns" :key="col.key" class="flex items-center gap-2 p-1 hover:bg-gray-50 rounded cursor-pointer">
                                <input type="checkbox" v-model="col.visible" class="rounded text-cyan-600 focus:ring-cyan-500">
                                <span class="text-[11px] font-semibold text-gray-700">{{ col.label }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Data Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 min-h-[500px] flex flex-col">
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left">
                    <thead class="bg-[#f1f5f9] border-b border-gray-100">
                        <tr>
                            <th v-for="col in visibleColumns" :key="col.key" class="px-4 py-3 text-[11px] font-bold text-gray-600 uppercase">
                                {{ col.label }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-if="loading" class="animate-pulse">
                            <td :colspan="visibleColumns.length" class="px-4 py-8 text-center text-sm text-gray-400">Loading Data...</td>
                        </tr>
                        <tr v-else-if="users.length === 0">
                            <td :colspan="visibleColumns.length" class="px-4 py-20">
                                <!-- Silicon ISP Empty State Card -->
                                <div class="max-w-md mx-auto bg-[#fffbf0] border border-[#fef3c7] rounded-2xl p-8 flex flex-col items-center text-center shadow-sm">
                                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center border border-[#fef3c7] mb-4 text-[#d97706]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-[#92400e] mb-2">Sorry, No Data Found</h3>
                                    <p class="text-xs text-[#b45309] leading-relaxed">
                                        We couldn't find any data right now. Please try again later.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        <tr v-for="user in users" :key="user.id" class="hover:bg-blue-50/20 transition-colors group">
                           <td v-if="isColVisible('client_id')" class="px-4 py-3 text-xs font-semibold text-gray-700">{{ user.id }}</td>
                           <td v-if="isColVisible('name_address')" class="px-4 py-3">
                               <div class="text-xs font-bold text-gray-800">{{ user.name }}</div>
                               <div class="text-[10px] text-gray-400 font-medium">{{ user.address || 'N/A' }}</div>
                           </td>
                           <td v-if="isColVisible('mac')" class="px-4 py-3 text-xs font-mono text-gray-500">{{ user.serial_number }}</td>
                           <td v-if="isColVisible('olt_port')" class="px-4 py-3">
                               <span class="text-xs font-semibold text-gray-600">{{ user.olt_name }}</span>
                               <span class="text-[10px] text-gray-400 block">{{ user.interface }}</span>
                           </td>
                           <td v-if="isColVisible('rx_vlan')" class="px-4 py-3">
                               <div :class="getSignalClass(user.signal)" class="text-xs">{{ user.signal || 'N/A' }} dBm</div>
                               <div class="text-[10px] text-gray-400">VLAN: {{ user.vlan || 'N/A' }}</div>
                           </td>
                           <td v-if="isColVisible('distance')" class="px-4 py-3 text-xs font-medium text-gray-500">{{ user.distance || '0m' }}</td>
                           <td v-if="isColVisible('temp')" class="px-4 py-3 text-xs font-medium text-gray-500">{{ user.temp || 'N/A' }}°C</td>
                           <td v-if="isColVisible('mode')" class="px-4 py-3">
                               <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-[10px] font-bold uppercase">{{ user.mode || 'Bridge' }}</span>
                           </td>
                           <td v-if="isColVisible('updated')" class="px-4 py-3 text-[10px] text-gray-400 font-medium">{{ user.updated_at_fmt || 'Recently' }}</td>
                           <td v-if="isColVisible('actions')" class="px-4 py-3 text-right">
                               <div class="flex items-center justify-end gap-1 transition-opacity">
                                   <button @click="viewDetails(user)" class="p-1 text-gray-400 hover:text-cyan-500 transition-colors">
                                       <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                           <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                       </svg>
                                   </button>
                                   <button @click="rebootOnu(user)" class="p-1 text-gray-400 hover:text-red-500 transition-colors">
                                       <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                           <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                       </svg>
                                   </button>
                               </div>
                           </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="px-4 py-3 border-t border-gray-50 flex items-center justify-between text-xs font-semibold text-gray-500 bg-[#fbfbfb]">
                <div class="flex items-center gap-2">
                    <span>Rows per page:</span>
                    <select v-model="perPage" class="bg-white border border-gray-200 rounded px-1 py-0.5 outline-none focus:border-cyan-500">
                        <option :value="20">20</option>
                        <option :value="50">50</option>
                        <option :value="100">100</option>
                    </select>
                </div>
                <div class="flex items-center gap-4">
                    <span>Page {{ pagination.current_page }} of {{ pagination.last_page || 0 }}</span>
                    <div class="flex items-center gap-1">
                        <button class="px-2 py-1 bg-gray-100 rounded disabled:opacity-30 hover:bg-gray-200 transition-colors" :disabled="pagination.current_page <= 1">First</button>
                        <button class="px-2 py-1 bg-gray-100 rounded disabled:opacity-30 hover:bg-gray-200 transition-colors" :disabled="pagination.current_page <= 1">Previous</button>
                        <button class="px-2 py-1 bg-gray-100 rounded disabled:opacity-30 hover:bg-gray-200 transition-colors">Next</button>
                        <button class="px-2 py-1 bg-gray-100 rounded disabled:opacity-30 hover:bg-gray-200 transition-colors">Last</button>
                    </div>
                    <div class="flex items-center gap-2">
                        <span>Go to page:</span>
                        <input type="text" value="1" class="w-10 border border-gray-200 rounded px-1 py-0.5 text-center outline-none focus:border-cyan-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Modal -->
        <div v-if="showFilterModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showFilterModal = false"></div>
            <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-xl overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">OLT Users Filter</h3>
                    <button @click="showFilterModal = false" class="text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase">OLT</label>
                            <select v-model="filter.olt_id" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500 transition-all">
                                <option value="">Select...</option>
                                <option v-for="olt in olts" :key="olt.id" :value="olt.id">{{ olt.name }}</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase">Status</label>
                            <select v-model="filter.status" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500 transition-all">
                                <option value="">Select...</option>
                                <option value="Online">Online</option>
                                <option value="Offline">Offline</option>
                                <option value="LOS">LOS</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex items-center justify-end gap-3">
                    <button @click="resetFilter" class="px-6 py-2 border border-gray-300 rounded-lg text-xs font-bold text-gray-600 hover:bg-white transition-colors uppercase">Reset</button>
                    <button @click="applyFilter" class="px-8 py-2 bg-teal-600 text-white rounded-lg text-xs font-bold hover:bg-teal-700 transition-all uppercase shadow-md active:scale-95">Submit</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import Swal from 'sweetalert2';
import axios from 'axios';

// State
const users = ref([]);
const olts = ref([]);
const loading = ref(false);
const searchQuery = ref('');
const perPage = ref(100);
const pagination = ref({ current_page: 1, last_page: 0, total: 0 });

const showFilterModal = ref(false);
const showVisibilityMenu = ref(false);

const filter = ref({
    olt_id: '',
    status: ''
});

// Column Configuration
const columns = ref([
    { key: 'client_id', label: 'Client ID', visible: true },
    { key: 'name_address', label: 'Name / Address', visible: true },
    { key: 'mac', label: 'MAC', visible: true },
    { key: 'olt_port', label: 'OLT / Port', visible: true },
    { key: 'rx_vlan', label: 'RX / VLAN', visible: true },
    { key: 'distance', label: 'Distance', visible: true },
    { key: 'temp', label: 'V/T°C', visible: true },
    { key: 'mode', label: 'ONU Model', visible: true },
    { key: 'updated', label: 'Updated', visible: true },
    { key: 'actions', label: 'Actions', visible: true },
]);

// Computed
const visibleColumns = computed(() => columns.value.filter(c => c.visible));

// Methods
const fetchUsers = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/config/olt-users', {
            params: { 
                search: searchQuery.value,
                olt_id: filter.value.olt_id,
                status: filter.value.status,
                per_page: perPage.value
            }
        });
        users.value = response.data.data.map(u => ({
            ...u,
            updated_at_fmt: new Date(u.updated_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
        }));
        pagination.value = {
            current_page: response.data.current_page,
            last_page: response.data.last_page,
            total: response.data.total
        };
    } catch (error) {
        console.error(error);
        // Silently fail or show toast
    } finally {
        loading.value = false;
    }
};

const fetchOlts = async () => {
    try {
        const response = await axios.get('/api/config/olts');
        olts.value = response.data.data;
    } catch (e) {
        console.error(e);
    }
};

const syncUsers = async () => {
    if (!filter.value.olt_id) {
        Swal.fire('Info', 'Please select an OLT from Filter first to sync users for a specific device.', 'info');
        showFilterModal.value = true;
        return;
    }

    try {
        loading.value = true;
        await axios.post(`/api/config/olts/${filter.value.olt_id}/sync-users`);
        Swal.fire({
            title: 'Synced!',
            text: 'OLT users have been synchronized.',
            icon: 'success',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        fetchUsers();
    } catch (e) {
        Swal.fire('Error', 'Failed to sync users for this OLT.', 'error');
    } finally {
        loading.value = false;
    }
};

const exportData = () => {
    // Simple CSV export logic
    const headers = visibleColumns.value.map(c => c.label).join(',');
    const rows = users.value.map(u => {
        return visibleColumns.value.map(c => {
            if (c.key === 'name_address') return `"${u.name}"`;
            return `"${u[c.key] || ''}"`;
        }).join(',');
    }).join('\n');
    
    const blob = new Blob([`${headers}\n${rows}`], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `olt_users_${new Date().getTime()}.csv`;
    a.click();
};

const isColVisible = (key) => columns.value.find(c => c.key === key)?.visible;

const getSignalClass = (signal) => {
    if (!signal) return 'text-gray-400 font-bold';
    const val = parseFloat(signal);
    if (val > -20) return 'text-green-600 font-bold';
    if (val > -25) return 'text-yellow-600 font-bold';
    return 'text-red-600 font-bold';
};

const applyFilter = () => {
    showFilterModal.value = false;
    fetchUsers();
};

const resetFilter = () => {
    filter.value = { olt_id: '', status: '' };
    fetchUsers();
};

const rebootOnu = async (user) => {
    const result = await Swal.fire({
        title: 'Reboot ONU?',
        text: `Are you sure you want to reboot ${user.name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0d9488',
        cancelButtonColor: '#f43f5e',
        confirmButtonText: 'Yes, Reboot'
    });

    if (result.isConfirmed) {
        try {
            await axios.post(`/api/config/olts/${user.olt_id}/reboot`, { onu_id: user.serial_number });
            Swal.fire('Success', 'Reboot command sent.', 'success');
        } catch (e) {
            Swal.fire('Error', 'Failed to reboot ONU.', 'error');
        }
    }
};

const viewDetails = (user) => {
    Swal.fire({
        title: `<span class="text-sm font-bold uppercase">ONU Information</span>`,
        html: `
            <div class="text-left space-y-2 text-xs font-medium text-gray-600">
                <div class="flex justify-between border-b pb-1"><span>Name:</span> <span class="text-gray-900">${user.name}</span></div>
                <div class="flex justify-between border-b pb-1"><span>Serial / MAC:</span> <span class="text-gray-900">${user.serial_number}</span></div>
                <div class="flex justify-between border-b pb-1"><span>Interface:</span> <span class="text-gray-900">${user.olt_name} / ${user.interface}</span></div>
                <div class="flex justify-between border-b pb-1"><span>Signal:</span> <span class="${getSignalClass(user.signal)}">${user.signal || 'N/A'} dBm</span></div>
                <div class="flex justify-between border-b pb-1"><span>Status:</span> <span class="font-bold">${user.status || 'N/A'}</span></div>
            </div>
        `,
        confirmButtonColor: '#1e293b'
    });
};

// Listeners
watch(searchQuery, (val) => {
    if (val === '') fetchUsers();
});

watch(perPage, () => fetchUsers());

onMounted(() => {
    fetchUsers();
    fetchOlts();
});
</script>

<style scoped>
.animate-in {
    animation: fadeIn 0.2s ease-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
</style>
