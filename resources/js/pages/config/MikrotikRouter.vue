<template>
  <div class="p-2 h-full flex flex-col">
    <!-- Data Table -->
    <DataTable 
      :columns="columns" 
      :data="routers" 
      :loading="loading" 
      :pagination="pagination"
      title="List Of Mikrotik Router"
      @update:search="search = $event; fetchRouters()"
      @page-change="fetchRouters"
      @refresh="fetchRouters"
    >
      <template #add-button>
          <button 
            @click="openModal()"
            class="p-1.5 text-teal-500 bg-white border border-gray-200 rounded-md hover:bg-gray-50 focus:outline-none"
          >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
          </button>
      </template>

      <template #connection="{ item }">
        <ConnectionStatus :routerId="item.id" />
      </template>
      <template #actions="{ item }">
        <div class="flex items-center justify-end space-x-2">
            <button @click="openModal(item)" title="Edit" class="text-teal-500 hover:text-teal-700">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
            <ActionMenu :items="getActions(item)" />
        </div>
      </template>
    </DataTable>

    <!-- Form Modal -->
    <FormModal 
      :show="showModal" 
      :title="editingRouter ? 'Edit Router' : 'Add New Router'" 
      :loading="saving"
      @close="closeModal"
      @submit="saveRouter"
    >
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <!-- Name -->
        <div class="col-span-1">
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Router Name</label>
            <input v-model="form.name" type="text" class="w-full text-sm text-gray-700 border border-gray-200 rounded px-3 py-2 leading-tight focus:outline-none focus:border-[#00bcd4] transition-colors" placeholder="e.g. Main Router">
             <p v-if="errors.name" class="mt-1 text-xs text-red-500">{{ errors.name[0] }}</p>
        </div>

        <!-- IP Address -->
        <div class="col-span-1">
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">IP Address</label>
            <input v-model="form.ip_address" type="text" class="w-full text-sm text-gray-700 border border-gray-200 rounded px-3 py-2 leading-tight focus:outline-none focus:border-[#00bcd4] transition-colors" placeholder="192.168.88.1">
            <p v-if="errors.ip_address" class="mt-1 text-xs text-red-500">{{ errors.ip_address[0] }}</p>
        </div>

        <!-- Username -->
        <div class="col-span-1">
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Username</label>
            <input v-model="form.username" type="text" class="w-full text-sm text-gray-700 border border-gray-200 rounded px-3 py-2 leading-tight focus:outline-none focus:border-[#00bcd4] transition-colors" placeholder="admin">
            <p v-if="errors.username" class="mt-1 text-xs text-red-500">{{ errors.username[0] }}</p>
        </div>

        <!-- Password -->
        <div class="col-span-1">
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Password</label>
            <input v-model="form.password" type="password" class="w-full text-sm text-gray-700 border border-gray-200 rounded px-3 py-2 leading-tight focus:outline-none focus:border-[#00bcd4] transition-colors" placeholder="••••••">
            <p v-if="errors.password" class="mt-1 text-xs text-red-500">{{ errors.password[0] }}</p>
        </div>

        <!-- API Port -->
        <div class="col-span-1">
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">API Port</label>
            <input v-model="form.port" type="number" class="w-full text-sm text-gray-700 border border-gray-200 rounded px-3 py-2 leading-tight focus:outline-none focus:border-[#00bcd4] transition-colors" placeholder="8728">
            <p v-if="errors.port" class="mt-1 text-xs text-red-500">{{ errors.port[0] }}</p>
        </div>
        
        <!-- Sync Time -->
        <div class="col-span-1 md:col-span-2">
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Sync Time</label>
            <div class="flex items-center space-x-4">
                 <div class="flex items-center cursor-pointer">
                    <input type="radio" id="seconds" value="seconds" v-model="form.sync_timer_unit" class="text-[#00bcd4] focus:ring-[#00bcd4] cursor-pointer">
                    <label for="seconds" class="ml-2 text-sm text-gray-600 cursor-pointer">Seconds</label>
                 </div>
                 <div class="flex items-center cursor-pointer">
                    <input type="radio" id="minutes" value="minutes" v-model="form.sync_timer_unit" class="text-[#00bcd4] focus:ring-[#00bcd4] cursor-pointer">
                    <label for="minutes" class="ml-2 text-sm text-gray-600 cursor-pointer">Minutes</label>
                 </div>
                 <input v-model="form.sync_timer_value" type="number" min="1" class="w-24 text-sm text-gray-700 border border-gray-200 rounded px-3 py-2 leading-tight focus:outline-none focus:border-[#00bcd4] transition-colors">
            </div>
             <p v-if="errors.sync_timer_value" class="mt-1 text-xs text-red-500">{{ errors.sync_timer_value[0] }}</p>
        </div>

        <!-- Test Connection Button inside Modal -->
         <div class="col-span-1 md:col-span-2 flex justify-end">
            <button type="button" @click="testFormConnection" class="text-sm font-medium text-[#00bcd4] hover:text-[#00acc1] flex items-center transition-colors">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Test Connection
            </button>
         </div>
      </div>
    </FormModal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DataTable from '../../components/config/DataTable.vue';
