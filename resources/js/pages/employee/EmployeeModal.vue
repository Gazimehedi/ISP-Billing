<template>
    <Teleport to="body">
        <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto w-full h-full bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-5xl rounded-2xl shadow-xl border border-gray-100 transform transition-all">
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50/50 rounded-t-2xl">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <span v-if="isEditMode">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit Employee
                    </span>
                    <span v-else>
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Add Employee
                    </span>
                </h3>
                <button @click="close" class="text-gray-400 hover:text-gray-600 transition-colors bg-white hover:bg-red-50 p-1.5 rounded-lg border border-transparent hover:border-red-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Body -->
            <form @submit.prevent="submit" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    
                    <!-- Col 1 -->
                    <div class="space-y-4">
                        <div class="relative">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                            <input v-model="form.name" type="text" placeholder="Enter full name" class="w-full pl-3 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all placeholder-gray-400" required>
                        </div>

                        <div class="relative">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input v-model="form.password" :type="showPassword ? 'text' : 'password'" placeholder="Enter password" class="w-full pl-3 pr-10 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all placeholder-gray-400" :required="!isEditMode">
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                                    <svg v-if="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                            <select v-model="form.gender" class="w-full pl-3 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all appearance-none cursor-pointer" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Salary Deduction</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-400 text-xs">৳</span>
                                <input v-model="form.salary_deduction" type="number" placeholder="0.00" class="w-full pl-7 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">District</label>
                            <input v-model="form.district" type="text" placeholder="Enter district" class="w-full pl-3 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Reference Name</label>
                            <input v-model="form.reference_name" type="text" placeholder="Reference person" class="w-full pl-3 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                        </div>
                    </div>

                    <!-- Col 2 -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                            <select v-model="form.role_id" class="w-full pl-3 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all appearance-none cursor-pointer" required>
                                <option value="" disabled>Select Role...</option>
                                <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Father Name</label>
                            <input v-model="form.father_name" type="text" placeholder="Enter father's name" class="w-full pl-3 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Salary</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-400 text-xs">৳</span>
                                <input v-model="form.salary" type="number" placeholder="0.00" class="w-full pl-7 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Salary Type <span class="text-red-500">*</span></label>
                             <select v-model="form.salary_type" class="w-full pl-3 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all appearance-none cursor-pointer" required>
                                <option value="Monthly">Monthly</option>
                                <option value="Daily">Daily</option>
                                <option value="Contractual">Contractual</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Thana</label>
                             <input v-model="form.thana" type="text" placeholder="Enter thana" class="w-full pl-3 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Reference Address</label>
                            <input v-model="form.reference_address" type="text" placeholder="Reference address" class="w-full pl-3 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                        </div>
                        
                         <!-- Status Toggle -->
                         <div class="pt-2">
                             <label class="block text-xs font-bold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                             <div class="flex items-center gap-4">
                                 <label class="flex items-center gap-2 cursor-pointer">
                                     <input type="radio" v-model="form.status" :value="true" class="text-teal-500 focus:ring-teal-500 h-4 w-4">
                                     <span class="text-xs font-bold text-gray-600 bg-teal-50 px-2.5 py-1 rounded-md border border-teal-100">ACTIVE</span>
                                 </label>
                                 <label class="flex items-center gap-2 cursor-pointer">
                                     <input type="radio" v-model="form.status" :value="false" class="text-red-500 focus:ring-red-500 h-4 w-4">
                                     <span class="text-xs font-bold text-gray-600 bg-red-50 px-2.5 py-1 rounded-md border border-red-100">INACTIVE</span>
                                 </label>
                             </div>
                         </div>
                    </div>

                    <!-- Col 3 -->
                    <div class="space-y-4">
                        <div class="relative">
                             <label class="block text-xs font-bold text-gray-700 mb-1">Mobile <span class="text-red-500">*</span></label>
                             <div class="flex">
                                 <div class="bg-gray-100 border border-gray-200 border-r-0 rounded-l-lg px-2 flex items-center justify-center">
                                     <img src="https://flagcdn.com/w20/bd.png" class="w-4 h-3 rounded-sm shadow-sm" alt="BD">
                                 </div>
                                 <input v-model="form.mobile" type="tel" placeholder="01XXXXXXXXX" class="w-full pl-3 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-r-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-mono" required>
                             </div>
                        </div>

                         <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">NID <span class="text-red-500">*</span></label>
                            <input v-model="form.nid" type="text" placeholder="National ID Number" class="w-full pl-3 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-mono" required>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Due</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-400 text-xs">৳</span>
                                <input v-model="form.due" type="number" placeholder="0.00" class="w-full pl-7 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Division</label>
                            <input v-model="form.division" type="text" placeholder="Enter division" class="w-full pl-3 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Address</label>
                             <textarea v-model="form.address" rows="1" placeholder="Enter full address" class="w-full pl-3 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all"></textarea>
                        </div>

                        <div>
                             <label class="block text-xs font-bold text-gray-700 mb-1">Reference Mobile</label>
                             <input v-model="form.reference_mobile" type="tel" placeholder="Reference mobile" class="w-full pl-3 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" @click="resetForm" class="px-5 py-2 text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors border border-gray-200">
                        Reset
                    </button>
                    <button type="submit" class="px-6 py-2 text-xs font-bold text-white bg-slate-800 hover:bg-slate-900 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 border border-slate-700 flex items-center gap-2">
                        <span v-if="loading" class="animate-spin h-3 w-3 border-2 border-white/30 border-t-white rounded-full"></span>
                        {{ isEditMode ? 'Update Employee' : 'Submit' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    </Teleport>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({
    isOpen: Boolean,
    editData: Object
});

const emit = defineEmits(['close', 'refresh']);

const loading = ref(false);
const showPassword = ref(false);
const roles = ref([]);
const isEditMode = computed(() => !!props.editData && !!props.editData.id);

const form = reactive({
    name: '',
    role_id: '',
    mobile: '',
    password: '',
    father_name: '',
    nid: '',
    gender: 'Male',
    salary: '',
    due: '',
    salary_deduction: '',
    salary_type: 'Monthly',
    division: '',
    district: '',
    thana: '',
    address: '',
    reference_name: '',
    reference_address: '',
    reference_mobile: '',
    status: true
});

onMounted(async () => {
    try {
        const res = await axios.get('/api/config/roles');
        roles.value = res.data;
    } catch (e) {
        console.error("Failed to fetch roles");
    }
});

watch(() => props.isOpen, (newVal) => {
    if (newVal) {
        if (props.editData && props.editData.id) {
            // Edit Mode - Populate Form
            Object.assign(form, props.editData);
            form.password = ''; // Don't fill password on edit
            form.role_id = props.editData.role_id || (props.editData.role ? props.editData.role.id : '');
        } else {
            resetForm();
        }
    }
});

const resetForm = () => {
    Object.assign(form, {
        name: '', role_id: '', mobile: '', password: '', father_name: '', nid: '',
        gender: 'Male', salary: '', due: '', salary_deduction: '', salary_type: 'Monthly',
        division: '', district: '', thana: '', address: '',
        reference_name: '', reference_address: '', reference_mobile: '', status: true
    });
};

const close = () => {
    emit('close');
    resetForm();
};

const submit = async () => {
    loading.value = true;
    try {
        if (isEditMode.value) {
            await axios.put(`/api/config/employees/${props.editData.id}`, form);
            Swal.fire('Updated!', 'Employee details updated successfully.', 'success');
        } else {
            await axios.post('/api/config/employees', form);
            Swal.fire('Created!', 'New employee added successfully.', 'success');
        }
        emit('refresh');
        close();
    } catch (error) {
        if (error.response?.status === 422) {
             const errors = error.response.data.errors;
             let errorMsg = '';
             Object.values(errors).forEach(err => errorMsg += err.join('<br>') + '<br>');
             Swal.fire({
                title: 'Validation Error',
                html: errorMsg,
                icon: 'error'
             });
        } else {
             const msg = error.response?.data?.message || 'Something went wrong';
             Swal.fire('Error', msg, 'error');
        }
    } finally {
        loading.value = false;
    }
};
</script>
