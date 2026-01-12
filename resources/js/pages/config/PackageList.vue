<template>
  <div class="p-6 bg-[#eff3f6] min-h-screen font-sans">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
      <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
          <div class="bg-emerald-500 p-3 rounded-xl text-white shadow-lg shadow-emerald-500/20 hidden sm:block">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
          <div>
            <div class="flex items-center gap-3">
              <h1 class="text-xl font-black text-slate-800 tracking-tight">Packages</h1>
              <span class="bg-emerald-100 text-emerald-700 px-2.5 py-0.5 rounded-full text-xs font-black ring-1 ring-emerald-500/20">
                {{ meta.total }}
              </span>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <button @click="fetchPackages" class="p-2.5 bg-white border border-slate-200 text-slate-500 rounded-xl hover:text-emerald-500 hover:border-emerald-200 hover:bg-emerald-50 transition-all shadow-sm" title="Refresh">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
          </button>
          <button @click="openModal()" class="flex items-center gap-2 px-4 py-2.5 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-all text-xs font-black shadow-lg shadow-emerald-500/20 uppercase tracking-wider">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Package
          </button>
        </div>
      </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-[#f8fafc] text-gray-700 text-[11px] uppercase font-bold border-b border-gray-100">
              <th class="px-4 py-4">Name</th>
              <th class="px-4 py-4">Speed</th>
              <th class="px-4 py-4">Price</th>
              <th class="px-4 py-4">Status</th>
              <th class="px-4 py-4 text-center">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-if="loading" class="animate-pulse">
              <td colspan="5" class="px-6 py-20 text-center text-gray-400">
                <div class="flex flex-col items-center gap-2">
                  <div class="w-8 h-8 border-4 border-sky-500 border-t-transparent rounded-full animate-spin"></div>
                  <span class="text-xs font-medium">Loading packages...</span>
                </div>
              </td>
            </tr>
            <tr v-else-if="packages.length === 0">
              <td colspan="5" class="px-6 py-20 text-center text-gray-500 text-xs italic">No packages found.</td>
            </tr>
            <tr v-for="pkg in packages" :key="pkg.id" class="hover:bg-gray-50/80 transition-colors text-xs">
              <td class="px-4 py-3">
                <div class="font-bold text-gray-700">{{ pkg.name }}</div>
                <div class="text-gray-400 text-[10px] mt-0.5">{{ pkg.description || 'No description' }}</div>
              </td>
              <td class="px-4 py-3">
                <span class="text-sky-500 font-bold">{{ pkg.speed || 'N/A' }}</span>
              </td>
              <td class="px-4 py-3">
                <span class="text-emerald-600 font-bold">৳{{ pkg.price }}</span>
              </td>
              <td class="px-4 py-3">
                <span :class="pkg.is_active ? 'bg-emerald-500 text-white' : 'bg-gray-500 text-white'" class="inline-block px-3 py-0.5 rounded text-[9px] font-bold tracking-wider uppercase">
                  {{ pkg.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="flex items-center justify-center gap-2">
                  <button @click="openModal(pkg)" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-md transition-colors border border-blue-100" title="Edit">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                  </button>
                  <button @click="deletePackage(pkg.id)" class="p-1.5 text-red-500 hover:bg-red-50 rounded-md transition-colors border border-red-100" title="Delete">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="px-6 py-3 border-t border-gray-50 bg-white flex items-center justify-between text-xs text-gray-500 font-medium">
        <div>{{ meta.from }}-{{ meta.to }} of {{ meta.total }}</div>
        <div class="flex gap-2">
          <button @click="changePage(meta.current_page - 1)" :disabled="meta.current_page <= 1" class="px-2 py-1 disabled:opacity-30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
          </button>
          <button @click="changePage(meta.current_page + 1)" :disabled="meta.current_page >= meta.last_page" class="px-2 py-1 disabled:opacity-30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Package Modal -->
    <PackageModal v-if="showModal" :package="selectedPackage" @close="closeModal" @saved="handleSaved" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import PackageModal from './PackageModal.vue';

const packages = ref([]);
const loading = ref(false);
const showModal = ref(false);
const selectedPackage = ref(null);
const meta = ref({ current_page: 1, last_page: 1, total: 0, from: 0, to: 0 });

const fetchPackages = async (page = 1) => {
  loading.value = true;
  try {
    const res = await axios.get('/api/config/packages', { params: { page } });
    packages.value = res.data.data;
    meta.value = {
      current_page: res.data.current_page,
      last_page: res.data.last_page,
      total: res.data.total,
      from: res.data.from,
      to: res.data.to
    };
  } catch (error) {
    console.error("Failed to fetch packages", error);
    Swal.fire('Error', 'Failed to load packages', 'error');
  } finally {
    loading.value = false;
  }
};

const changePage = (page) => {
  fetchPackages(page);
};

const openModal = (pkg = null) => {
  selectedPackage.value = pkg;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  selectedPackage.value = null;
};

const handleSaved = () => {
  closeModal();
  fetchPackages(meta.value.current_page);
};

const deletePackage = async (id) => {
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: "This package will be permanently deleted!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, delete it!'
  });

  if (result.isConfirmed) {
    try {
      await axios.delete(`/api/config/packages/${id}`);
      Swal.fire('Deleted!', 'Package has been deleted.', 'success');
      fetchPackages(meta.value.current_page);
    } catch (error) {
      Swal.fire('Error', 'Failed to delete package', 'error');
    }
  }
};

onMounted(() => {
  fetchPackages();
});
</script>

<style scoped>
thead th {
  position: sticky;
  top: 0;
  z-index: 10;
}
</style>
