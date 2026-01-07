<template>
    <div class="p-6 max-w-[1600px] mx-auto space-y-6">
        <!-- Header Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Employees</div>
                    <div class="text-2xl font-black text-slate-800 mt-1">{{ stats.count || 0 }}</div>
                </div>
                <div class="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Salary</div>
                    <div class="text-2xl font-black text-emerald-600 mt-1">৳{{ stats.total_salary }}</div>
                </div>
                <div class="h-10 w-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Due</div>
                    <div class="text-2xl font-black text-red-500 mt-1">৳{{ stats.total_due }}</div>
                </div>
                <div class="h-10 w-10 rounded-xl bg-red-50 flex items-center justify-center text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Toolbar -->
            <div class="p-4 border-b border-gray-100 flex flex-col lg:flex-row gap-4 justify-between items-center bg-gray-50/30">
                <div class="flex items-center gap-3 w-full lg:w-auto">
                    <div class="relative w-full lg:w-64">
                         <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                             <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                         </span>
                         <input v-model="search" type="text" placeholder="Search employees..." class="pl-9 pr-4 py-2 w-full text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all bg-white shadow-sm">
                    </div>
                    
                    <select v-model="filters.role" class="px-3 py-2 text-sm border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm">
                        <option value="">All Roles</option>
                        <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                    </select>
                </div>

                <div class="flex items-center gap-3">
                    <button @click="fetchEmployees" class="p-2 text-gray-500 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-all border border-transparent hover:border-teal-100" title="Refresh">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </button>
                    <button @click="openAddModal" class="flex items-center gap-2 px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-xl shadow-lg shadow-teal-500/20 hover:shadow-teal-500/30 transition-all transform hover:-translate-y-0.5 text-xs font-bold uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Employee
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 text-gray-500 uppercase text-[10px] font-bold tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-center w-12">#</th>
                            <th class="px-4 py-3">Name / Contact</th>
                            <th class="px-4 py-3 text-right">Salary</th>
                            <th class="px-4 py-3 text-right">Due</th>
                            <th class="px-4 py-3 text-center">Role</th>
                            <th class="px-4 py-3 text-center">Type</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-if="loading" v-for="i in 5" :key="'skel-'+i" class="animate-pulse">
                            <td class="px-4 py-3"><div class="h-3 bg-gray-100 rounded w-6 mx-auto"></div></td>
                            <td class="px-4 py-3"><div class="h-8 bg-gray-100 rounded w-48"></div></td>
                            <td class="px-4 py-3"><div class="h-3 bg-gray-100 rounded w-20 ml-auto"></div></td>
                            <td class="px-4 py-3"><div class="h-3 bg-gray-100 rounded w-16 ml-auto"></div></td>
                            <td class="px-4 py-3"><div class="h-5 bg-gray-100 rounded w-20 mx-auto"></div></td>
                            <td class="px-4 py-3"><div class="h-5 bg-gray-100 rounded w-24 mx-auto"></div></td>
                            <td class="px-4 py-3"><div class="h-5 bg-gray-100 rounded w-16 mx-auto"></div></td>
                            <td class="px-4 py-3"><div class="h-8 bg-gray-100 rounded w-8 ml-auto"></div></td>
                        </tr>
                        
                        <tr v-else-if="employees.length === 0">
                            <td colspan="8" class="px-4 py-12 text-center text-gray-400 text-sm">No employees found.</td>
                        </tr>

                        <tr v-else v-for="(emp, idx) in employees" :key="emp.id" class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-4 py-3 text-center text-xs font-bold text-gray-400">
                                {{ meta.from + idx }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs uppercase border border-indigo-100">
                                        {{ emp.name.substring(0,2) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-700 text-sm">{{ emp.name }}</div>
                                        <div class="text-[11px] text-gray-400 font-mono">{{ emp.mobile }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-sm font-bold text-gray-600">
                                {{ Number(emp.salary).toLocaleString() }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-sm font-bold text-red-500">
                                {{ Number(emp.due).toLocaleString() }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide bg-blue-50 text-blue-600 border border-blue-100">
                                    {{ emp.role?.name }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide bg-amber-50 text-amber-600 border border-amber-100">
                                    {{ emp.salary_type }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span v-if="emp.status" class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    Active
                                </span>
                                <span v-else class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide bg-gray-100 text-gray-500 border border-gray-200">
                                    Inactive
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2 group-hover:opacity-100 transition-opacity">
                                    <button @click="editEmployee(emp)" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button @click="deleteEmployee(emp.id)" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/30 flex items-center justify-between">
                <div class="text-xs text-gray-500">
                    Showing {{ meta.from }} to {{ meta.to }} of {{ meta.total }} employees
                </div>
                <div class="flex gap-1">
                    <button @click="changePage(meta.current_page - 1)" :disabled="meta.current_page <= 1" class="px-3 py-1 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">Previous</button>
                    <button @click="changePage(meta.current_page + 1)" :disabled="meta.current_page >= meta.last_page" class="px-3 py-1 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">Next</button>
                </div>
            </div>
        </div>

        <EmployeeModal :is-open="modalOpen" :edit-data="selectedEmployee" @close="modalOpen = false" @refresh="fetchEmployees" />
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import EmployeeModal from './EmployeeModal.vue';

const employees = ref([]);
const roles = ref([]);
const loading = ref(false);
const search = ref('');
const filters = ref({ role: '' });
const modalOpen = ref(false);
const selectedEmployee = ref(null);
const stats = ref({});
const meta = ref({ current_page: 1, from: 0, to: 0, total: 0 });

const fetchEmployees = async (page = 1) => {
    loading.value = true;
    try {
        const res = await axios.get('/api/config/employees', {
            params: {
                page,
                search: search.value,
                role_id: filters.value.role
            }
        });
        employees.value = res.data.employees.data;
        stats.value = res.data.stats;
        meta.value = {
            current_page: res.data.employees.current_page,
            last_page: res.data.employees.last_page,
            total: res.data.employees.total,
            from: res.data.employees.from,
            to: res.data.employees.to
        };
    } catch (e) {
        console.error("Failed to fetch employees");
    } finally {
        loading.value = false;
    }
};

const fetchRoles = async () => {
    try {
        const res = await axios.get('/api/config/roles');
        roles.value = res.data;
    } catch (e) {}
};

const openAddModal = () => {
    selectedEmployee.value = null;
    modalOpen.value = true;
};

const editEmployee = (emp) => {
    selectedEmployee.value = { ...emp };
    modalOpen.value = true;
};

const deleteEmployee = async (id) => {
    const result = await Swal.fire({
        title: 'Delete Employee?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Yes, delete it'
    });

    if (result.isConfirmed) {
        try {
            await axios.delete(`/api/config/employees/${id}`);
            Swal.fire('Deleted!', 'Employee has been removed.', 'success');
            fetchEmployees();
        } catch (e) {
            Swal.fire('Error', 'Failed to delete employee.', 'error');
        }
    }
};

const changePage = (page) => {
    if (page >= 1 && page <= meta.value.last_page) {
        fetchEmployees(page);
    }
};

let debounceTimer = null;
watch([search, () => filters.value.role], () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        fetchEmployees(1);
    }, 300);
});

onMounted(() => {
    fetchEmployees();
    fetchRoles();
});
</script>
