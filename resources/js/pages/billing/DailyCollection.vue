<template>
    <div class="p-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.414.586l5.414 5.414a1 1 0 01.586 1.414V19a2 2 0 01-2 2z"></path></svg>
                    Daily Bill Collection
                    <span class="text-xs font-normal text-slate-400 ml-2">All Daily Collected Bills</span>
                </h1>
            </div>
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <span>Billing</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-indigo-600 font-medium">Daily Bill Collection</span>
            </div>
        </div>

        <!-- Custom Tabs -->
        <div class="flex items-center gap-4 mb-6">
            <button 
                @click="currentTab = 'manual'"
                :class="[
                    'px-6 py-2 rounded-full text-sm font-medium transition-all flex items-center gap-2',
                    currentTab === 'manual' ? 'bg-slate-800 text-white shadow-lg shadow-slate-900/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
                ]"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                Collected Bills
            </button>
            <button 
                @click="currentTab = 'webhook'"
                :class="[
                    'px-6 py-2 rounded-full text-sm font-medium transition-all flex items-center gap-2',
                    currentTab === 'webhook' ? 'bg-slate-800 text-white shadow-lg shadow-slate-900/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
                ]"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Webhook Payments
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Receive Card -->
            <div class="relative overflow-hidden rounded-xl bg-cyan-500 text-white p-6 shadow-lg shadow-cyan-500/20 group">
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold uppercase tracking-wider opacity-90 mb-1">{{ currentTab === 'manual' ? 'Recieve' : 'Total Received' }}</div>
                        <div class="text-3xl font-black group-hover:scale-105 transition-transform">
                            {{ currentTab === 'manual' ? (stats.total_received || 0) : (webhookStats.total_received || 0) }}
                        </div>
                    </div>
                    <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 text-xs font-medium bg-black/10 inline-block px-2 py-1 rounded">
                    {{ currentTab === 'manual' ? 'Monthly paid client' : 'Automatically settled payments' }}
                </div>
            </div>

            <!-- Second Card (Discount or Pending) -->
            <div class="relative overflow-hidden rounded-xl bg-teal-500 text-white p-6 shadow-lg shadow-teal-500/20 group">
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold uppercase tracking-wider opacity-90 mb-1">{{ currentTab === 'manual' ? 'Discount' : 'Total Pending' }}</div>
                        <div class="text-3xl font-black group-hover:scale-105 transition-transform">
                            {{ currentTab === 'manual' ? (stats.total_discount || 0) : (webhookStats.total_pending || 0) }}
                        </div>
                    </div>
                    <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 text-xs font-medium bg-black/10 inline-block px-2 py-1 rounded">
                    {{ currentTab === 'manual' ? 'Monthly unpaid client' : 'Awaiting confirmation' }}
                </div>
            </div>

            <!-- Third Card (Due or Count) -->
            <div class="relative overflow-hidden rounded-xl bg-indigo-500 text-white p-6 shadow-lg shadow-indigo-500/20 group">
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold uppercase tracking-wider opacity-90 mb-1">{{ currentTab === 'manual' ? 'Due' : 'Transaction Count' }}</div>
                        <div class="text-3xl font-black group-hover:scale-105 transition-transform">
                            {{ currentTab === 'manual' ? (stats.total_due || 0) : (webhookStats.total_count || 0) }}
                        </div>
                    </div>
                    <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 text-xs font-medium bg-black/10 inline-block px-2 py-1 rounded">
                    {{ currentTab === 'manual' ? 'Monthly recieved bill' : 'Successful webhook events' }}
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-2">
                <button @click="exportData('csv')" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-medium hover:bg-slate-900 shadow-sm">
                    Generate CSV
                </button>
                <button @click="exportData('pdf')" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-medium hover:bg-slate-900 shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Generate PDF
                </button>
            </div>
            <div v-if="currentTab === 'manual'" class="flex items-center gap-2">
                <button @click="showReceiveModal = true" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-medium hover:bg-slate-900 shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Receive Bill
                </button>
                <button @click="approveSelected" class="px-4 py-2 bg-indigo-900 text-white rounded-lg text-sm font-medium hover:bg-indigo-800 shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Approve Selected Transaction
                </button>
                <button @click="deleteSelected" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Delete Selected Transaction
                </button>
            </div>
        </div>

        <!-- Filters Grid -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Username</label>
                    <select v-model="filters.username" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 focus:outline-none focus:border-indigo-500">
                        <option value="">All User</option>
                        <option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">From Date</label>
                    <input v-model="filters.from_date" type="date" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">To Date</label>
                    <input v-model="filters.to_date" type="date" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Received By</label>
                    <select v-model="filters.received_by" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 focus:outline-none focus:border-indigo-500">
                        <option value="">Select</option>
                        <option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }}</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div v-if="currentTab === 'manual'">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Approved By</label>
                    <select v-model="filters.approved_by" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 focus:outline-none focus:border-indigo-500">
                        <option value="Select">Select</option>
                        <option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }}</option>
                    </select>
                </div>
                <div v-if="currentTab === 'manual'">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Created By</label>
                    <select v-model="filters.created_by" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 focus:outline-none focus:border-indigo-500">
                        <option value="Select">Select</option>
                        <option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Trans. Status</label>
                    <select v-model="filters.status" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 focus:outline-none focus:border-indigo-500">
                        <option value="Select">Select</option>
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Payment Method</label>
                    <select v-model="filters.payment_method" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 focus:outline-none focus:border-indigo-500">
                        <option value="">Select</option>
                        <option value="cash">Cash</option>
                        <option value="bkash">Bkash</option>
                        <option value="nagad">Nagad</option>
                        <option value="rocket">Rocket</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Pagination & Search -->
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2 text-sm text-slate-600">
                <span>Show</span>
                <select v-model="filters.per_page" class="px-2 py-1 border border-slate-200 rounded-lg focus:outline-none">
                    <option :value="25">25</option>
                    <option :value="50">50</option>
                    <option :value="100">100</option>
                </select>
                <span>entries</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-600 uppercase font-bold">Search:</span>
                <input v-model="filters.search" type="text" placeholder="Search..." class="px-3 py-1.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 min-w-[200px]">
            </div>
        </div>

        <!-- Data Table (Manual Collections) -->
        <div v-if="currentTab === 'manual'" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white uppercase font-bold tracking-wider">
                        <tr>
                            <th class="p-4 text-[10px]">Date <span class="ml-1 text-emerald-400">↓</span></th>
                            <th class="p-4 border-l border-slate-600 text-[10px]">C.Code</th>
                            <th class="p-4 border-l border-slate-600 text-[10px]">UserName</th>
                            <th class="p-4 border-l border-slate-600 text-[10px]">Cus.Name</th>
                            <th class="p-4 border-l border-slate-600 text-[10px]">MobileNo.</th>
                            <th class="p-4 border-l border-slate-600 text-[10px]">Note/Remarks</th>
                            <th class="p-4 border-l border-slate-600 text-[10px]">M.Bill</th>
                            <th class="p-4 border-l border-slate-600 text-[10px]">Received</th>
                            <th class="p-4 border-l border-slate-600 text-[10px]">VAT</th>
                            <th class="p-4 border-l border-slate-600 text-[10px]">Discount</th>
                            <th class="p-4 border-l border-slate-600 text-[10px]">BalanceDue</th>
                            <th class="p-4 border-l border-slate-600 text-[10px]">PaymentMethod</th>
                            <th class="p-4 border-l border-slate-600 text-[10px]">ReceivedBy <span class="ml-1">↑↓</span></th>
                            <th class="p-4 border-l border-slate-600 text-[10px]">ApprovedBy <span class="ml-1">↑↓</span></th>
                            <th class="p-4 border-l border-slate-600 text-[10px]">CreatedBy <span class="ml-1">↑↓</span></th>
                            <th class="p-4 border-l border-slate-600 flex items-center justify-between text-[10px]">
                                Action
                                <input type="checkbox" @change="toggleAll" :checked="selectedItems.length > 0 && selectedItems.length === collections.data.length" class="rounded border-gray-400 text-emerald-500 focus:ring-emerald-500">
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr v-if="loading" class="animate-pulse">
                            <td colspan="16" class="p-8 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-10 h-10 border-4 border-cyan-500 border-t-transparent rounded-full animate-spin"></div>
                                    <span class="text-sm font-medium text-slate-500">Loading transactions...</span>
                                </div>
                            </td>
                        </tr>
                        <tr v-else-if="collections.data.length === 0">
                             <td colspan="16" class="p-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    <span class="text-sm font-medium text-slate-400">No transactions found</span>
                                </div>
                             </td>
                        </tr>
                        <tr v-for="item in collections.data" :key="item.id" class="hover:bg-slate-50/80 transition-all duration-150 group">
                            <td class="p-4 font-medium text-slate-600">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800">{{ formatDate(item.date).split(' ')[0] }}</span>
                                    <span class="text-[10px] text-slate-400">{{ formatDate(item.date).split(' ')[1] }}</span>
                                </div>
                            </td>
                            <td class="p-4 border-l border-slate-100">
                                <span class="px-2 py-1 bg-cyan-50 text-cyan-700 rounded font-bold text-[10px]">{{ item.client?.client_id_display || '-' }}</span>
                            </td>
                            <td class="p-4 border-l border-slate-100 font-medium text-sky-600">{{ item.client?.username || '-' }}</td>
                            <td class="p-4 border-l border-slate-100 font-bold text-slate-800">{{ item.client?.name || '-' }}</td>
                             <td class="p-4 border-l border-slate-100 text-slate-600">{{ item.client?.mobile || '-' }}</td>
                            <td class="p-4 border-l border-slate-100 text-slate-500 max-w-[150px] truncate" :title="item.note">{{ item.note || '-' }}</td>
                             <td class="p-4 border-l border-slate-100 font-bold text-slate-700">৳{{ item.client?.monthly_fee || 0 }}</td>
                             <td class="p-4 border-l border-slate-100">
                                <span class="px-2 py-1 bg-emerald-50 text-emerald-700 rounded-md font-bold">৳{{ item.amount }}</span>
                             </td>
                             <td class="p-4 border-l border-slate-100 text-slate-400">{{ (item.amount * 0).toFixed(2) }}</td>
                             <td class="p-4 border-l border-slate-100">
                                <span class="px-2 py-1 bg-amber-50 text-amber-700 rounded-md font-bold">৳{{ item.discount }}</span>
                             </td>
                             <td class="p-4 border-l border-slate-100">
                                <span class="px-2 py-1 bg-red-50 text-red-700 rounded-md font-bold">৳{{ ((item.client?.monthly_fee_due || 0) - (item.amount + item.discount)).toFixed(2) }}</span>
                             </td>
                            <td class="p-4 border-l border-slate-100">
                                <span class="px-2.5 py-1 bg-indigo-100 text-indigo-700 rounded-full uppercase font-bold text-[9px] tracking-wider">{{ item.method }}</span>
                            </td>
                             <td class="p-4 border-l border-slate-100 text-slate-600">{{ item.collector?.name || '-' }}</td>
                            <td class="p-4 border-l border-slate-100">
                                <span v-if="item.status === 'approved'" class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-[10px] font-bold">admin</span>
                                <span v-else class="text-slate-400">-</span>
                            </td>
                            <td class="p-4 border-l border-slate-100 text-slate-600">{{ item.collector?.name || 'admin' }}</td>
                             <td class="p-4 border-l border-slate-100 text-center">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <input type="checkbox" :checked="selectedItems.includes(item.id)" @change="toggleSelection(item.id)" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-gradient-to-r from-slate-100 to-slate-50 border-t-2 border-slate-300">
                        <tr class="font-bold text-slate-800">
                            <td colspan="6" class="p-4 text-right uppercase text-xs tracking-widest text-slate-600">Total</td>
                            <td class="p-4 border-l border-slate-200 text-slate-700">৳{{ collections.data.reduce((sum, item) => sum + parseFloat(item.client?.monthly_fee || 0), 0).toFixed(2) }}</td>
                            <td class="p-4 border-l border-slate-200">
                                <span class="px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-md font-black">৳{{ collections.data.reduce((sum, item) => sum + parseFloat(item.amount || 0), 0).toFixed(2) }}</span>
                            </td>
                            <td class="p-4 border-l border-slate-200 text-slate-400">0.00</td>
                            <td class="p-4 border-l border-slate-200">
                                <span class="px-3 py-1.5 bg-amber-100 text-amber-700 rounded-md font-black">৳{{ collections.data.reduce((sum, item) => sum + parseFloat(item.discount || 0), 0).toFixed(2) }}</span>
                            </td>
                            <td class="p-4 border-l border-slate-200">
                                <span class="px-3 py-1.5 bg-red-100 text-red-700 rounded-md font-black">৳{{ collections.data.reduce((sum, item) => sum + (parseFloat(item.client?.monthly_fee_due || 0) - (parseFloat(item.amount) + parseFloat(item.discount))), 0).toFixed(2) }}</span>
                            </td>
                            <td colspan="5" class="border-l border-slate-200"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Footer Info -->
            <div class="p-4 flex flex-col md:flex-row items-center justify-between border-t border-slate-200 bg-white">
                <div class="text-xs text-slate-500 mb-4 md:mb-0">
                    Showing {{ collections.from || 0 }} to {{ collections.to || 0 }} of {{ collections.total || 0 }} entries
                </div>
                <div class="flex gap-1">
                    <button @click="prevPage" :disabled="currentPage === 1" class="px-3 py-1 text-xs font-medium text-slate-400 bg-white border border-slate-200 rounded hover:bg-slate-50 disabled:opacity-50">Previous</button>
                    <button v-for="p in totalPages" :key="p" @click="currentPage = p" :class="currentPage === p ? 'bg-blue-500 text-white border-blue-500' : 'text-slate-600 bg-white border-slate-200'" class="px-3 py-1 text-xs font-medium border rounded hover:bg-slate-50 transition-colors">{{ p }}</button>
                    <button @click="nextPage" :disabled="currentPage === totalPages" class="px-3 py-1 text-xs font-medium text-slate-400 bg-white border border-slate-200 rounded hover:bg-slate-50 disabled:opacity-50">Next</button>
                </div>
            </div>
        </div>

        <!-- Data Table (Webhook Payments) -->
        <div v-else class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-[11px] text-left">
                    <thead class="bg-slate-800 text-white uppercase font-bold tracking-wider">
                        <tr>
                            <th class="p-3">Trxn.Date <span class="ml-1 text-emerald-400">↑↓</span></th>
                            <th class="p-3 border-l border-slate-600">Trxn.ID</th>
                            <th class="p-3 border-l border-slate-600">FromWallet</th>
                            <th class="p-3 border-l border-slate-600">Amount</th>
                            <th class="p-3 border-l border-slate-600">Trxn.Status</th>
                            <th class="p-3 border-l border-slate-600">Trxn.Ref</th>
                            <th class="p-3 border-l border-slate-600">TimeStamp</th>
                            <th class="p-3 border-l border-slate-600">TypeFor</th>
                            <th class="p-3 border-l border-slate-600">Status</th>
                            <th class="p-3 border-l border-slate-600">UpdateBy</th>
                            <th class="p-3 border-l border-slate-600">UpdatedOn</th>
                            <th class="p-3 border-l border-slate-600">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr v-if="loading">
                            <td colspan="12" class="p-8 text-center">Loading...</td>
                        </tr>
                        <tr v-else-if="webhookPayments.data.length === 0">
                             <td colspan="12" class="p-12 text-center text-slate-400">No transactions found</td>
                        </tr>
                        <tr v-for="item in webhookPayments.data" :key="item.id" class="hover:bg-slate-50 transition-colors group text-[10px]">
                            <td class="p-3 font-medium">{{ formatDateShort(item.created_at) }}</td>
                            <td class="p-3 border-l border-slate-100 font-bold text-slate-700">{{ item.transaction_id }}</td>
                            <td class="p-3 border-l border-slate-100">{{ item.payment_from }}</td>
                            <td class="p-3 border-l border-slate-100 font-bold">{{ item.amount }}</td>
                            <td class="p-3 border-l border-slate-100">
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full font-bold">Completed</span>
                            </td>
                            <td class="p-3 border-l border-slate-100 font-bold text-indigo-600">{{ item.client?.client_id_display || '-' }}</td>
                            <td class="p-3 border-l border-slate-100 text-slate-400">{{ formatTimestamp(item.created_at) }}</td>
                            <td class="p-3 border-l border-slate-100 uppercase font-bold text-slate-500">{{ item.payment_method }}_WEBHOOK</td>
                            <td class="p-3 border-l border-slate-100">
                                <span class="px-2 py-1 bg-emerald-500 text-white rounded font-bold text-[9px] uppercase">Automatically Settled</span>
                            </td>
                            <td class="p-3 border-l border-slate-100 text-slate-400">-</td>
                            <td class="p-3 border-l border-slate-100">{{ formatDateShort(item.updated_at) }}</td>
                            <td class="p-3 border-l border-slate-100">
                                <div class="flex items-center gap-2">
                                     <button class="text-emerald-500" title="Resettle">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                     </button>
                                     <button class="text-indigo-500" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                     </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-slate-50 font-bold">
                        <tr>
                            <td colspan="3" class="p-3 text-right uppercase">Total</td>
                            <td class="p-3 border-l border-slate-200">৳{{ webhookStats.total_received || 0 }}</td>
                            <td colspan="8"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Footer Info -->
            <div class="p-4 flex flex-col md:flex-row items-center justify-between border-t border-slate-200 bg-white">
                <div class="text-xs text-slate-500">
                    Showing {{ webhookPayments.from || 0 }} to {{ webhookPayments.to || 0 }} of {{ webhookPayments.total || 0 }} entries
                </div>
                <div class="flex gap-1">
                    <button @click="prevPage" :disabled="currentPage === 1" class="px-3 py-1 text-xs font-medium text-slate-400 bg-white border border-slate-200 rounded hover:bg-slate-50 disabled:opacity-50">Previous</button>
                    <button v-for="p in webhookTotalPages" :key="p" @click="currentPage = p" :class="currentPage === p ? 'bg-blue-500 text-white border-blue-500' : 'text-slate-600 bg-white border-slate-200'" class="px-3 py-1 text-xs font-medium border rounded hover:bg-slate-50 transition-colors">{{ p }}</button>
                    <button @click="nextPage" :disabled="currentPage === webhookTotalPages" class="px-3 py-1 text-xs font-medium text-slate-400 bg-white border border-slate-200 rounded hover:bg-slate-50 disabled:opacity-50">Next</button>
                </div>
            </div>
        </div>

        <!-- Receive Bill Modal -->
        <div v-if="showReceiveModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="bg-slate-800 text-white p-4 flex items-center justify-between rounded-t-xl">
                    <h3 class="text-lg font-bold">Bill Receive</h3>
                    <button @click="showReceiveModal = false" class="text-slate-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="p-6">
                    <!-- Client Search -->
                    <div class="mb-6 relative">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">USER NAME (ID)</label>
                        <input 
                            type="text" 
                            v-model="clientSearch" 
                            @input="searchClients"
                            placeholder="Type Name, ID or Mobile to search..." 
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                        >
                        <!-- Search Results Dropdown -->
                        <div v-if="searchResults.length > 0" class="absolute z-10 w-full bg-white border border-slate-200 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto">
                            <div 
                                v-for="client in searchResults" 
                                :key="client.id" 
                                @click="selectClient(client)"
                                class="px-4 py-2 hover:bg-slate-50 cursor-pointer border-b border-slate-50 last:border-0"
                            >
                                <div class="font-medium text-slate-800">{{ client.name }} ({{ client.client_id_display }})</div>
                                <div class="text-xs text-slate-500">{{ client.username }} | {{ client.mobile }}</div>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="submitPayment">
                        <!-- Client Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <!-- User Name -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">User Name</label>
                                <div class="px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-700 font-medium h-10 flex items-center">
                                    {{ selectedClient?.username || '-' }}
                                </div>
                            </div>
                            <!-- Mobile Number -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Mobile Number</label>
                                <div class="px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-700 font-medium h-10 flex items-center">
                                    {{ selectedClient?.mobile || '-' }}
                                </div>
                            </div>
                             <!-- Billing Last Date -->
                             <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Billing Last Date</label>
                                <div class="px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-700 font-medium h-10 flex items-center">
                                    {{ selectedClient?.expire_date ? new Date(selectedClient.expire_date).getDate() : '-' }}
                                </div>
                            </div>
                        </div>

                        <!-- Billing Status -->
                        <div class="mb-6">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Billing Status</label>
                             <div class="px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-700 font-medium h-10 flex items-center uppercase">
                                {{ selectedClient?.status || '-' }}
                            </div>
                        </div>

                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Left Column -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Customer Zone</label>
                                    <div class="px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-700 h-10 flex items-center">
                                        {{ selectedClient?.zone?.name || '-' }}
                                    </div>
                                </div>
                                 <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Customer Package</label>
                                    <div class="px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-700 h-10 flex items-center">
                                        {{ selectedClient?.package?.name || '-' }}
                                    </div>
                                </div>
                                 <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Received From</label>
                                    <input v-model="newPayment.received_from" type="text" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-indigo-500">
                                </div>
                                 <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Received By</label>
                                    <select v-model="newPayment.collected_by" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-indigo-500">
                                         <option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }}</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Right Column -->
                             <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Address</label>
                                    <div class="px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-700 h-10 flex items-center truncate">
                                        {{ selectedClient?.address || '-' }}
                                    </div>
                                </div>
                                 <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Monthly Bill</label>
                                    <div class="px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-700 h-10 flex items-center">
                                        {{ selectedClient?.monthly_fee || '0.00' }}
                                    </div>
                                </div>
                                  <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Due Amount</label>
                                    <div class="px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-700 h-10 flex items-center">
                                        {{ selectedClient?.monthly_fee_due || '0.00' }}
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Payment Method</label>
                                    <select v-model="newPayment.method" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-indigo-500">
                                        <option value="cash">Cash</option>
                                        <option value="bkash">Bkash</option>
                                        <option value="nagad">Nagad</option>
                                        <option value="rocket">Rocket</option>
                                        <option value="bank">Bank Transfer</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Table -->
                        <div class="border border-slate-200 rounded-lg overflow-hidden mb-6">
                            <div class="bg-slate-800 text-white px-4 py-2 grid grid-cols-2 text-sm font-bold">
                                <div>Details</div>
                                <div class="text-right">Amount Info</div>
                            </div>
                            <div class="divide-y divide-slate-100 text-sm">
                                <div class="px-4 py-3 grid grid-cols-2 bg-slate-50">
                                    <div class="text-slate-600 font-medium">Payable Amount</div>
                                    <div class="text-right font-bold text-slate-800">{{ payableAmount }}</div>
                                </div>
                                <div class="px-4 py-2 grid grid-cols-2 items-center">
                                    <div class="text-slate-600">Discount</div>
                                    <div class="text-right">
                                        <input v-model="newPayment.discount" type="number" step="0.01" class="w-32 text-right px-2 py-1 border border-slate-200 rounded focus:outline-none focus:border-indigo-500">
                                    </div>
                                </div>
                                 <div class="px-4 py-2 grid grid-cols-2 items-center">
                                    <div class="text-slate-600">Received Amount</div>
                                    <div class="text-right">
                                        <input v-model="newPayment.amount" type="number" step="0.01" class="w-32 text-right px-2 py-1 border border-indigo-500 rounded focus:outline-none ring-1 ring-indigo-500 font-bold">
                                    </div>
                                </div>
                                 <div class="px-4 py-3 grid grid-cols-2 items-center">
                                    <div class="text-slate-600">Receipt/Transaction No.</div>
                                    <div class="text-right">
                                         <input v-model="newPayment.transaction_id" type="text" placeholder="Auto" class="w-full text-right px-2 py-1 border border-slate-200 rounded focus:outline-none focus:border-indigo-500 bg-slate-50" readonly>
                                    </div>
                                </div>
                                 <div class="px-4 py-3 grid grid-cols-2 bg-slate-50">
                                    <div class="text-slate-600 font-medium">Balance Due</div>
                                    <div class="text-right font-bold" :class="balanceDue > 0 ? 'text-red-500' : 'text-emerald-600'">
                                        {{ balanceDue }}
                                    </div>
                                </div>
                                 <div class="px-4 py-3 grid grid-cols-2 items-center">
                                    <div class="text-slate-600">Remarks/Note</div>
                                    <div class="text-right">
                                        <textarea v-model="newPayment.note" rows="1" class="w-full px-2 py-1 border border-slate-200 rounded focus:outline-none focus:border-indigo-500"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input v-model="newPayment.is_next_billing" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-sm text-slate-600">Is it next billing date?</span>
                                </label>
                                 <label class="flex items-center gap-2 cursor-pointer">
                                    <input v-model="newPayment.send_sms" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-sm text-slate-600">Send SMS</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-between gap-4">
                            <button type="button" @click="showReceiveModal = false" class="px-8 py-2 border border-red-500 text-red-500 rounded-full text-sm font-bold hover:bg-red-50 transition-colors w-full md:w-auto">
                                Cancel
                            </button>
                            <button type="submit" :disabled="submitting || !newPayment.amount" class="px-8 py-2 bg-white border border-slate-800 text-slate-800 rounded-full text-sm font-bold hover:bg-slate-50 transition-colors flex items-center justify-center gap-2 w-full md:w-auto shadow-lg shadow-slate-200">
                                <svg v-if="submitting" class="animate-spin h-4 w-4 text-slate-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <svg v-else class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const collections = ref({ data: [], meta: {} });
