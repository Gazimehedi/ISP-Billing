<template>
    <div class="p-6 bg-[#eff3f6] min-h-screen font-sans">
        <div class="flex flex-col gap-6">
            <!-- Header Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                    <div class="flex items-center gap-4">
                        <div class="bg-indigo-600 p-3 rounded-xl text-white shadow-lg shadow-indigo-500/20">
                            <i class="fas fa-file-invoice-dollar text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-black text-slate-800 tracking-tight">Billing List</h1>
                        </div>
                    </div>
                </div>


                <!-- Action Buttons Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3 pt-4 border-t border-gray-100">
                    <button @click="handleAction('generate_excel')" class="btn-action bg-slate-800 hover:bg-slate-900"><i class="fas fa-file-excel mr-2 text-emerald-400"></i> Generate Excel</button>
                    <button @click="handleAction('generate_pdf')" class="btn-action bg-slate-800 hover:bg-slate-900"><i class="fas fa-file-pdf mr-2 text-red-400"></i> Generate PDF</button>
                    <button @click="handleAction('sync_clients')" class="btn-action bg-slate-800 hover:bg-slate-900"><i class="fas fa-sync mr-2 text-sky-400"></i> Sync Data</button>
                    <button @click="handleAction('disable_selected')" class="btn-action bg-slate-800 hover:bg-slate-900"><i class="fas fa-power-off mr-2 text-rose-500"></i> Disable Selected</button>
                    <button @click="handleAction('bulk_status')" class="btn-action bg-slate-800 hover:bg-slate-900"><i class="fas fa-user-check mr-2 text-amber-400"></i> Bulk Status</button>
                    <button @click="handleAction('bulk_zone')" class="btn-action bg-slate-800 hover:bg-slate-900"><i class="fas fa-map-marker-alt mr-2 text-purple-400"></i> Bulk Zone</button>
                    
                    <!-- Row 2 -->
                    <button @click="handleAction('enable_selected')" class="btn-action bg-slate-800 hover:bg-slate-900"><i class="fas fa-check-circle mr-2 text-emerald-500"></i> Enable Selected</button>
                    <button @click="handleAction('download_invoice')" class="btn-action bg-slate-800 hover:bg-slate-900"><i class="fas fa-download mr-2 text-blue-400"></i> Download Invoice</button>
                    <button @click="handleAction('sms_selected')" class="btn-action bg-slate-800 hover:bg-slate-900"><i class="fas fa-sms mr-2 text-yellow-400"></i> SMS Selected</button>
                    <button @click="handleAction('email_selected')" class="btn-action bg-slate-800 hover:bg-slate-900"><i class="fas fa-envelope mr-2 text-orange-400"></i> Email Selected</button>
                    <button @click="handleAction('info_mikrotik')" class="btn-action bg-slate-800 hover:bg-slate-900"><i class="fas fa-info-circle mr-2 text-cyan-400"></i> Info Mikrotik</button>
                    <button @click="handleAction('bulk_extend')" class="btn-action bg-slate-800 hover:bg-slate-900"><i class="fas fa-calendar-plus mr-2 text-pink-400"></i> Extend Date</button>
                </div>
            </div>
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4">
                <!-- Paid Client -->
                <div class="stat-card bg-[#00bcd4] text-white">
                    <div class="relative z-10">
                        <div class="text-white/80 text-[10px] font-black uppercase tracking-wider mb-1">Paid Client</div>
                        <div class="text-2xl font-black">{{ stats.paid_clients || 0 }}</div>
                    </div>
                    <i class="fas fa-users absolute top-2 right-2 text-4xl opacity-10"></i>
                </div>
                
                <!-- Unpaid Client -->
                <div class="stat-card bg-[#009688] text-white">
                    <div class="relative z-10">
                        <div class="text-white/80 text-[10px] font-black uppercase tracking-wider mb-1">Unpaid Client</div>
                        <div class="text-2xl font-black">{{ stats.unpaid_clients || 0 }}</div>
                    </div>
                    <i class="fas fa-user-times absolute top-2 right-2 text-4xl opacity-10"></i>
                </div>

                <!-- Received Bill -->
                <div class="stat-card bg-[#673ab7] text-white">
                    <div class="relative z-10">
                        <div class="text-white/80 text-[10px] font-black uppercase tracking-wider mb-1">Received Bill</div>
                        <div class="text-2xl font-black">{{ formatCurrency(stats.received_bill) }}</div>
                    </div>
                    <i class="fas fa-money-bill-wave absolute top-2 right-2 text-4xl opacity-10"></i>
                </div>

                <!-- Due Amount -->
                <div class="stat-card bg-[#455a64] text-white">
                    <div class="relative z-10">
                        <div class="text-white/80 text-[10px] font-black uppercase tracking-wider mb-1">Due Amount</div>
                        <div class="text-2xl font-black">{{ formatCurrency(stats.due_amount) }}</div>
                    </div>
                    <i class="fas fa-hand-holding-usd absolute top-2 right-2 text-4xl opacity-10"></i>
                </div>

                <!-- Generated Bill -->
                <div class="stat-card bg-[#03a9f4] text-white">
                    <div class="relative z-10">
                        <div class="text-white/80 text-[10px] font-black uppercase tracking-wider mb-1">Generated Bill</div>
                        <div class="text-2xl font-black">{{ formatCurrency(stats.generated_bill) }}</div>
                    </div>
                    <i class="fas fa-file-invoice absolute top-2 right-2 text-4xl opacity-10"></i>
                </div>

                <!-- Advance Amount -->
                <div class="stat-card bg-[#009688] text-white">
                    <div class="relative z-10">
                        <div class="text-white/80 text-[10px] font-black uppercase tracking-wider mb-1">Advance Amount</div>
                        <div class="text-2xl font-black">{{ formatCurrency(stats.advance_amount) }}</div>
                    </div>
                    <i class="fas fa-wallet absolute top-2 right-2 text-4xl opacity-10"></i>
                </div>

                <!-- Monthly Bill -->
                <div class="stat-card bg-[#9c27b0] text-white">
                    <div class="relative z-10">
                        <div class="text-white/80 text-[10px] font-black uppercase tracking-wider mb-1">Monthly Bill</div>
                        <div class="text-2xl font-black">{{ formatCurrency(stats.monthly_bill) }}</div>
                    </div>
                    <i class="fas fa-calendar-check absolute top-2 right-2 text-4xl opacity-10"></i>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Toolbar matching ClientList -->
                <div class="p-5 flex flex-col sm:flex-row items-center gap-3 justify-between border-b border-gray-100">
                    <!-- Left: Search Bar -->
                    <div class="relative flex-grow min-w-[200px] w-full max-w-sm group">
                        <span class="absolute inset-y-0 left-4 flex items-center">
                            <svg class="w-4 h-4 text-slate-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input 
                            v-model="search" 
                            @input="debouncedSearch"
                            type="text" 
                            placeholder="Search by name, username, mobile..." 
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 focus:bg-white outline-none transition-all placeholder:text-slate-400 font-medium"
                        >
                    </div>

                    <!-- Right: Action Buttons -->
                    <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto justify-end">
                        <button @click="fetchData" class="p-2.5 bg-white border border-slate-200 text-slate-500 rounded-xl hover:text-emerald-500 hover:border-emerald-200 hover:bg-emerald-50 transition-all shadow-sm" title="Refresh">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </button>
                        
                        <button @click="showFilterModal = true" class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl hover:text-sky-600 hover:border-sky-200 hover:bg-sky-50 transition-all text-xs font-black shadow-sm uppercase tracking-wider whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Filter
                        </button>



                        <!-- Visibility Dropdown with Click Outside -->
                        <div class="relative" ref="visibilityContainer">
                            <button @click.stop="toggleVisibilityMenu" class="flex items-center gap-2 px-4 py-2.5 bg-slate-800 text-white rounded-xl hover:bg-slate-900 transition-all text-xs font-black shadow-lg shadow-slate-900/10 uppercase tracking-wider whitespace-nowrap">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Visibility
                            </button>
                            
                            <div v-if="showVisibilityMenu" class="absolute right-0 mt-2 w-48 bg-white border border-slate-100 rounded-2xl shadow-2xl z-50 p-3 ring-1 ring-slate-900/5 animate-in fade-in slide-in-from-top-2 duration-200">
                                <div v-for="col in columns" :key="col.key" class="flex items-center gap-3 p-2 hover:bg-slate-50 rounded-xl cursor-pointer transition-colors group" @click.stop>
                                    <input type="checkbox" v-model="col.visible" class="rounded-md border-slate-200 text-emerald-500 focus:ring-emerald-500 transition-all">
                                    <span class="text-[11px] font-bold text-slate-600 group-hover:text-emerald-600 tracking-wide uppercase">{{ col.label }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 text-[10px] uppercase font-black tracking-wider border-b border-gray-200">
                                <th v-if="isColVisible('select')" class="p-4 w-10 text-center"><input type="checkbox" v-model="selectAll" @change="toggleSelectAll" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"></th>
                                <th v-if="isColVisible('client_id')" class="p-4">C.Code</th>
                                <th v-if="isColVisible('username')" class="p-4">ID/IP</th>
                                <th v-if="isColVisible('name')" class="p-4">Name</th>
                                <th v-if="isColVisible('mobile')" class="p-4">Mobile</th>
                                <th v-if="isColVisible('zone')" class="p-4">Zone</th>
                                <th v-if="isColVisible('type')" class="p-4">Type</th>
                                <th v-if="isColVisible('conn')" class="p-4">Conn</th>
                                <th v-if="isColVisible('address')" class="p-4">Address</th>
                                <th v-if="isColVisible('package')" class="p-4">Package</th>
                                <th v-if="isColVisible('speed')" class="p-4">Speed</th>
                                <th v-if="isColVisible('expire')" class="p-4">Expire</th>
                                <th v-if="isColVisible('bill')" class="p-4 text-right">M.Bill</th>
                                <th v-if="isColVisible('paid')" class="p-4 text-right">Paid</th>
                                <th v-if="isColVisible('due')" class="p-4 text-right">Due</th>
                                <th v-if="isColVisible('adv')" class="p-4 text-right">Adv</th>
                                <th v-if="isColVisible('last_pay')" class="p-4">Last Pay</th>
                                <th v-if="isColVisible('server')" class="p-4">Server</th>
                                <th v-if="isColVisible('status')" class="p-4 text-center">Status</th>
                                <th v-if="isColVisible('payment')" class="p-4 text-center">Payment</th>

                            </tr>
                        </thead>
                        <tbody class="text-xs text-slate-600 divide-y divide-gray-100">
                             <tr v-if="loading" class="text-center py-12"><td colspan="21" class="text-slate-400 italic">Loading records...</td></tr>
                             <tr v-else-if="clients.length === 0" class="text-center py-12"><td colspan="21" class="text-slate-400 italic">No matching records found</td></tr>

                            <tr v-for="client in clients" :key="client.id" class="hover:bg-slate-50 transition-colors group">
                                <td v-if="isColVisible('select')" class="p-4 border-r border-gray-50 text-center"><input type="checkbox" v-model="selectedItems" :value="client.id" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"></td>
                                <td v-if="isColVisible('client_id')" class="p-4 border-r border-gray-50 font-bold text-slate-700">{{ client.client_id_display || client.id }}</td>
                                <td v-if="isColVisible('username')" class="p-4 border-r border-gray-50">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-indigo-600">{{ client.username }}</span>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ client.remote_address || '-' }}</span>
                                    </div>
                                </td>
                                <td v-if="isColVisible('name')" class="p-4 border-r border-gray-50 font-medium">{{ client.name }}</td>
                                <td v-if="isColVisible('mobile')" class="p-4 border-r border-gray-50 font-mono">{{ client.mobile }}</td>
                                <td v-if="isColVisible('zone')" class="p-4 border-r border-gray-50">{{ client.zone?.name || '-' }}</td>
                                <td v-if="isColVisible('type')" class="p-4 border-r border-gray-50">{{ client.billing_type }}</td>
                                <td v-if="isColVisible('conn')" class="p-4 border-r border-gray-50">{{ client.connection_type }}</td>
                                <td v-if="isColVisible('address')" class="p-4 border-r border-gray-50 max-w-[150px] truncate relative group-hover:whitespace-normal group-hover:absolute group-hover:bg-white group-hover:shadow-lg group-hover:z-10 group-hover:p-2 group-hover:rounded border-l">{{ client.address }}</td>
                                <td v-if="isColVisible('package')" class="p-4 border-r border-gray-50 font-bold">{{ client.package?.name || '-' }}</td>
                                <td v-if="isColVisible('speed')" class="p-4 border-r border-gray-50">{{ client.package?.speed || '-' }}</td>
                                <td v-if="isColVisible('expire')" class="p-4 border-r border-gray-50 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ formatDate(client.expire_date) }}</span>
                                        <span v-if="daysLeft(client.expire_date) < 3" class="text-[9px] text-rose-500 font-bold">{{ daysLeft(client.expire_date) }} Days left</span>
                                        <span v-else class="text-[9px] text-emerald-500 font-bold">{{ daysLeft(client.expire_date) }} Days</span>
                                    </div>
                                </td>
                                <td v-if="isColVisible('bill')" class="p-4 border-r border-gray-50 text-right font-bold text-slate-700">{{ client.monthly_fee }}</td>
                                <td v-if="isColVisible('paid')" class="p-4 border-r border-gray-50 text-right text-emerald-600">{{ (Number(client.monthly_fee) - Number(client.monthly_fee_due)).toFixed(2) }}</td>
                                <td v-if="isColVisible('due')" class="p-4 border-r border-gray-50 text-right text-rose-500 font-black">{{ client.monthly_fee_due }}</td>
                                <td v-if="isColVisible('adv')" class="p-4 border-r border-gray-50 text-right text-indigo-600 font-bold">{{ client.balance }}</td>
                                 <td v-if="isColVisible('last_pay')" class="p-4 border-r border-gray-50 text-[10px]">
                                    {{ client.last_payment_date ? formatDateTime(client.last_payment_date) : '-' }}
                                </td>
                                <td v-if="isColVisible('server')" class="p-4 border-r border-gray-50">{{ client.server?.name || '-' }}</td>
                                <td v-if="isColVisible('status')" class="p-4 border-r border-gray-50 text-center">
                                    <div class="flex justify-center">
                                        <div :class="client.status === 'active' ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500'" class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider">
                                            {{ client.status }}
                                        </div>
                                    </div>
                                </td>
                                <td v-if="isColVisible('payment')" class="p-4 border-r border-gray-50 text-center">
                                    <div class="flex justify-center">
                                        <span 
                                            v-if="client.payment_status === 'paid'"
                                            class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-500 text-white uppercase tracking-wider shadow-sm shadow-emerald-200"
                                        >Paid</span>
                                        <span 
                                            v-else
                                            class="px-2 py-0.5 rounded text-[10px] font-black bg-rose-500 text-white uppercase tracking-wider shadow-sm shadow-rose-200"
                                        >Unpaid</span>
                                    </div>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                <div class="px-6 py-4 border-t border-gray-50 bg-white flex flex-col md:flex-row items-center justify-between text-xs text-gray-500 font-medium gap-4">
                    <div class="flex items-center gap-4">
                        <span>Rows per page:</span>
                        <select v-model="perPage" @change="fetchData" class="border-0 bg-transparent py-0 focus:ring-0 cursor-pointer font-bold text-slate-700">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                            <option value="500">500</option>
                            <option value="1000">1000</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center gap-6">
                        <span>{{ pagination.from }}-{{ pagination.to }} of {{ pagination.total }}</span>
                        <div class="flex gap-2">
                            <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page <= 1" class="px-2 py-1 disabled:opacity-30 hover:bg-slate-100 rounded transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page" class="px-2 py-1 disabled:opacity-30 hover:bg-slate-100 rounded transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-2">
                           <span>Go to page:</span>
                           <input type="number" v-model="pagination.current_page" @keyup.enter="changePage(pagination.current_page)" class="w-12 h-6 border border-gray-200 rounded text-center focus:ring-1 focus:ring-emerald-500 outline-none">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Filter Modal -->
            <div v-if="showFilterModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                    <div class="bg-[#0f4340] px-6 py-4 flex items-center justify-between">
                        <h3 class="text-white font-black uppercase tracking-widest text-sm flex items-center gap-2">
                             <i class="fas fa-filter"></i> Advanced Filter
                        </h3>
                        <button @click="showFilterModal = false" class="text-white/60 hover:text-white transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <!-- Zone -->
                        <div>
                            <label class="text-[11px] font-bold text-slate-400 uppercase mb-1.5 block">Zone</label>
                            <select v-model="filters.zone_id" class="w-full rounded-xl border border-gray-200 bg-slate-50 text-sm py-2.5 px-3 focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none transition-all">
                                <option value="">Select Zone...</option>
                                <option v-for="zone in dropdowns.zones" :key="zone.id" :value="zone.id">{{ zone.name }}</option>
                            </select>
                        </div>
                        
                         <!-- Package -->
                         <div>
                            <label class="text-[11px] font-bold text-slate-400 uppercase mb-1.5 block">Package</label>
                            <select v-model="filters.package_id" class="w-full rounded-xl border border-gray-200 bg-slate-50 text-sm py-2.5 px-3 focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none transition-all">
                                <option value="">Select Package...</option>
                                <option v-for="pkg in dropdowns.packages" :key="pkg.id" :value="pkg.id">{{ pkg.name }}</option>
                            </select>
                        </div>

                         <!-- Status -->
                         <div>
                            <label class="text-[11px] font-bold text-slate-400 uppercase mb-1.5 block">Status</label>
                            <select v-model="filters.status" class="w-full rounded-xl border border-gray-200 bg-slate-50 text-sm py-2.5 px-3 focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none transition-all">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <!-- Payment Status -->
                         <div>
                            <label class="text-[11px] font-bold text-slate-400 uppercase mb-1.5 block">Payment Status</label>
                            <select v-model="filters.payment_status" class="w-full rounded-xl border border-gray-200 bg-slate-50 text-sm py-2.5 px-3 focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none transition-all">
                                <option value="">All</option>
                                <option value="paid">Paid</option>
                                <option value="unpaid">Unpaid</option>
                            </select>
                        </div>

                    </div>
                    
                    <div class="px-6 py-4 bg-slate-50 border-t border-gray-100 flex justify-end gap-3">
                         <button @click="resetFilters" class="px-4 py-2 text-xs font-bold text-slate-500 uppercase hover:text-rose-500 transition-colors">Reset</button>
                         <button @click="applyFilters" class="px-6 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold uppercase shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 transition-all">Apply Filters</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';



const clients = ref([]);
const stats = ref({});
const loading = ref(false);
const search = ref('');
const perPage = ref(100);
const selectAll = ref(false);
const selectedItems = ref([]);

const showFilterModal = ref(false);
const showVisibilityMenu = ref(false);
const visibilityContainer = ref(null);

const filters = ref({
    zone_id: '',
    package_id: '',
    status: '',
    payment_status: ''
});

const dropdowns = ref({
    zones: [],
    packages: []
});

const columns = ref([
    { label: 'Select', key: 'select', visible: true },
    { label: 'Client Code', key: 'client_id', visible: true },
    { label: 'ID/IP', key: 'username', visible: true },
    { label: 'Name', key: 'name', visible: true },
    { label: 'Mobile', key: 'mobile', visible: true },
    { label: 'Zone', key: 'zone', visible: true },
    { label: 'Type', key: 'type', visible: true },
    { label: 'Conn', key: 'conn', visible: false }, // Hidden by default example
    { label: 'Address', key: 'address', visible: true },
    { label: 'Package', key: 'package', visible: true },
    { label: 'Speed', key: 'speed', visible: true },
    { label: 'Expire', key: 'expire', visible: true },
    { label: 'M.Bill', key: 'bill', visible: true },
    { label: 'Paid', key: 'paid', visible: true },
    { label: 'Due', key: 'due', visible: true },
    { label: 'Adv', key: 'adv', visible: true },
    { label: 'Last Pay', key: 'last_pay', visible: true },
    { label: 'Server', key: 'server', visible: false },
    { label: 'Status', key: 'status', visible: true },
    { label: 'Payment', key: 'payment', visible: true },

]);

const pagination = ref({
    current_page: 1,
    last_page: 1,
    from: 0,
    to: 0,
    total: 0
});

const isColVisible = (key) => {
    const col = columns.value.find(c => c.key === key);
    return col ? col.visible : false;
};

const fetchData = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/billing/list', {
            params: {
                page: pagination.value.current_page,
                per_page: perPage.value,
                search: search.value,
                ...filters.value, // Spread filters into params
            }
        });
        
        clients.value = response.data.clients.data;
        stats.value = response.data.stats;
        
        // Update pagination
        pagination.value = {
            current_page: response.data.clients.current_page,
            last_page: response.data.clients.last_page,
            from: response.data.clients.from,
            to: response.data.clients.to,
            total: response.data.clients.total
        };

    } catch (error) {
        console.error("Error fetching billing data:", error);
    } finally {
        loading.value = false;
    }
};

