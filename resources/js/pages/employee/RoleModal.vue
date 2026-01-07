<template>
    <Teleport to="body">
        <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto w-full h-full bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl border border-gray-100 transform transition-all relative">
                <!-- Header -->
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50/50 rounded-t-2xl">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <span v-if="isEditMode">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit Role
                        </span>
                        <span v-else>
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            Add Role
                        </span>
                    </h3>
                    <button @click="close" class="text-gray-400 hover:text-gray-600 transition-colors bg-white hover:bg-red-50 p-1.5 rounded-lg border border-transparent hover:border-red-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Body -->
                <form @submit.prevent="submit" class="p-6">
                    <div class="space-y-4">
                        <div class="relative">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Role Name <span class="text-red-500">*</span></label>
                            <input v-model="form.name" type="text" placeholder="e.g. Manager, Technician" class="w-full pl-3 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all placeholder-gray-400" required>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                         <button type="button" @click="close" class="px-5 py-2 text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors border border-gray-200">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 text-xs font-bold text-white bg-slate-800 hover:bg-slate-900 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 border border-slate-700 flex items-center gap-2">
                            <span v-if="loading" class="animate-spin h-3 w-3 border-2 border-white/30 border-t-white rounded-full"></span>
                            {{ isEditMode ? 'Update Role' : 'Create Role' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({
    isOpen: Boolean,
    editData: Object
});

const emit = defineEmits(['close', 'refresh']);

const loading = ref(false);
const isEditMode = computed(() => !!props.editData && !!props.editData.id);

const form = reactive({
    name: ''
});

watch(() => props.isOpen, (newVal) => {
    if (newVal) {
        if (props.editData && props.editData.id) {
            form.name = props.editData.name;
        } else {
            resetForm();
        }
    }
});

const resetForm = () => {
    form.name = '';
};

const close = () => {
    emit('close');
    resetForm();
};

const submit = async () => {
    loading.value = true;
    try {
        if (isEditMode.value) {
            await axios.put(`/api/config/roles/${props.editData.id}`, form);
            Swal.fire('Updated!', 'Role has been updated.', 'success');
        } else {
            await axios.post('/api/config/roles', form);
            Swal.fire('Created!', 'Role has been created.', 'success');
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
