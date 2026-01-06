<template>
    <div class="p-6 bg-[#eff3f6] min-h-screen font-sans">
        <!-- Page Title -->
        <div class="flex items-center gap-2 mb-4 text-gray-700">
            <svg class="w-5 h-5 text-sky-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
            <h1 class="text-xl font-bold">Client</h1>
            <span class="text-xs text-gray-400 font-medium">Edit Client</span>
            <div class="flex-grow"></div>
            <div class="text-[10px] text-gray-400">Client / Edit Client</div>
        </div>

        <form @submit.prevent="submitClient" class="space-y-6">
            
            <!-- 1. Personal Information -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-[#0f3443] px-4 py-2 text-white">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span class="font-bold text-xs">Personal Information</span>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        <!-- Profile Pic -->
                        <div class="md:col-span-2 flex flex-col pt-2">
                             <label class="text-[10px] font-bold text-gray-400 uppercase mb-4">PROFILE PICTURE</label>
                             <div class="relative group w-36 h-36 mx-auto">
                                <div class="w-full h-full bg-gray-100 rounded-full border-2 border-gray-100 flex items-center justify-center overflow-hidden shadow-inner">
                                    <img v-if="previews.profile" :src="previews.profile" class="w-full h-full object-cover">
                                    <svg v-else class="w-20 h-20 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                                </div>
                                <button type="button" @click="$refs.profileInput.click()" class="absolute top-2 right-2 p-1.5 bg-[#0f3443] text-white rounded-full border-2 border-white shadow hover:scale-110 transition-transform">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <input type="file" @change="handleFile($event, 'profile_pic')" class="hidden" ref="profileInput">
                             </div>
                        </div>

                        <!-- Name & Occupation -->
                        <div class="md:col-span-5 space-y-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">CUSTOMER NAME <span class="text-red-500">✱</span></label>
                                <input v-model="form.name" type="text" class="input-pill-light" required>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">OCCUPATION</label>
                                <input v-model="form.occupation" type="text" class="input-pill-light">
                            </div>
                        </div>

                        <!-- Remarks -->
                        <div class="md:col-span-5 relative">
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">REMARKS/SPECIAL NOTE</label>
                            <textarea v-model="form.remarks" class="input-pill-light h-32 py-4 resize-none"></textarea>
                        </div>
                    </div>

                    <!-- Middle Row: NID, Pictures, Details -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">NID/BIRTH CERTIFICATE NO</label>
                                <input v-model="form.nid" type="text" class="input-pill-light">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">GENDER</label>
                                <select v-model="form.gender" class="select-pill-light">
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">DATE OF BIRTH</label>
                                <input v-model="form.dob" type="date" class="input-pill-light">
                            </div>
                        </div>

                        <!-- NID Picture -->
                        <div class="flex flex-col items-center">
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 self-start">NID PICTURE</label>
                            <div class="relative w-32 h-32">
                                <div class="w-full h-full bg-gray-50 rounded-full border border-gray-200 flex items-center justify-center overflow-hidden">
                                     <img v-if="previews.nid" :src="previews.nid" class="w-full h-full object-cover">
                                     <svg v-else class="w-20 h-20 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <button type="button" @click="$refs.nidInput.click()" class="absolute top-1 right-1 p-1.5 bg-[#0f3443] text-white rounded-full border-2 border-white shadow">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <input type="file" @change="handleFile($event, 'nid_pic')" class="hidden" ref="nidInput">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">REG FORM NO</label>
                                <input v-model="form.res_form_no" type="text" class="input-pill-light">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">FATHER NAME</label>
                                <input v-model="form.father_name" type="text" class="input-pill-light">
                            </div>
                        </div>

                        <!-- Reg Form Picture -->
                        <div class="flex flex-col items-center">
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 self-start">FORM PICTURE</label>
                            <div class="relative w-32 h-32">
                                <div class="w-full h-full bg-gray-50 rounded-full border border-gray-200 flex items-center justify-center overflow-hidden">
                                     <img v-if="previews.res_form" :src="previews.res_form" class="w-full h-full object-cover">
                                     <svg v-else class="w-20 h-20 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <button type="button" @click="$refs.resInput.click()" class="absolute top-1 right-1 p-1.5 bg-[#0f3443] text-white rounded-full border-2 border-white shadow">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <input type="file" @change="handleFile($event, 'res_form_pic')" class="hidden" ref="resInput">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Contact Information -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-[#0f3443] px-4 py-2 text-white">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span class="font-bold text-xs">Contact Information</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">MOBILE NUMBER <span class="text-red-500 font-bold">✱</span></label>
                                <input v-model="form.mobile" type="text" class="input-pill-light" required>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">EMAIL ADDRESS</label>
                                <input v-model="form.email" type="email" class="input-pill-light">
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">DISTRICT</label>
                                <select v-model="form.district" class="select-pill-light">
                                    <option value="">Select</option>
                                    <option v-for="d in dropdowns.districts" :key="d" :value="d">{{ d }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">PRESENT ADDRESS</label>
                            <textarea v-model="form.address" rows="3" class="input-pill-light h-20 py-2 resize-none"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Network & Product Information -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-[#0f3443] px-4 py-2 text-white">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span class="font-bold text-xs">Network & Product Information</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                         <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">SERVER <span class="text-red-500 font-bold">✱</span></label>
                            <select v-model="form.server_id" class="select-pill-light" required>
                                <option value="">Select</option>
                                <option v-for="router in dropdowns.servers" :key="router.id" :value="router.id">{{ router.name }}</option>
                            </select>
                        </div>
                        <div>
                             <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">ZONE <span class="text-red-500 font-bold">✱</span></label>
                            <select v-model="form.zone_id" @change="fetchSubZones" class="select-pill-light" required>
                                <option value="">Select</option>
                                <option v-for="zone in zones" :key="zone.id" :value="zone.id">{{ zone.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">CONNECTION TYPE <span class="text-red-500 font-bold">✱</span></label>
                            <select v-model="form.connection_type" class="select-pill-light" required>
                                <option v-for="type in dropdowns.connection_types" :key="type.id" :value="type.id">{{ type.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Service Information -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-[#0f3443] px-4 py-2 text-white">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span class="font-bold text-xs">Service Information</span>
                    </div>
                </div>
                <div class="p-6 text-gray-600">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">PACKAGE <span class="text-red-500 font-bold">✱</span></label>
                            <select v-model="form.package_id" class="select-pill-light" required>
                                <option v-for="pkg in packages" :key="pkg.id" :value="pkg.id">{{ pkg.name }} ({{ pkg.price }} BDT)</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">USERNAME/IP</label>
                            <input v-model="form.username" type="text" class="input-pill-light" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">PASSWORD</label>
                            <input v-model="form.password" type="password" class="input-pill-light" placeholder="Leave blank to keep current">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">MONTHLY BILL <span class="text-red-500 font-bold">✱</span></label>
                            <input v-model="form.monthly_fee" type="number" class="input-pill-light" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between items-center pt-4 pb-12">
                <button type="button" @click="$router.push('/client/all')" class="px-8 py-2 bg-gray-400 text-white font-bold rounded-full hover:bg-gray-500 transition-colors text-sm uppercase tracking-wide">
                    Cancel
                </button>
                <button type="submit" :disabled="loading" class="px-8 py-2 bg-[#0f3443] text-white font-bold rounded-lg hover:bg-[#0a2530] shadow shadow-[#0f3443]/20 transition-all active:scale-95 disabled:opacity-50 flex items-center gap-2 text-sm uppercase tracking-wide">
                    <span v-if="loading" class="inline-block animate-spin w-4 h-4 border-2 border-white border-t-transparent rounded-full"></span>
                    {{ loading ? 'Updating...' : 'Update Client' }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Swal from 'sweetalert2';
import axios from 'axios';

const router = useRouter();
const route = useRoute();
const loading = ref(false);
const clientId = route.params.id;

const packages = ref([]);
const zones = ref([]);
const subZones = ref([]);
const boxes = ref([]);

const dropdowns = ref({
    districts: ['Dhaka', 'Chittagong', 'Sylhet', 'Rajshahi', 'Khulna', 'Barisal', 'Rangpur', 'Mymensingh'],
    upazilas: ['Badda', 'Gulshan', 'Uttara', 'Mirpur', 'Dhanmondi', 'Mohammadpur', 'Rampura', 'Motijheel'],
    servers: [],
    service_profiles: [],
    client_types: [],
    connection_types: []
});

const previews = ref({
    profile: null,
    nid: null,
    res_form: null
});

const form = ref({
    name: '',
    occupation: '',
    remarks: '',
    nid: '',
    gender: '',
    dob: '',
    res_form_no: '',
    father_name: '',
    mother_name: '',
    profile_pic: null,
    nid_pic: null,
    res_form_pic: null,
    mobile: '',
    email: '',
    district: '',
    address: '',
    server_id: '',
    zone_id: '',
    sub_zone_id: '',
    box_id: '',
    connection_type: 'pppoe',
    package_id: '',
    service_profile_id: '',
    username: '',
    password: '',
    monthly_fee: 0,
    joining_date: '',
    expire_date: '10'
});

const loadDropdowns = async () => {
    try {
        const [pkgRes, zoneRes, serverRes, profileRes, typeRes, connRes] = await Promise.all([
            axios.get('/api/config/packages?no_paginate=true'),
            axios.get('/api/config/zones?no_paginate=true'),
            axios.get('/api/config/mikrotik-routers?no_paginate=true'),
            axios.get('/api/config/service-profiles?no_paginate=true'),
            axios.get('/api/config/client-types?no_paginate=true'),
            axios.get('/api/config/connection-types?no_paginate=true')
        ]);
        packages.value = pkgRes.data;
        zones.value = zoneRes.data;
        dropdowns.value.servers = serverRes.data;
        dropdowns.value.service_profiles = profileRes.data;
        dropdowns.value.client_types = typeRes.data;
        dropdowns.value.connection_types = connRes.data;
    } catch (error) {
        console.error('Failed to load dropdown data', error);
    }
};

const fetchClient = async () => {
    try {
        loading.value = true;
        const response = await axios.get(`/api/config/clients/${clientId}`);
        const client = response.data;
        
        // Populate form
        Object.keys(form.value).forEach(key => {
            if (client[key] !== undefined) {
                form.value[key] = client[key];
            }
        });

        // Set previews
        if (client.profile_pic) previews.value.profile = `/storage/${client.profile_pic}`;
        if (client.nid_pic) previews.value.nid = `/storage/${client.nid_pic}`;
        if (client.res_form_pic) previews.value.res_form = `/storage/${client.res_form_pic}`;

        // Fetch subzones/boxes if zone exists
        if (form.value.zone_id) fetchSubZones();
        
    } catch (error) {
        Swal.fire('Error', 'Failed to fetch client data.', 'error');
        router.push('/client/all');
    } finally {
        loading.value = false;
    }
};

const fetchSubZones = async () => {
    if (!form.value.zone_id) return;
    try {
        const res = await axios.get(`/api/config/sub-zones?zone_id=${form.value.zone_id}&no_paginate=true`);
        subZones.value = res.data;
        const boxRes = await axios.get(`/api/config/boxes?zone_id=${form.value.zone_id}&no_paginate=true`);
        boxes.value = boxRes.data;
    } catch (error) {
        console.error('Failed to load subzones/boxes', error);
    }
};

const handleFile = (event, field) => {
    const file = event.target.files[0];
    if (file) {
        form.value[field] = file;
        const previewMap = {
            'profile_pic': 'profile',
            'nid_pic': 'nid',
            'res_form_pic': 'res_form'
        };
        if (previewMap[field]) {
            previews.value[previewMap[field]] = URL.createObjectURL(file);
        }
    }
};

const submitClient = async () => {
    loading.value = true;
    const formData = new FormData();
    
    // Method spoofing for PUT request with files
    formData.append('_method', 'PUT');

    Object.keys(form.value).forEach(key => {
        if (form.value[key] !== null && form.value[key] !== undefined) {
            // Only append files if they are File objects
            if (['profile_pic', 'nid_pic', 'res_form_pic'].includes(key)) {
                if (form.value[key] instanceof File) {
                    formData.append(key, form.value[key]);
                }
            } else {
                formData.append(key, form.value[key]);
            }
        }
    });

    try {
        await axios.post(`/api/config/clients/${clientId}`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        await Swal.fire({
            icon: 'success',
            title: 'Client Updated!',
            timer: 1500,
            showConfirmButton: false
        });
        router.push('/client/all');
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.response?.data?.message || 'Failed to update client data.'
        });
    } finally {
        loading.value = false;
    }
};

onMounted(async () => {
    await loadDropdowns();
    await fetchClient();
});
</script>

<style scoped>
.input-pill-light {
    width: 100%;
    border-radius: 1rem;
    border: 1px solid #f1f5f9;
    background-color: #f3f6f9;
    color: #374151;
    font-size: 11px;
    padding: 0.375rem 1rem;
    outline: none;
    transition: all 0.2s ease;
}
.input-pill-light:focus {
    --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
    --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) rgba(14, 165, 233, 0.5);
    box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
}

.select-pill-light {
    width: 100%;
    border-radius: 1rem;
    border: 1px solid #f1f5f9;
    background-color: #f3f6f9;
    color: #374151;
    font-size: 11px;
    padding: 0.375rem 1rem;
    outline: none;
    transition: all 0.2s ease;
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1.25em 1.25em;
}
.select-pill-light:focus {
    --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
    --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) rgba(14, 165, 233, 0.5);
    box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
}
</style>
