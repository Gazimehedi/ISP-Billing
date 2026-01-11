<template>
  <div class="p-6 bg-[#eff3f6] min-h-screen font-sans">
    <!-- Header Summary Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
      <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <!-- Left: Title & Summary Stats -->
        <div class="flex items-center gap-4">
          <div class="bg-emerald-500 p-3 rounded-xl text-white shadow-lg shadow-emerald-500/20 hidden sm:block">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <div>
            <div class="flex items-center gap-3">
              <h1 class="text-xl font-black text-slate-800 tracking-tight">PPPoE Clients</h1>
              <span class="bg-emerald-100 text-emerald-700 px-2.5 py-0.5 rounded-full text-xs font-black ring-1 ring-emerald-500/20">
                {{ meta.total }}
              </span>
            </div>
            <div class="flex items-center gap-4 mt-2">
              <div class="flex items-center gap-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Bill</span>
                <span class="text-sm font-bold text-slate-700">৳{{ summary.total_bill }}</span>
              </div>
              <div class="w-px h-3 bg-slate-200"></div>
              <div class="flex items-center gap-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Balance</span>
                <span class="text-sm font-bold text-emerald-600">৳{{ summary.total_balance }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right: Search & Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center gap-3 lg:flex-nowrap flex-wrap flex-grow lg:max-w-[65%]">
          <!-- Search Bar -->
          <div class="relative flex-grow min-w-[200px] group">
            <span class="absolute inset-y-0 left-4 flex items-center">
              <svg class="w-4 h-4 text-slate-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </span>
            <input 
              v-model="filters.search" 
              @input="handleSearch"
              type="text" 
              placeholder="Search by name, username, mobile..." 
              class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 focus:bg-white outline-none transition-all placeholder:text-slate-400 font-medium"
            >
          </div>

          <!-- Buttons Row -->
          <div class="flex items-center gap-2 shrink-0">
            <button @click="fetchClients" class="p-2.5 bg-white border border-slate-200 text-slate-500 rounded-xl hover:text-emerald-500 hover:border-emerald-200 hover:bg-emerald-50 transition-all shadow-sm" title="Refresh">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </button>
            <button @click="showFilterModal = true" class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl hover:text-sky-600 hover:border-sky-200 hover:bg-sky-50 transition-all text-xs font-black shadow-sm uppercase tracking-wider">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
              Filter
            </button>
            <button @click="exportCSV" class="p-2.5 bg-white border border-slate-200 text-slate-500 rounded-xl hover:text-amber-600 hover:border-amber-200 hover:bg-amber-50 transition-all shadow-sm" title="Export CSV">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            </button>
            <button @click="$router.push('/client/add')" class="p-2.5 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </button>
            <div class="relative">
              <button @click="showVisibilityMenu = !showVisibilityMenu" class="flex items-center gap-2 px-4 py-2.5 bg-slate-800 text-white rounded-xl hover:bg-slate-900 transition-all text-xs font-black shadow-lg shadow-slate-900/10 uppercase tracking-wider whitespace-nowrap">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Visibility
              </button>
              <!-- Visibility Dropdown -->
              <div v-if="showVisibilityMenu" class="absolute right-0 mt-2 w-48 bg-white border border-slate-100 rounded-2xl shadow-2xl z-50 p-3 ring-1 ring-slate-900/5 animate-in fade-in slide-in-from-top-2 duration-200">
                <div v-for="col in columns" :key="col.key" class="flex items-center gap-3 p-2 hover:bg-slate-50 rounded-xl cursor-pointer transition-colors group">
                  <input type="checkbox" v-model="col.visible" class="rounded-md border-slate-200 text-emerald-500 focus:ring-emerald-500 transition-all">
                  <span class="text-[11px] font-bold text-slate-600 group-hover:text-emerald-600 tracking-wide uppercase">{{ col.label }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-[#f8fafc] text-gray-700 text-[11px] uppercase font-bold border-b border-gray-100">
              <th v-if="isColVisible('select')" class="px-4 py-4 w-10 text-center"><input type="checkbox" @change="selectAll($event)" class="rounded text-sky-500 h-3.5 w-3.5 border-gray-300"></th>
              <th v-if="isColVisible('name')" class="px-4 py-4">Name</th>
              <th v-if="isColVisible('pppoe')" class="px-4 py-4">PPPoE</th>
              <th v-if="isColVisible('zone')" class="px-4 py-4">Zone</th>
              <th v-if="isColVisible('package')" class="px-4 py-4">Package</th>
              <th v-if="isColVisible('bill')" class="px-4 py-4">Bill</th>
              <th v-if="isColVisible('all_date')" class="px-4 py-4">All Date</th>
              <th v-if="isColVisible('day_left')" class="px-4 py-4">Day Left</th>
              <th v-if="isColVisible('status')" class="px-4 py-4">Status</th>
              <th v-if="isColVisible('actions')" class="px-4 py-4 text-center">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-if="loading" class="animate-pulse">
              <td :colspan="columns.filter(c => c.visible).length" class="px-6 py-20 text-center text-gray-400">
                 <div class="flex flex-col items-center gap-2">
                   <div class="w-8 h-8 border-4 border-sky-500 border-t-transparent rounded-full animate-spin"></div>
                   <span class="text-xs font-medium">Fetching client data...</span>
                 </div>
              </td>
            </tr>
            <tr v-else-if="clients.length === 0">
              <td :colspan="columns.filter(c => c.visible).length" class="px-6 py-20 text-center text-gray-500 text-xs italic">No clients found matching your criteria.</td>
            </tr>
            <tr v-for="client in clients" :key="client.id" class="hover:bg-gray-50/80 transition-colors text-xs group">
              <td v-if="isColVisible('select')" class="px-4 py-3 text-center">
                <input type="checkbox" v-model="selectedClients" :value="client.id" class="rounded text-sky-500 h-3.5 w-3.5 border-gray-300">
              </td>
              <td v-if="isColVisible('name')" class="px-4 py-3">
                <router-link :to="`/client/view/${client.id}`" class="font-bold text-emerald-600 hover:underline cursor-pointer">{{ client.client_id_display || client.id }}</router-link>
                <div class="font-medium text-gray-700 mt-0.5">{{ client.name }}</div>
              </td>
              <td v-if="isColVisible('pppoe')" class="px-4 py-3">
                <div class="text-sky-500 font-bold">{{ client.package?.name || 'No Package' }}</div>
                <div class="text-gray-400 font-medium text-[10px] mt-0.5">{{ client.username }}</div>
                <div class="text-gray-300 text-[10px] leading-none">********</div>
              </td>
              <td v-if="isColVisible('zone')" class="px-4 py-3">
                <div class="text-gray-700 font-bold uppercase">{{ client.zone?.name || 'N/A' }}</div>
                <div class="text-gray-400 text-[10px] mt-0.5">{{ client.sub_zone?.name || 'N/A' }}</div>
              </td>
              <td v-if="isColVisible('package')" class="px-4 py-3">
                <span class="text-gray-600 font-medium">{{ client.package?.name || 'N/A' }}</span>
              </td>
              <td v-if="isColVisible('bill')" class="px-4 py-3 leading-tight">
                <div><span class="text-gray-400">Bill:</span> <span class="text-gray-700 font-bold">{{ client.monthly_fee }}</span></div>
                <div><span class="text-gray-400">Balance:</span> <span class="text-emerald-500 font-bold">{{ client.monthly_fee_due }}</span></div>
                <div><span class="text-gray-400">Due:</span> <span class="text-red-500 font-bold">0</span></div>
              </td>
              <td v-if="isColVisible('all_date')" class="px-4 py-3">
                <div class="text-gray-600 font-medium">{{ formatDate(client.joining_date) }}</div>
              </td>
              <td v-if="isColVisible('day_left')" class="px-4 py-3">
                <span class="text-emerald-600 font-bold">{{ getDaysLeft(client.expire_date) }}</span>
              </td>
              <td v-if="isColVisible('status')" class="px-4 py-3 space-y-1">
                <div :class="[
                  'inline-block px-3 py-0.5 rounded text-[9px] font-bold tracking-wider w-full text-center uppercase',
                  client.payment_status === 'paid' ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white'
                ]">{{ client.payment_status || 'UNPAID' }}</div>
                <div :class="[
                  'inline-block px-3 py-0.5 rounded text-[9px] font-bold tracking-wider w-full text-center uppercase',
                  client.status === 'active' ? 'bg-teal-600 text-white' : 'bg-gray-500 text-white'
                ]">{{ client.status || 'INACTIVE' }}</div>
                <div class="inline-block px-3 py-0.5 bg-indigo-600 text-white rounded text-[9px] font-bold tracking-wider w-full text-center uppercase">{{ client.billing_type || 'MONTHLY' }}</div>
              </td>
              <td v-if="isColVisible('actions')" class="px-4 py-3 text-center">
                <div class="flex items-center justify-center gap-2">
                   <button @click="Swal.fire('Info', 'Billing details coming soon', 'info')" class="p-1.5 text-sky-500 hover:bg-sky-50 rounded-md transition-colors border border-sky-100" title="Billing History">
                     <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                   </button>
                   <!-- Interactive Toggle Button -->
                   <button 
                     @click="toggleStatus(client)"
                     :class="client.status === 'active' ? 'bg-emerald-500' : 'bg-gray-300'"
                     class="relative inline-flex items-center h-4 w-8 rounded-full transition-colors focus:outline-none"
                   >
                      <span 
                        :class="client.status === 'active' ? 'translate-x-4' : 'translate-x-1'"
                        class="inline-block h-3 w-3 rounded-full bg-white transition-transform"
                      ></span>
                   </button>
                   <div class="relative inline-block text-left">
                     <button @click="toggleAction(client.id)" class="text-gray-400 hover:text-gray-600 p-1">
                       <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path></svg>
                     </button>
                     <div v-if="activeAction === client.id" class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded shadow-lg z-[100] p-2 text-left">
                       <router-link :to="`/client/view/${client.id}`" class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded text-xs font-bold text-gray-700">View Profile</router-link>
                       <router-link :to="`/client/edit/${client.id}`" class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded text-xs font-bold text-gray-700">Edit Client</router-link>
                     </div>
                   </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination Footer -->
      <div class="px-6 py-3 border-t border-gray-50 bg-white flex items-center justify-between text-xs text-gray-500 font-medium">
        <div class="flex items-center gap-4">
           <span>Rows per page:</span>
           <select v-model="filters.per_page" @change="fetchClients" class="border-0 bg-transparent py-0 focus:ring-0 cursor-pointer">
             <option :value="10">10</option>
             <option :value="20">20</option>
             <option :value="50">50</option>
             <option :value="100">100</option>
           </select>
        </div>
        
        <div class="flex items-center gap-6">
           <span>{{ meta.from }}-{{ meta.to }} of {{ meta.total }}</span>
           <div class="flex gap-2">
             <button @click="changePage(meta.current_page - 1)" :disabled="meta.current_page <= 1" class="px-2 py-1 disabled:opacity-30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
             </button>
             <button @click="changePage(meta.current_page + 1)" :disabled="meta.current_page >= meta.last_page" class="px-2 py-1 disabled:opacity-30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
             </button>
           </div>
           <div class="flex items-center gap-2">
              <span>Go to page:</span>
              <input type="number" v-model="filters.page" @keyup.enter="fetchClients" class="w-12 h-6 border border-gray-100 rounded text-center focus:ring-1 focus:ring-sky-500 outline-none">
           </div>
        </div>
      </div>
    </div>

    <!-- Filter Modal -->
    <div v-if="showFilterModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
       <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl overflow-hidden max-h-[90vh] flex flex-col">
          <div class="bg-[#0f4340] px-6 py-3 text-white flex items-center justify-between shrink-0">
             <span class="font-bold text-sm uppercase">Client Filter</span>
             <button @click="showFilterModal = false">
                <svg class="w-5 h-5 text-white/70 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
             </button>
          </div>
          <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4 overflow-y-auto">
             <!-- Row 1 -->
             <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Router</label>
                <select v-model="filters.server_id" class="w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-xs py-2 px-3 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                   <option value="">Select...</option>
                   <option v-for="router in dropdowns.routers" :key="router.id" :value="router.id">{{ router.name }}</option>
                </select>
             </div>
             <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Zone</label>
                <select v-model="filters.zone_id" class="w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-xs py-2 px-3 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                   <option value="">Select...</option>
                   <option v-for="zone in dropdowns.zones" :key="zone.id" :value="zone.id">{{ zone.name }}</option>
                </select>
             </div>
             <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Sub Zone</label>
                <select v-model="filters.sub_zone_id" class="w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-xs py-2 px-3 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                   <option value="">Select...</option>
                   <option v-for="sz in filteredSubZones" :key="sz.id" :value="sz.id">{{ sz.name }}</option>
                </select>
             </div>

             <!-- Row 2 -->
             <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Billing Cycle</label>
                <div class="w-full">
                    <VueDatePicker 
                        v-model="billingCycleRange" 
                        range 
                        :enable-time-picker="false" 
                        :auto-apply="true"
                        placeholder="Select Date Range" 
                        teleport="body" 
                    />
                </div>
             </div>
             <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Box</label>
                <select v-model="filters.box_id" class="w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-xs py-2 px-3 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                   <option value="">Select...</option>
                   <option v-for="box in filteredBoxes" :key="box.id" :value="box.id">{{ box.name }}</option>
                </select>
             </div>
             <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Packages</label>
                <select v-model="filters.package_id" class="w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-xs py-2 px-3 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                   <option value="">Select...</option>
                   <option v-for="pkg in dropdowns.packages" :key="pkg.id" :value="pkg.id">{{ pkg.name }}</option>
                </select>
             </div>

             <!-- Row 3 -->
             <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Expire Date</label>
                <div class="w-full">
                    <VueDatePicker 
                        v-model="expireDateRange" 
                        range 
                        :enable-time-picker="false" 
                        :auto-apply="true"
                        placeholder="Select Date Range" 
                        teleport="body" 
                    />
                </div>
             </div>
             <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Billing Type</label>
                <select v-model="filters.billing_type" class="w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-xs py-2 px-3 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                   <option value="">Select...</option>
                   <option value="monthly">Monthly</option>
                   <option value="one_time">One Time</option>
                </select>
             </div>
             <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Client Type</label>
                <select v-model="filters.client_type_id" class="w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-xs py-2 px-3 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                   <option value="">Select...</option>
                   <option v-for="ctype in dropdowns.client_types" :key="ctype.id" :value="ctype.id">{{ ctype.name }}</option>
                   <!-- Fallback if no API -->
                   <option v-if="dropdowns.client_types.length === 0" value="home">Home</option>
                   <option v-if="dropdowns.client_types.length === 0" value="corporate">Corporate</option>
                </select>
             </div>

             <!-- Row 4 -->
             <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Promise Date</label>
                <div class="w-full">
                    <VueDatePicker 
                        v-model="promiseDateRange" 
                        range 
                        :enable-time-picker="false" 
                        :auto-apply="true"
                        placeholder="Select Date Range" 
                        teleport="body" 
                    />
                </div>
             </div>
             <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Payment Status</label>
                <select v-model="filters.payment_status" class="w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-xs py-2 px-3 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                   <option value="">Select...</option>
                   <option value="paid">Paid</option>
                   <option value="unpaid">Unpaid</option>
                </select>
             </div>
             <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Status</label>
                <select v-model="filters.status" class="w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-xs py-2 px-3 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                   <option value="">Select...</option>
                   <option value="active">Active</option>
                   <option value="inactive">Inactive</option>
                   <option value="suspended">Suspended</option>
                   <option value="expired">Expired</option>
                </select>
             </div>

             <!-- Row 5 -->
             <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Expire Date Left</label>
                <input type="number" v-model="filters.expire_date_left" placeholder="Expire Date Left" class="w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-xs py-2 px-3 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
             </div>
             <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Billing Cycle Left</label>
                <input type="number" v-model="filters.billing_cycle_left" placeholder="Billing Cycle Left" class="w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-xs py-2 px-3 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
             </div>
             <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Promise Date Left</label>
                <input type="number" v-model="filters.promise_date_left" placeholder="Promise Date Left" class="w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-xs py-2 px-3 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
             </div>
          </div>
          <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-100 shrink-0">
             <button @click="resetFilters" class="px-6 py-2 border border-gray-300 rounded-lg text-xs font-bold text-gray-600 hover:bg-white transition-colors uppercase tracking-wide">Reset</button>
             <button @click="applyFilters" class="px-6 py-2 bg-[#0f4340] text-white rounded-lg text-xs font-bold hover:bg-[#0a2f2d] transition-colors uppercase tracking-wide shadow-md">Submit</button>
          </div>
       </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import { VueDatePicker } from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const clients = ref([]);
const loading = ref(false);
const showFilterModal = ref(false);
const showVisibilityMenu = ref(false);
const selectedClients = ref([]);
const activeAction = ref(null);
const summary = ref({ total_bill: 0, total_balance: 0 });
const meta = ref({ current_page: 1, last_page: 1, total: 0, from: 0, to: 0 });

const dropdowns = ref({
    zones: [],
    sub_zones: [],
    boxes: [],
    packages: [],
    routers: [],
    client_types: [],
});

const filteredSubZones = ref([]);
const filteredBoxes = ref([]);

const columns = ref([
    { key: 'select', label: 'Select', visible: true },
    { key: 'name', label: 'Name', visible: true },
    { key: 'pppoe', label: 'PPPoE', visible: true },
    { key: 'zone', label: 'Zone', visible: true },
    { key: 'package', label: 'Package', visible: true },
    { key: 'bill', label: 'Bill', visible: true },
    { key: 'all_date', label: 'All Date', visible: true },
    { key: 'day_left', label: 'Day Left', visible: true },
    { key: 'status', label: 'Status', visible: true },
    { key: 'actions', label: 'Actions', visible: true },
]);

// Updated Filters Object matching the requirement
const filters = ref({
    server_id: '',
    zone_id: '',
    sub_zone_id: '',
    box_id: '',
    package_id: '',
    billing_type: '',
    client_type_id: '', // Assuming mapped to client_type
    status: '',
    payment_status: '',
    
    // Date Ranges & Inputs (Backend expects these)
    billing_cycle_start: '',
    billing_cycle_end: '',
    expire_date_start: '',
    expire_date_end: '',
    promise_date_start: '',
    promise_date_end: '',
    
    expire_date_left: '',
    billing_cycle_left: '',
    promise_date_left: '',

    search: '',
    page: 1,
    per_page: 50
});

// UI refs for DatePickers
const billingCycleRange = ref();
const expireDateRange = ref();
const promiseDateRange = ref();

const fetchClients = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/api/config/clients', { params: filters.value });
        clients.value = res.data.clients.data;
        summary.value = res.data.summary;
        meta.value = {
            current_page: res.data.clients.current_page,
            last_page: res.data.clients.last_page,
            total: res.data.clients.total,
            from: res.data.clients.from,
            to: res.data.clients.to
        };
    } catch (error) {
        console.error("Failed to fetch clients", error);
    } finally {
        loading.value = false;
    }
};