const stats = ref({ total_received: 0, total_discount: 0, total_due: 0 });
const webhookPayments = ref({ data: [], meta: {} });
const webhookStats = ref({ total_received: 0, total_pending: 0, total_count: 0 });
const users = ref([]);
const loading = ref(false);
const currentPage = ref(1);
const totalPages = ref(1);
const webhookTotalPages = ref(1);
const selectedItems = ref([]);
const clientSearch = ref('');
const searchResults = ref([]);
const selectedClient = ref(null);
const currentTab = ref('manual'); // 'manual' or 'webhook'

// Modal State
const showReceiveModal = ref(false);
const newPayment = ref({
    client_id: '',
    amount: 0,
    discount: 0,
    method: 'cash',
    note: '',
    received_from: '',
    collected_by: '',
    is_next_billing: false,
    send_sms: true,
    transaction_id: ''
});
const submitting = ref(false);

const filters = ref({
    username: 'All User',
    from_date: new Date().toISOString().split('T')[0],
    to_date: new Date().toISOString().split('T')[0],
    received_by: '',
    approved_by: 'Select',
    created_by: 'Select',
    status: 'Select',
    payment_method: 'Select',
    per_page: 25,
    search: ''
});

// Computed Properties
const payableAmount = computed(() => {
    if (!selectedClient.value) return 0;
    return parseFloat(selectedClient.value.monthly_fee_due || 0).toFixed(2);
});

