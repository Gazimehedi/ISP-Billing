<template>
    <div class="p-6 bg-[#eff3f6] min-h-screen font-sans">
        <div v-if="loading" class="flex items-center justify-center h-64">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>
        
        <div v-else-if="client" class="max-w-7xl mx-auto space-y-6">
            <!-- Header Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="h-32 bg-gradient-to-r from-[#0f3443] to-[#30e8bf] relative">
                    <div class="absolute -bottom-12 left-8 flex items-end gap-6">
                        <div class="w-32 h-32 rounded-2xl border-4 border-white shadow-lg overflow-hidden bg-white">
                            <img v-if="client.profile_pic" :src="`/storage/${client.profile_pic}`" class="w-full h-full object-cover">
                            <div v-else class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-300">
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                            </div>
                        </div>
                        <div class="pb-2">
                            <h1 class="text-2xl font-bold text-gray-800">{{ client.name }}</h1>
                            <div class="flex items-center gap-3 text-sm font-medium">
                                <span class="text-gray-400">#{{ client.id }}</span>
                                <span :class="client.status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600'" class="px-3 py-0.5 rounded-full text-[10px] uppercase font-bold tracking-wider">
                                    {{ client.status || 'Active' }}
                                </span>
                                <span class="bg-blue-50 text-blue-600 px-3 py-0.5 rounded-full text-[10px] uppercase font-bold tracking-wider">
                                    {{ client.username }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-16 pb-6 px-8 flex items-center justify-end gap-3">
                    <button @click="$router.push(`/client/edit/${client.id}`)" class="px-5 py-2 border border-gray-200 text-gray-600 rounded-lg text-xs font-bold hover:bg-gray-50 transition-all uppercase tracking-wide">
                        Edit Profile
                    </button>
                    <button class="px-5 py-2 bg-[#0f3443] text-white rounded-lg text-xs font-bold hover:bg-[#0a2530] transition-all uppercase tracking-wide shadow-md active:scale-95">
                        Post Payment
                    </button>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Left Sidebar: Quick Stats -->
                <div class="md:col-span-4 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b pb-2">Financial Summary</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100">
                                <p class="text-[10px] font-bold text-emerald-600 uppercase mb-1">Total Bill</p>
                                <p class="text-xl font-black text-emerald-700">{{ client.monthly_fee }} <span class="text-xs">BDT</span></p>
                            </div>
                            <div class="bg-red-50 p-4 rounded-xl border border-red-100">
                                <p class="text-[10px] font-bold text-red-600 uppercase mb-1">Current Due</p>
                                <p class="text-xl font-black text-red-700">0 <span class="text-xs">BDT</span></p>
                            </div>
                        </div>
                        
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b pb-2 pt-2">Contact Info</h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-blue-50 text-blue-500 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">Mobile Number</p>
                                    <p class="text-sm font-semibold text-gray-700">{{ client.mobile }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-sky-50 text-sky-500 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">Email Address</p>
                                    <p class="text-sm font-semibold text-gray-700">{{ client.email || 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-indigo-50 text-indigo-500 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">Address</p>
                                    <p class="text-sm font-semibold text-gray-700 leading-tight">{{ client.address || 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Details & Charts -->
                <div class="md:col-span-8 space-y-6">
                    <!-- Tabs -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex p-1">
                        <button v-for="tab in tabs" :key="tab.id" @click="activeTab = tab.id" 
                            :class="activeTab === tab.id ? 'bg-[#0f3443] text-white' : 'text-gray-500 hover:bg-gray-50'"
                            class="flex-1 py-2 text-xs font-bold rounded-lg transition-all uppercase tracking-wide">
                            {{ tab.label }}
                        </button>
                    </div>

                    <!-- Tab Content -->
                    <div v-show="activeTab === 'service'" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                        <div class="grid grid-cols-2 gap-12">
                            <div class="space-y-6">
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b pb-2">Subscription Details</h4>
                                <div class="grid grid-cols-2 gap-y-4 text-xs">
                                    <span class="text-gray-400 font-medium">Package Name:</span>
                                    <span class="text-gray-800 font-bold">{{ client.package?.name || 'Standard' }}</span>
                                    
                                    <span class="text-gray-400 font-medium">Service Profile:</span>
                                    <span class="text-gray-800 font-bold uppercase">{{ client.service_profile?.name || 'N/A' }}</span>
                                    
                                    <span class="text-gray-400 font-medium">Monthly Fee:</span>
                                    <span class="text-blue-600 font-bold">{{ client.monthly_fee }} BDT</span>
                                    
                                    <span class="text-gray-400 font-medium">Expire Date:</span>
                                    <span class="text-red-500 font-bold">{{ client.expire_date }}th of month</span>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b pb-2">Network Configuration</h4>
                                <div class="grid grid-cols-2 gap-y-4 text-xs">
                                    <span class="text-gray-400 font-medium">Protocol Type:</span>
                                    <span class="text-gray-800 font-bold">{{ client.protocol_type || 'IPv4' }}</span>
                                    
                                    <span class="text-gray-400 font-medium">Connection Type:</span>
                                    <span class="text-gray-800 font-bold uppercase">{{ client.connection_type || 'PPPoE' }}</span>
                                    
                                    <span class="text-gray-400 font-medium">Server IP:</span>
                                    <span class="text-gray-800 font-bold">{{ client.server?.ip_address || 'N/A' }}</span>
                                    
                                    <span class="text-gray-400 font-medium">MAC Address:</span>
                                    <span class="text-gray-800 font-bold font-mono">{{ client.device_mac_serial || 'N/A' }}</span>

                                    <span class="text-gray-400 font-medium">PPPoE Username:</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-800 font-bold font-mono bg-yellow-50 px-2 py-0.5 rounded text-yellow-700 border border-yellow-100">{{ client.username }}</span>
                                        <button @click="copyToClipboard(client.username, 'Username')" class="text-gray-400 hover:text-gray-600 focus:outline-none" title="Copy Username">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        </button>
                                    </div>

                                    <span class="text-gray-400 font-medium">PPPoE Password:</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-800 font-bold font-mono bg-red-50 px-2 py-0.5 rounded text-red-600 border border-red-100 min-w-[80px]">
                                            {{ showPassword ? client.password : '••••••••' }}
                                        </span>
                                        <button @click="showPassword = !showPassword" class="text-gray-400 hover:text-gray-600 focus:outline-none" title="Toggle Password">
                                            <svg v-if="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                        </button>
                                        <button @click="copyToClipboard(client.password, 'Password')" class="text-gray-400 hover:text-gray-600 focus:outline-none" title="Copy Password">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Network Monitoring Tab -->
                    <div v-show="activeTab === 'monitoring'" class="space-y-6">
                        <SignalTrendChart 
                            v-if="oltUser" 
                            :olt-user-id="oltUser.id" 
                            title="Signal Strength Monitoring" 
                            subtitle="Last 24 hours of connectivity telemetry"
                        />
                        <div v-else class="bg-white rounded-xl shadow-sm border border-gray-100 p-20 text-center">
                            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 text-blue-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-700">No OLT Data Linked</h3>
                            <p class="text-xs text-gray-400 mt-1 max-w-xs mx-auto">This client is not currently linked to an OLT device. Please link a serial number to enable signal monitoring.</p>
                            <button class="mt-6 px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition-all uppercase tracking-wide">
                                Link OLT Device
                            </button>
                        </div>
                    </div>

                    <div v-show="activeTab === 'documents'" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                        <div class="grid grid-cols-2 gap-8">
                            <div>
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase mb-4">Identity Document (NID)</h4>
                                <div class="aspect-video bg-gray-50 rounded-lg border border-dashed border-gray-200 overflow-hidden group relative">
                                    <img v-if="client.nid_pic" :src="`/storage/${client.nid_pic}`" class="w-full h-full object-cover">
                                    <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    </div>
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                                        <a v-if="client.nid_pic" :href="`/storage/${client.nid_pic}`" target="_blank" class="px-4 py-2 bg-white rounded-lg text-xs font-bold text-gray-800">View Full Size</a>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase mb-4">Registration Form</h4>
                                <div class="aspect-video bg-gray-50 rounded-lg border border-dashed border-gray-200 overflow-hidden group relative">
                                    <img v-if="client.res_form_pic" :src="`/storage/${client.res_form_pic}`" class="w-full h-full object-cover">
                                    <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                                        <a v-if="client.res_form_pic" :href="`/storage/${client.res_form_pic}`" target="_blank" class="px-4 py-2 bg-white rounded-lg text-xs font-bold text-gray-800">View Full Size</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div v-else class="bg-white rounded-xl p-12 text-center border border-gray-100 shadow-sm max-w-xl mx-auto">
             <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                 <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
             </div>
             <h2 class="text-xl font-bold text-gray-800">Client Not Found</h2>
             <p class="text-sm text-gray-400 mt-2">The client you are looking for does not exist or has been removed from the system.</p>
             <button @click="$router.push('/client/all')" class="mt-6 px-6 py-2 bg-[#0f3443] text-white rounded-lg text-xs font-bold hover:bg-[#0a2530] transition-all uppercase tracking-wide">
                Back to List
             </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import Swal from 'sweetalert2';
import SignalTrendChart from '../../components/SignalTrendChart.vue';

const route = useRoute();
const router = useRouter();
const loading = ref(true);
const client = ref(null);
const oltUser = ref(null);
const activeTab = ref('service');
const showPassword = ref(false);

const tabs = [
    { id: 'service', label: 'Subscription & Network' },
    { id: 'monitoring', label: 'Live Monitoring' },
    { id: 'documents', label: 'Documents & KYCs' },
    { id: 'billing', label: 'Billing History' }
];

const fetchClientData = async () => {
    try {
        loading.value = true;
        const response = await axios.get(`/api/config/clients/${route.params.id}`);
        client.value = response.data;
        
        // Attempt to find linked OLT user by device_mac_serial if it exists
        if (client.value.device_mac_serial) {
            try {
                const oltUserRes = await axios.get('/api/config/olt-users', {
                    params: { search: client.value.device_mac_serial }
                });
                if (oltUserRes.data.data && oltUserRes.data.data.length > 0) {
                    oltUser.value = oltUserRes.data.data[0];
                }
            } catch (e) {
                console.warn('OLT user lookup failed');
            }
        }
    } catch (error) {
        console.error('Failed to fetch client profile', error);
    } finally {
        loading.value = false;
    }
};

const copyToClipboard = async (text, label) => {
    try {
        await navigator.clipboard.writeText(text);
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        Toast.fire({
            icon: 'success',
            title: `${label} copied to clipboard`
        });
    } catch (err) {
        console.error('Failed to copy: ', err);
    }
};

onMounted(() => {
    fetchClientData();
});
</script>

<style scoped>
/* Translucent card effects */
.bg-white-translucent {
    backdrop-filter: blur(8px);
}
</style>