const fetchDropdowns = async () => {
    try {
        // Fetch all necessary dropdowns
        const [zonesRes, subZonesRes, boxesRes, pkgsRes, routersRes, clientTypesRes] = await Promise.all([
            axios.get('/api/config/zones', { params: { no_paginate: true } }),
            axios.get('/api/config/sub-zones', { params: { no_paginate: true } }),
            axios.get('/api/config/boxes', { params: { no_paginate: true } }),
            axios.get('/api/config/packages', { params: { no_paginate: true } }),
            axios.get('/api/config/mikrotik-routers', { params: { no_paginate: true } }),
            axios.get('/api/config/client-types', { params: { no_paginate: true } }),
        ]);

        dropdowns.value.zones = zonesRes.data;
        dropdowns.value.sub_zones = subZonesRes.data;
        dropdowns.value.boxes = boxesRes.data;
        dropdowns.value.packages = pkgsRes.data;
        dropdowns.value.routers = routersRes.data;
        dropdowns.value.client_types = clientTypesRes.data;
    } catch (e) {
        console.error("Failed to fetch dropdowns", e);
    }
}

// Watchers for cascading dropdowns
watch(() => filters.value.zone_id, (newVal) => {
    if (newVal) {
        filteredSubZones.value = dropdowns.value.sub_zones.filter(sz => sz.zone_id == newVal);
    } else {
        filteredSubZones.value = [];
    }
    // filters.value.sub_zone_id = ''; // Optional: clear subzone when zone changes
});