const balanceDue = computed(() => {
    const payable = parseFloat(payableAmount.value) || 0;
    const discount = parseFloat(newPayment.value.discount) || 0;
    const received = parseFloat(newPayment.value.amount) || 0;
    return (payable - discount - received).toFixed(2);
});

// Methods
let searchTimeout;
const searchClients = () => {
    if (clientSearch.value.length < 2) {
        searchResults.value = [];
        return;
    }
    
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(async () => {
        try {
            const response = await axios.get(`/api/billing/daily-collections/search-client?query=${clientSearch.value}`);
            searchResults.value = response.data;
        } catch (error) {
            console.error("Search failed", error);
        }
    }, 300);
};

const selectClient = (client) => {
    selectedClient.value = client;
    newPayment.value.client_id = client.id;
    newPayment.value.received_from = client.name;
    // Auto-fill amount with due amount
    newPayment.value.amount = parseFloat(client.monthly_fee_due || 0); 
    clientSearch.value = '';
    searchResults.value = [];
};

const fetchData = async () => {
    loading.value = true;
    try {
        if (currentTab.value === 'manual') {
            const response = await axios.get('/api/billing/daily-collections', {
                params: {
                    page: currentPage.value,
                    ...filters.value
                }
            });
            collections.value = response.data.collections;
            stats.value = response.data.stats;
            users.value = response.data.users || [];
            totalPages.value = response.data.collections.last_page;
        } else {
            const response = await axios.get('/api/billing/webhook-payments', {
                params: {
                    page: currentPage.value,
                    ...filters.value
                }
            });
            webhookPayments.value = response.data.webhookPayments;
            webhookStats.value = response.data.stats;
            webhookTotalPages.value = response.data.webhookPayments.last_page;
        }
    } catch (error) {
        console.error("Error fetching data:", error);
    } finally {
        loading.value = false;
    }
};