const changePage = (page) => {
    if (page >= 1 && page <= pagination.value.last_page) {
        pagination.value.current_page = page;
        fetchData();
    }
};

const debounce = (fn, delay) => {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn(...args), delay);
    };
};

const debouncedSearch = debounce(fetchData, 300);

const toggleSelectAll = () => {
    if (selectAll.value) {
        selectedItems.value = clients.value.map(c => c.id);
    } else {
        selectedItems.value = [];
    }
};

// Formatters
const formatCurrency = (val) => {
    return val ? Number(val).toFixed(0) : '0';
};

// Formatters with Native JS
const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return '-';
    return date.toISOString().split('T')[0];
};

const formatDateTime = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return '-';
    return date.toISOString().slice(0, 16).replace('T', ' ');
};

const daysLeft = (dateString) => {
    if (!dateString) return 0;
    const targetDate = new Date(dateString);
    const today = new Date();
    
    // Reset hours to compare just dates
    targetDate.setHours(0,0,0,0);
    today.setHours(0,0,0,0);
    
    const diffTime = targetDate - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays > 0 ? diffDays : 0;
};

// Actions
import Swal from 'sweetalert2';

const handleAction = async (action) => {
    // Actions requiring selection
    const selectionRequired = ['disable_selected', 'enable_selected', 'sms_selected', 'email_selected', 'bulk_status', 'bulk_zone', 'bulk_extend'];
    
    if (selectionRequired.includes(action) && selectedItems.value.length === 0) {
        Swal.fire({
            title: 'Selection Required',
            text: 'Please select at least one client to proceed.',
            icon: 'warning',
            confirmButtonColor: '#4f46e5'
        });
        return;
    }

    switch (action) {
        case 'generate_excel':
            window.location.href = '/api/billing/export/excel'; // Assuming endpoint
            break;

        case 'generate_pdf':
             window.open('/api/billing/export/pdf', '_blank'); // Assuming endpoint
            break;

        case 'sync_clients':
            loading.value = true;
            try {
                await axios.post('/api/mikrotik/sync');
                Swal.fire('Synced!', 'Client data synced with Mikrotik.', 'success');
                fetchData();
            } catch (error) {
                Swal.fire('Error', 'Failed to sync clients.', 'error');
            } finally {
                loading.value = false;
            }
            break;

        case 'disable_selected':
        case 'enable_selected':
            const status = action === 'disable_selected' ? 'disabled' : 'active';
            const actionText = action === 'disable_selected' ? 'Disable' : 'Enable';
            
            Swal.fire({
                title: `Are you sure?`,
                text: `You are about to ${actionText.toLowerCase()} ${selectedItems.value.length} clients.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: status === 'disabled' ? '#ef4444' : '#10b981',
                confirmButtonText: `Yes, ${actionText}!`,
                showLoaderOnConfirm: true,
                preConfirm: async () => {
                    try {
                         await axios.post('/api/billing/bulk-status', { 
                            ids: selectedItems.value, 
                            status: status 
                        });
                        return true;
                    } catch (error) {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Success!', `Clients have been ${status}.`, 'success');
                    fetchData();
                    selectedItems.value = [];
                }
            });
            break;

        case 'bulk_status':
            const { value: selectedStatus } = await Swal.fire({
                title: 'Select Status',
                input: 'select',
                inputOptions: {
                    'active': 'Active',
                    'disabled': 'Disabled',
                    'expired': 'Expired'
                },
                inputPlaceholder: 'Select a status',
                showCancelButton: true
            });

            if (selectedStatus) {
                await axios.post('/api/billing/bulk-status', { ids: selectedItems.value, status: selectedStatus });
                Swal.fire('Success', 'Status updated successfully', 'success');
                fetchData();
            }
            break;

        case 'bulk_zone':
             // Assuming zones are available in dropdowns
             const zoneOptions = dropdowns.value.zones.reduce((acc, zone) => {
                acc[zone.id] = zone.name;
                return acc;
            }, {});

            const { value: selectedZone } = await Swal.fire({
                title: 'Move to Zone',
                input: 'select',
                inputOptions: zoneOptions,
                inputPlaceholder: 'Select a zone',
                showCancelButton: true
            });

            if (selectedZone) {
                await axios.post('/api/billing/bulk-zone', { ids: selectedItems.value, zone_id: selectedZone });
                Swal.fire('Success', 'Zone updated successfully', 'success');
                fetchData();
            }
            break;

        case 'download_invoice':
             // Bulk invoice download
             // Can be a POST to generate a ZIP or single PDF
             window.location.href = `/api/billing/invoices/bulk?ids=${selectedItems.value.join(',')}`;
            break;
            
        case 'sms_selected':
            const { value: smsText } = await Swal.fire({
                input: 'textarea',
                inputLabel: 'Message',
                inputPlaceholder: 'Type your SMS message here...',
                inputAttributes: { 'aria-label': 'Type your SMS message' },
                showCancelButton: true
            });
            if (smsText) {
                await axios.post('/api/communication/sms/bulk', { ids: selectedItems.value, message: smsText });
                Swal.fire('Sent!', 'SMS queued for delivery.', 'success');
            }
            break;

        case 'email_selected':
            const { value: emailSubject } = await Swal.fire({
                input: 'text',
                inputLabel: 'Subject',
                inputPlaceholder: 'Email Subject',
                showCancelButton: true
            });
             if (emailSubject) { // If subject entered, prompt for body
                 const { value: emailBody } = await Swal.fire({
                    input: 'textarea',
                    inputLabel: 'Body',
                    inputPlaceholder: 'Email content...',
                    showCancelButton: true
                });
                if (emailBody) {
                    await axios.post('/api/communication/email/bulk', { ids: selectedItems.value, subject: emailSubject, body: emailBody });
                     Swal.fire('Sent!', 'Emails queued for delivery.', 'success');
                }
            }
            break;

        case 'info_mikrotik':
            // Likely just shows info for the first selected item or a list
            if(selectedItems.value.length === 1) {
                 const client = clients.value.find(c => c.id === selectedItems.value[0]);
                 Swal.fire({
                    title: 'Mikrotik Info',
                    html: `<div class="text-left text-sm">
                            <p><strong>IP:</strong> ${client.ip_address || 'N/A'}</p>
                            <p><strong>Mac:</strong> ${client.mac_address || 'N/A'}</p>
                            <p><strong>Uptime:</strong> ${client.uptime || 'N/A'}</p>
                           </div>`
                 });
            } else {
                 Swal.fire('Info', 'Please select exactly one client to view Mikrotik Info.', 'info');
            }
            break;

        case 'bulk_extend':
            const { value: days } = await Swal.fire({
                title: 'Extend Expiry',
                input: 'number',
                inputLabel: 'Days to extend',
                inputValue: 30,
                showCancelButton: true
            });
            if (days) {
                 await axios.post('/api/billing/bulk-extend', { ids: selectedItems.value, days: days });
                 Swal.fire('Success', `Extended by ${days} days.`, 'success');
                 fetchData();
            }
            break;

        default:
            console.log('Unknown Action', action);
    }
};


const fetchDropdowns = async () => {
    try {
        // Fetch Zones
        const zoneRes = await axios.get('/api/config/zones');
        dropdowns.value.zones = zoneRes.data.data;
        
        // Fetch Packages (Assuming route exists, otherwise handle gracefully)
        try {
           const pkgRes = await axios.get('/api/config/packages');
           dropdowns.value.packages = pkgRes.data.data;
        } catch (e) { console.warn('Packages endpoint not ready'); }

    } catch (error) {
        console.error("Error fetching dropdowns:", error);
    }
};

const applyFilters = () => {
    pagination.value.current_page = 1;
    fetchData();
    showFilterModal.value = false;
};

const resetFilters = () => {
    filters.value = {
        zone_id: '',
        package_id: '',
        status: '',
        payment_status: ''
    };
    applyFilters();
};



const toggleVisibilityMenu = () => {
    showVisibilityMenu.value = !showVisibilityMenu.value;
};

const handleClickOutside = (event) => {
    if (visibilityContainer.value && !visibilityContainer.value.contains(event.target)) {
        showVisibilityMenu.value = false;
    }
};

onMounted(() => {
    fetchData();
    fetchDropdowns();
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
.btn-action {
    @apply text-white text-[10px] uppercase font-bold py-2.5 px-3 rounded-xl shadow-sm border border-transparent transition-all flex items-center justify-center whitespace-nowrap active:scale-95 text-xs tracking-wide;
}

.stat-card {
    @apply rounded-2xl shadow-sm p-5 relative overflow-hidden transition-all hover:-translate-y-1 hover:shadow-md border border-white/10;
}

</style>