import FormModal from '../../components/config/FormModal.vue';
import ActionMenu from '../../components/config/ActionMenu.vue';
import ConnectionStatus from '../../components/config/ConnectionStatus.vue';
import { toast, confirmAlert } from '../../components/config/swal.js';

const routers = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const editingRouter = ref(null);
const search = ref('');
const pagination = ref({ current_page: 1, total: 0 });
const errors = ref({});

const columns = [
  { key: 'id', label: '#' },
  { key: 'name', label: 'Name', class: 'font-medium text-gray-900' },
  { key: 'username', label: 'Username' },
  { key: 'ip_address', label: 'IP Address' },
  { key: 'port', label: 'Port' },
  { key: 'connection', label: 'Connection', class: 'text-center' },
  { key: 'actions', label: 'Actions', class: 'text-right' },
];

const form = ref({
  name: '',
  ip_address: '',
  username: '',
  password: '',
  port: 8728,
  sync_timer_unit: 'minutes',
  sync_timer_value: 5,
});

const getActions = (item) => [
    {
        label: 'Edit',
        icon: '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>',
        onClick: () => openModal(item)
    },
    {
        label: 'Test Connection',
        icon: '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>',
        onClick: () => testConnectionRow(item)
    },
    {
        label: 'Delete',
        icon: '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>',
        class: 'text-red-600 hover:bg-red-50',
        onClick: () => deleteRouter(item)
    }
];

const testConnectionRow = async (item) => {
    try {
        const response = await axios.post(`/api/config/mikrotik-routers/${item.id}/test-connection`);
        alert(`Status: Connected\n${response.data.message || ''}`);
        // Optionally refresh table to update component? 
        // The component is self-contained but table refresh is good.
        fetchRouters(pagination.value.current_page);
    } catch (error) {
        alert(`Status: Failed\n${error.response?.data?.message || 'Connection failed'}`);
    }
};

const fetchRouters = async (page = 1) => {
  loading.value = true;
  try {
    const response = await axios.get('/api/config/mikrotik-routers', {
      params: { page, search: search.value }
    });
    routers.value = response.data.data;
    pagination.value = response.data; // Stores meta & links
  } catch (error) {
    console.error('Error fetching routers:', error);
  } finally {
    loading.value = false;
  }
};

const openModal = (router = null) => {
  errors.value = {};
  if (router) {
    editingRouter.value = router;
    form.value = { ...router, password: '' }; // Don't show password
  } else {
    editingRouter.value = null;
    form.value = {
      name: '',
      ip_address: '',
      username: '',
      password: '',
      port: 8728,
      sync_timer_unit: 'minutes',
      sync_timer_value: 5,
    };
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingRouter.value = null;
};

const saveRouter = async () => {
  saving.value = true;
  errors.value = {};
  try {
    if (editingRouter.value) {
      await axios.put(`/api/config/mikrotik-routers/${editingRouter.value.id}`, form.value);
      toast.fire({ icon: 'success', title: 'Router updated successfully' });
    } else {
      await axios.post('/api/config/mikrotik-routers', form.value);
      toast.fire({ icon: 'success', title: 'Router created successfully' });
    }
    closeModal();
    fetchRouters(pagination.value.current_page);
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors;
       toast.fire({ icon: 'error', title: 'Validation error', text: 'Please check the form input.' });
    } else {
      console.error('Error saving router:', error);
       toast.fire({ icon: 'error', title: 'Failed to save router' });
    }
  } finally {
    saving.value = false;
  }
};

const deleteRouter = async (item) => {
    const result = await confirmAlert('Are you sure?', 'You verify deleting this router!');
    if (result.isConfirmed) {
        try {
            await axios.delete(`/api/config/mikrotik-routers/${item.id}`);
            toast.fire({ icon: 'success', title: 'Router deleted successfully' });
            fetchRouters(pagination.value.current_page);
        } catch (error) {
           toast.fire({ icon: 'error', title: 'Failed to delete router' });
           console.error(error);
        }
    }
};

const testConnection = async (router) => {
    // This requires password, but we don't have it in the list (hidden).
    // Usually connection test is done inside the edit modal or we prompt for password.
    // For now, let's assume we can only test inside the form OR we rely on separate endpoint that uses stored password (backend side).
    // Since backend implementation of `testConnection` expects full credentials, we should use the form fields.
    alert("Please open Edit modal to test connection (requires re-entering password if changed) or implement backend stored password testing.");
}

const testFormConnection = async () => {
    if (!form.value.ip_address || !form.value.username || !form.value.password) {
        alert("Please fill in IP, Username and Password");
        return;
    }
    
    try {
        const response = await axios.post('/api/config/mikrotik-routers/test-connection', form.value);
        alert(response.data.message);
    } catch (error) {
         alert(error.response?.data?.message || "Connection Failed");
    }
}

onMounted(() => {
  fetchRouters();
});
</script>