const clearFilters = () => {
    filters.value = {
        username: 'All User',
        from_date: new Date().toISOString().split('T')[0],
        to_date: new Date().toISOString().split('T')[0],
        received_by: '',
        approved_by: 'Select',
        created_by: 'Select',
        status: 'Select',
        payment_method: 'Select',
        per_page: 25,
        search: ''
    };
    fetchData();
};

const submitPayment = async () => {
    if (!newPayment.value.client_id || !newPayment.value.amount) return Swal.fire('Error', 'Client ID and Amount are required', 'error');
    
    submitting.value = true;
    try {
        await axios.post('/api/billing/daily-collections', newPayment.value);
        Swal.fire('Success', 'Payment Received Successfully', 'success');
        showReceiveModal.value = false;
        // Reset
        newPayment.value = { 
            client_id: '', amount: 0, discount: 0, method: 'cash', 
            note: '', received_from: '', collected_by: '', 
            is_next_billing: false, send_sms: true, transaction_id: '' 
        };
        selectedClient.value = null;
        fetchData();
    } catch (error) {
         Swal.fire('Error', error.response?.data?.message || 'Failed to submit', 'error');
    } finally {
        submitting.value = false;
    }
};

const toggleSelection = (id) => {
    if (selectedItems.value.includes(id)) {
        selectedItems.value = selectedItems.value.filter(item => item !== id);
    } else {
        selectedItems.value.push(id);
    }
};

