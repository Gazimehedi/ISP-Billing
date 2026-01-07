<template>
    <div class="h-[calc(100vh-5rem)] flex flex-col p-4 bg-gray-50/50">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex-none h-auto md:h-16 mb-4">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-50 p-2.5 rounded-xl">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-800 tracking-tight">Role Management</h1>
                    <p class="text-xs text-gray-500 font-medium">Manage employee roles and permissions</p>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                 <button @click="openAddModal" class="ml-auto md:ml-0 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2 transform hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Role
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="flex-1 overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col">
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/80 sticky top-0 z-10 backdrop-blur-sm">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[100px]">ID</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="loading" v-for="n in 3" :key="n">
                            <td class="px-6 py-4"><div class="h-4 w-8 bg-gray-100 rounded animate-pulse"></div></td>
                            <td class="px-6 py-4"><div class="h-4 w-24 bg-gray-100 rounded animate-pulse"></div></td>
                            <td class="px-6 py-4"><div class="h-4 w-20 bg-gray-100 rounded animate-pulse"></div></td>
                             <td class="px-6 py-4"><div class="h-4 w-12 bg-gray-100 rounded animate-pulse ml-auto"></div></td>
                        </tr>
                        <tr v-else-if="roles.length === 0">
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400 text-sm">No roles found</td>
                        </tr>
                        <tr v-else v-for="role in roles" :key="role.id" class="group hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-xs font-mono text-gray-400">#{{ role.id }}</td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-600 transition-colors">{{ role.name }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 font-mono text-xs">{{ role.slug }}</td>
                            <td class="px-6 py-4 text-right">
                                 <div class="flex items-center justify-end gap-2 group-hover:opacity-100 transition-opacity">
                                    <button @click="editRole(role)" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button @click="deleteRole(role.id)" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <RoleModal :is-open="isModalOpen" :edit-data="editRoleData" @close="isModalOpen = false" @refresh="fetchRoles" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import RoleModal from './RoleModal.vue';

const roles = ref([]);
const loading = ref(false);
const isModalOpen = ref(false);
const editRoleData = ref({});

const fetchRoles = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/api/config/roles');
        roles.value = res.data;
    } catch (e) {
        console.error(e);
    } finally {
        loading.value = false;
    }
};

const openAddModal = () => {
    editRoleData.value = {};
    isModalOpen.value = true;
};

const editRole = (role) => {
    editRoleData.value = { ...role };
    isModalOpen.value = true;
};

const deleteRole = (id) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/config/roles/${id}`);
                Swal.fire('Deleted!', 'Role has been deleted.', 'success');
                fetchRoles();
            } catch (error) {
                 Swal.fire('Error!', 'Something went wrong.', 'error');
            }
        }
    });
};

onMounted(() => {
    fetchRoles();
});
</script>
