<template>
    <div class="space-y-6 p-4 bg-[#f8fafc] min-h-screen">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Service Profiles :</h2>
                    <span class="bg-[#e8f5e9] text-[#2e7d32] px-2 py-0.5 rounded border border-[#c8e6c9] text-xs font-bold">
                        {{ pagination.total || 0 }}
                    </span>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <div class="relative min-w-[250px]">
                        <input 
                            v-model="searchQuery" 
                            type="text" 
                            placeholder="Search profiles..." 
                            class="w-full pl-9 pr-3 py-1.5 bg-[#fcfcfc] border border-gray-200 rounded text-xs focus:outline-none focus:border-cyan-500 transition-all font-medium"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 absolute left-3 top-2 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>

                    <button @click="fetchProfiles" class="p-1.5 border border-green-500 text-green-500 rounded hover:bg-green-50 transition-colors" title="Refresh">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>

                    <button @click="openModal()" class="flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white rounded text-xs font-bold hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Profile
                    </button>
                </div>
            </div>
        </div>

        <!-- Profiles Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 min-h-[500px] flex flex-col">
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left">
                    <thead class="bg-[#f1f5f9] border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-[11px] font-bold text-gray-600 uppercase">Name</th>
                            <th class="px-4 py-3 text-[11px] font-bold text-gray-600 uppercase">Bandwidth</th>
                            <th class="px-4 py-3 text-[11px] font-bold text-gray-600 uppercase">VLAN Mode</th>
                            <th class="px-4 py-3 text-[11px] font-bold text-gray-600 uppercase">VLAN ID</th>
                            <th class="px-4 py-3 text-[11px] font-bold text-gray-600 uppercase">Priority</th>
                            <th class="px-4 py-3 text-[11px] font-bold text-gray-600 uppercase">Status</th>
                            <th class="px-4 py-3 text-[11px] font-bold text-gray-600 uppercase text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-if="loading" class="animate-pulse">
                            <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-400">Loading...</td>
                        </tr>
                        <tr v-else-if="profiles.length === 0">
                            <td colspan="7" class="px-4 py-20">
                                <div class="max-w-md mx-auto bg-[#fffbf0] border border-[#fef3c7] rounded-2xl p-8 flex flex-col items-center text-center shadow-sm">
                                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center border border-[#fef3c7] mb-4 text-[#d97706]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-[#92400e] mb-2">No Profiles Found</h3>
                                    <p class="text-xs text-[#b45309] leading-relaxed">Create your first service profile to get started.</p>
                                </div>
                            </td>
                        </tr>
                        <tr v-for="profile in profiles" :key="profile.id" class="hover:bg-blue-50/20 transition-colors group">
                            <td class="px-4 py-3">
                                <div class="text-xs font-bold text-gray-800">{{ profile.name }}</div>
                                <div class="text-[10px] text-gray-400 font-medium">{{ profile.description || 'No description' }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs font-semibold text-gray-600">
                                <div>↑ {{ profile.bandwidth_up }} Mbps</div>
                                <div>↓ {{ profile.bandwidth_down }} Mbps</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-[10px] font-bold uppercase">{{ profile.vlan_mode }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs font-medium text-gray-500">{{ profile.vlan_id || 'N/A' }}</td>
                            <td class="px-4 py-3 text-xs font-medium text-gray-500">{{ profile.priority }}</td>
                            <td class="px-4 py-3">
                                <span :class="profile.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'" class="px-2 py-0.5 rounded text-[10px] font-bold">
                                    {{ profile.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button @click="openModal(profile)" class="p-1 text-gray-400 hover:text-blue-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                    <button @click="deleteProfile(profile)" class="p-1 text-gray-400 hover:text-red-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Profile Modal -->
        <div v-if="showModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="closeModal"></div>
            <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">{{ editingProfile ? 'Edit' : 'Add' }} Service Profile</h3>
                    <button @click="closeModal" class="text-gray-400 hover:text-red-500 p-1.5 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase block mb-1">Profile Name *</label>
                            <input v-model="form.name" type="text" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500 transition-all">
                        </div>
                        <div class="col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase block mb-1">Description</label>
                            <textarea v-model="form.description" rows="2" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500 transition-all"></textarea>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase block mb-1">Upload Speed (Mbps) *</label>
                            <input v-model.number="form.bandwidth_up" type="number" min="1" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500 transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase block mb-1">Download Speed (Mbps) *</label>
                            <input v-model.number="form.bandwidth_down" type="number" min="1" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500 transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase block mb-1">VLAN Mode *</label>
                            <select v-model="form.vlan_mode" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500 transition-all">
                                <option value="transparent">Transparent</option>
                                <option value="tag">Tag</option>
                                <option value="translation">Translation</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase block mb-1">VLAN ID</label>
                            <input v-model.number="form.vlan_id" type="number" min="1" max="4094" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500 transition-all">
                        </div>
                        <div v-if="form.vlan_mode === 'translation'">
                            <label class="text-[11px] font-bold text-gray-500 uppercase block mb-1">Customer VLAN</label>
                            <input v-model.number="form.cvlan_id" type="number" min="1" max="4094" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500 transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase block mb-1">QoS Priority (0-7)</label>
                            <input v-model.number="form.priority" type="number" min="0" max="7" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500 transition-all">
                        </div>
                        <div class="col-span-2 flex items-center gap-2">
                            <input v-model="form.is_active" type="checkbox" class="rounded text-cyan-600 focus:ring-cyan-500">
                            <label class="text-xs font-semibold text-gray-700">Active Profile</label>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex items-center justify-end gap-3">
                    <button @click="closeModal" class="px-6 py-2 border border-gray-300 rounded-lg text-xs font-bold text-gray-600 hover:bg-white transition-colors uppercase">Cancel</button>
                    <button @click="saveProfile" class="px-8 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition-all uppercase shadow-md active:scale-95">Save Profile</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import Swal from 'sweetalert2';
import axios from 'axios';

const profiles = ref([]);
const loading = ref(false);
const searchQuery = ref('');
const pagination = ref({ current_page: 1, last_page: 0, total: 0 });

const showModal = ref(false);
const editingProfile = ref(null);
const form = ref({
    name: '',
    description: '',
    bandwidth_up: 100,
    bandwidth_down: 100,
    vlan_mode: 'transparent',
    vlan_id: null,
    cvlan_id: null,
    priority: 0,
    is_active: true
});

const fetchProfiles = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/config/service-profiles', {
            params: { search: searchQuery.value }
        });
        profiles.value = response.data.data;
        pagination.value = {
            current_page: response.data.current_page,
            last_page: response.data.last_page,
            total: response.data.total
        };
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

const openModal = (profile = null) => {
    if (profile) {
        editingProfile.value = profile;
        form.value = { ...profile };
    } else {
        editingProfile.value = null;
        form.value = {
            name: '',
            description: '',
            bandwidth_up: 100,
            bandwidth_down: 100,
            vlan_mode: 'transparent',
            vlan_id: null,
            cvlan_id: null,
            priority: 0,
            is_active: true
        };
    }
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingProfile.value = null;
};

const saveProfile = async () => {
    try {
        if (editingProfile.value) {
            await axios.put(`/api/config/service-profiles/${editingProfile.value.id}`, form.value);
            Swal.fire('Success', 'Profile updated successfully', 'success');
        } else {
            await axios.post('/api/config/service-profiles', form.value);
            Swal.fire('Success', 'Profile created successfully', 'success');
        }
        closeModal();
        fetchProfiles();
    } catch (error) {
        Swal.fire('Error', error.response?.data?.message || 'Failed to save profile', 'error');
    }
};

const deleteProfile = async (profile) => {
    const result = await Swal.fire({
        title: 'Delete Profile?',
        text: `Are you sure you want to delete "${profile.name}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete'
    });

    if (result.isConfirmed) {
        try {
            await axios.delete(`/api/config/service-profiles/${profile.id}`);
            Swal.fire('Deleted!', 'Profile deleted successfully', 'success');
            fetchProfiles();
        } catch (error) {
            Swal.fire('Error', error.response?.data?.message || 'Failed to delete profile', 'error');
        }
    }
};

watch(searchQuery, (val) => {
    if (val === '') fetchProfiles();
});

onMounted(() => {
    fetchProfiles();
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