const toggleAll = (event) => {
    if (event.target.checked) {
        selectedItems.value = collections.value.data.map(item => item.id);
    } else {
        selectedItems.value = [];
    }
};

const approveSelected = async () => {
    if (!selectedItems.value.length) return Swal.fire('Warning', 'No items selected', 'warning');
    
    if (await Swal.fire({ title: 'Approve Selected?', icon: 'question', showCancelButton: true }).then(r => r.isConfirmed)) {
        try {
            await axios.post('/api/billing/daily-collections/approve', { ids: selectedItems.value });
            Swal.fire('Success', 'Transactions Approved', 'success');
            selectedItems.value = [];
            fetchData();
        } catch (error) {
            Swal.fire('Error', 'Failed to approve', 'error');
        }
    }
};

const deleteSelected = async () => {
    if (!selectedItems.value.length) return Swal.fire('Warning', 'No items selected', 'warning');

    if (await Swal.fire({ title: 'Delete Selected?', icon: 'warning', showCancelButton: true }).then(r => r.isConfirmed)) {
        try {
            await axios.post('/api/billing/daily-collections/delete', { ids: selectedItems.value });
            Swal.fire('Success', 'Transactions Deleted', 'success');
            selectedItems.value = [];
            fetchData();
        } catch (error) {
            Swal.fire('Error', 'Failed to delete', 'error');
        }
    }
};

