<template>
  <div class="bg-white rounded-lg shadow-sm h-full flex flex-col">
    <!-- Top Header Section -->
    <div class="px-6 py-4 flex flex-col sm:flex-row justify-between items-center bg-white rounded-t-lg border-b border-gray-100">
      <!-- Title & Count -->
      <h2 class="text-lg font-semibold text-gray-700 mb-4 sm:mb-0">
        {{ title }} : <span class="text-gray-900">{{ pagination?.total || 0 }}</span>
      </h2>

      <!-- Right Controls -->
      <div class="flex items-center space-x-3 w-full sm:w-auto">
        <!-- Search -->
        <div class="relative flex-1 sm:max-w-xs">
          <input 
                v-model="searchTerm"
                type="text" 
                placeholder="Search..." 
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-[#00bcd4] focus:ring-[#00bcd4] sm:text-sm transition duration-150 ease-in-out"
            >
          <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </span>
        </div>

        <!-- Action Buttons Group -->
        <div class="flex items-center space-x-1">
            <button class="px-3 py-1.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-md hover:bg-gray-50 focus:outline-none">
                All visible
            </button>
            <button @click="exportToCSV" class="p-1.5 text-yellow-500 bg-white border border-gray-200 rounded-md hover:bg-gray-50 focus:outline-none" title="Export to CSV">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            </button>
            <button class="p-1.5 text-[#00bcd4] bg-white border border-gray-200 rounded-md hover:bg-gray-50 focus:outline-none" @click="$emit('refresh')">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
            <slot name="add-button"></slot>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="flex-1 overflow-auto relative">
      <div v-if="loading" class="absolute inset-0 bg-white/80 z-10 flex items-center justify-center">
        <svg class="animate-spin h-8 w-8 text-[#00bcd4]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
      
      <table class="min-w-full divide-y divide-gray-100 table-auto">
        <thead class="bg-gray-50/50 sticky top-0 z-10">
          <tr>
            <th 
              v-for="col in columns" 
              :key="col.key"
              class="px-4 py-3 text-left text-xs font-bold text-gray-800 uppercase tracking-wide border-b border-gray-100"
              :class="col.class"
            >
              {{ col.label }}
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-50">
          <tr v-if="data.length === 0" class="hover:bg-gray-50/50">
            <td :colspan="columns.length" class="px-4 py-8 text-center text-sm text-gray-500">
              No data found
            </td>
          </tr>
          <tr v-for="(item, index) in data" :key="item.id" class="hover:bg-blue-50/10 transition-colors group">
            <td 
              v-for="col in columns" 
              :key="col.key"
              class="px-4 py-3.5 text-sm text-gray-600 whitespace-nowrap"
              :class="[col.class, col.tdClass]"
            >
              <slot :name="col.key" :item="item" :index="index">
                {{ item[col.key] }}
              </slot>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="pagination && pagination.total > 0" class="px-6 py-4 border-t border-gray-100 flex items-center justify-end space-x-4 bg-white">
        <!-- Per Page (Mock) -->
        <select class="border border-gray-200 rounded text-sm p-1 text-gray-600 focus:outline-none focus:ring-[#00bcd4]">
            <option>100</option>
            <option>50</option>
            <option>20</option>
        </select>

        <!-- Navigation Buttons -->
        <div class="flex items-center space-x-1">
             <!-- First -->
            <button 
              @click="$emit('page-change', 1)"
              :disabled="pagination.current_page === 1"
              class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded text-gray-500 hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed text-xs transition-colors"
            >
               &lt;&lt;
            </button>
            
            <!-- Prev -->
            <button 
              :disabled="!pagination.prev_page_url"
              @click="$emit('page-change', pagination.current_page - 1)"
               class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded text-gray-500 hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              &lt;
            </button>

            <!-- Next -->
            <button 
              :disabled="!pagination.next_page_url"
              @click="$emit('page-change', pagination.current_page + 1)"
               class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded text-gray-500 hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              &gt;
            </button>
            
            <!-- Last -->
             <button 
              @click="$emit('page-change', pagination.last_page)"
              :disabled="pagination.current_page === pagination.last_page"
              class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded text-gray-500 hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed text-xs transition-colors"
            >
               &gt;&gt;
            </button>
        </div>

        <!-- Info Text -->
        <div class="text-sm text-gray-600 flex items-center space-x-1">
             <span>Page {{ pagination.current_page }} of {{ pagination.last_page }}</span>
             <span class="text-gray-400">|</span>
             <span>Go to page:</span>
             <input 
                type="number" 
                :value="pagination.current_page"
                @change="$emit('page-change', parseInt($event.target.value))"
                class="w-12 border border-gray-200 rounded p-1 text-center text-sm focus:border-[#00bcd4] focus:outline-none"
             >
        </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
const props = defineProps({
  columns: {
    type: Array,
    required: true
  },
  data: {
    type: Array,
    required: true
  },
  pagination: {
    type: Object,
    default: null
  },
  loading: {
    type: Boolean,
    default: false
  },
  search: {
    type: String,
    default: ''
  },
  title: {
      type: String,
      default: 'List'
  }
});

const emit = defineEmits(['update:search', 'page-change', 'refresh']);

const searchTerm = ref(props.search);
let timeout;

watch(() => props.search, (val) => {
    if (val !== searchTerm.value) searchTerm.value = val;
});

watch(searchTerm, (val) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        emit('update:search', val);
    }, 300);
});

const exportToCSV = () => {
    if (!props.data || !props.data.length) return;
    
    // Get headers
    const headers = cols.map(col => `"${col.label}"`).join(',');
    
    // Get rows
    const rows = props.data.map(row => {
        return cols.map(col => {
            let val = row[col.key];
            if (val === null || val === undefined) val = '';
            // Escape double quotes
            val = String(val).replace(/"/g, '""');
            return `"${val}"`;
        }).join(',');
    }).join('\n');
    
    const csvContent = `${headers}\n${rows}`;
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `${props.title.replace(/\s+/g, '_').toLowerCase()}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
};

const cols = props.columns.filter(c => c.key !== 'actions' && c.key !== 'connection'); // Exclude action/connection cols
</script>