watch(() => filters.value.sub_zone_id, (newVal) => {
    if (newVal) {
        filteredBoxes.value = dropdowns.value.boxes.filter(b => b.sub_zone_id == newVal);
    } else {
        filteredBoxes.value = [];
    }
    // filters.value.box_id = '';
});


const handleSearch = (() => {
    let timeout;
    return () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            filters.value.page = 1;
            fetchClients();
        }, 500);
    };
})();

const changePage = (page) => {
    filters.value.page = page;
    fetchClients();
};

const toggleAction = (id) => {
    activeAction.value = activeAction.value === id ? null : id;
};

const toggleStatus = async (client) => {
    const newStatus = client.status === 'active' ? 'inactive' : 'active';
    try {
        await axios.put(`/api/config/clients/${client.id}`, { 
            status: newStatus,
            _method: 'PUT'
        });
        client.status = newStatus;
        Swal.fire({
            title: 'Updated!',
            text: `Client is now ${newStatus}.`,
            icon: 'success',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
    } catch (e) {
        Swal.fire('Error', 'Failed to update status', 'error');
    }
};

const applyFilters = () => {
    // Process Date Ranges
    if (billingCycleRange.value && billingCycleRange.value.length === 2) {
        filters.value.billing_cycle_start = formatDateForApi(billingCycleRange.value[0]);
        filters.value.billing_cycle_end = formatDateForApi(billingCycleRange.value[1]);
    } else {
         filters.value.billing_cycle_start = '';
         filters.value.billing_cycle_end = '';
    }

    if (expireDateRange.value && expireDateRange.value.length === 2) {
        filters.value.expire_date_start = formatDateForApi(expireDateRange.value[0]);
        filters.value.expire_date_end = formatDateForApi(expireDateRange.value[1]);
    } else {
        filters.value.expire_date_start = '';
        filters.value.expire_date_end = '';
    }

    if (promiseDateRange.value && promiseDateRange.value.length === 2) {
        filters.value.promise_date_start = formatDateForApi(promiseDateRange.value[0]);
        filters.value.promise_date_end = formatDateForApi(promiseDateRange.value[1]);
    } else {
         filters.value.promise_date_start = '';
         filters.value.promise_date_end = '';
    }

    filters.value.page = 1;
    fetchClients();
    showFilterModal.value = false;
};

const resetFilters = () => {
    const defaultFilters = {
        server_id: '',
        zone_id: '',
        sub_zone_id: '',
        box_id: '',
        package_id: '',
        billing_type: '',
        client_type_id: '',
        status: '',
        payment_status: '',
        billing_cycle_start: '',
        billing_cycle_end: '',
        expire_date_start: '',
        expire_date_end: '',
        promise_date_start: '',
        promise_date_end: '',
        expire_date_left: '',
        billing_cycle_left: '',
        promise_date_left: '',
        search: '',
        page: 1,
        per_page: 50
    };
    filters.value = defaultFilters;
    // Reset UI Date pickers
    billingCycleRange.value = null;
    expireDateRange.value = null;
    promiseDateRange.value = null;
    
    fetchClients();
}

const selectAll = (event) => {
    if (event.target.checked) {
        selectedClients.value = clients.value.map(c => c.id);
    } else {
        selectedClients.value = [];
    }
};

const getDaysLeft = (expireDate) => {
    if (!expireDate) return 'N/A';
    const expire = new Date(expireDate);
    const today = new Date();
    today.setHours(0,0,0,0);
    const diffTime = expire - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays >= 0 ? diffDays : 0;
};

const isColVisible = (key) => columns.value.find(c => c.key === key)?.visible;

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

// Helper to format Date object to YYYY-MM-DD
const formatDateForApi = (date) => {
    if (!date) return '';
    const d = new Date(date);
    let month = '' + (d.getMonth() + 1);
    let day = '' + d.getDate();
    const year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

const exportCSV = () => {
    const headers = columns.value.filter(c => c.key !== 'select' && c.key !== 'actions').map(c => c.label).join(',');
    const rows = clients.value.map(c => {
        return [
            c.name,
            c.username,
            c.zone?.name || 'N/A',
            c.package?.name || 'N/A',
            c.monthly_fee,
            formatDate(c.joining_date),
            c.status
        ].join(',');
    }).join('\n');
    
    const blob = new Blob([`${headers}\n${rows}`], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `clients_export_${new Date().getTime()}.csv`;
    a.click();
};

onMounted(() => {
    fetchClients();
    fetchDropdowns();
});
</script>

<style scoped>
input[type="checkbox"] {
  outline: none;
  transition: all 0.2s ease;
  cursor: pointer;
}
/* Table Header Sticky (Optional) */
thead th {
  position: sticky;
  top: 0;
  z-index: 10;
}
/* Datepicker Override */
.dp__theme_light {
   --dp-primary-color: #10b981; /* Emerald 500 */
   --dp-border-radius: 8px; 
   --dp-input-padding: 8px 12px;
   --dp-font-size: 11px;
}
</style>