const exportData = async (type) => {
    try {
        const endpoint = currentTab.value === 'manual' 
            ? '/api/billing/daily-collections/export' 
            : '/api/billing/webhook-payments/export';
            
        const response = await axios.get(endpoint, {
            params: { type, ...filters.value },
            responseType: 'blob'
        });
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `${currentTab.value === 'manual' ? 'daily_collection' : 'webhook_payments'}_${new Date().toISOString().split('T')[0]}.${type}`);
        document.body.appendChild(link);
        link.click();
    } catch (error) {
        Swal.fire('Error', 'Export failed', 'error');
    }
};

const formatTimestamp = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toISOString().replace('T', ' ').split('.')[0] + 'Z';
};

const formatDateShort = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleString('en-GB', {
        day: '2-digit', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit', hour12: true
    });
};

const prevPage = () => { if (currentPage.value > 1) { currentPage.value--; fetchData(); } };
const nextPage = () => { if (currentPage.value < (currentTab.value === 'manual' ? totalPages.value : webhookTotalPages.value)) { currentPage.value++; fetchData(); } };

watch(() => currentTab.value, () => {
    currentPage.value = 1;
    fetchData();
});

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleString('en-GB', {
        day: '2-digit', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit', hour12: true
    });
};

watch(() => filters.value, () => {
    currentPage.value = 1; // Reset page on filter change
    fetchData();
}, { deep: true });

onMounted(() => {
    fetchData();
});
</script>


