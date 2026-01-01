<template>
  <div class="space-y-6">
    <DataTable
      title="List Of OLT Device"
      :columns="columns"
      :data="olts"
      :loading="loading"
      :pagination="pagination"
      :search="searchQuery"
      @search="handleSearch"
      @paginate="fetchOlts"
      @export="exportOlts"
    >
      <template #add-button>
        <div class="flex items-center space-x-2">
            <button
            @click="syncOltUsers"
            class="px-4 py-2 bg-white border border-[#00bcd4] text-[#00bcd4] rounded-lg hover:bg-[#e0f7fa] transition-colors shadow-sm text-sm font-medium"
            >
            Sync OLT Users
            </button>
            <button
            @click="openModal()"
            class="flex items-center space-x-2 px-4 py-2 bg-[#00bcd4] text-white rounded-lg hover:bg-[#00acc1] transition-colors shadow-sm"
            >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="text-sm font-medium">Add OLT</span>
            </button>
        </div>
      </template>

      <template #connection="{ item }">
        <ConnectionStatus :routerId="item.id" type="olt" />
      </template>

      <template #status="{ item }">
        <span
          :class="[
            'px-2 py-0.5 rounded-full text-xs font-medium',
            item.status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
          ]"
        >
          {{ item.status ? 'Active' : 'Inactive' }}
        </span>
      </template>

      <template #actions="{ item }">
        <ActionMenu :items="getActions(item)" />
      </template>
    </DataTable>

    <FormModal
      v-if="showModal"
      :show="showModal"
      :title="editingOlt ? 'Edit OLT Device' : 'Add OLT Device'"
      :saving="saving"
      @close="closeModal"
      @submit="saveOlt"
    >
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="space-y-1">
          <label class="text-xs font-semibold text-gray-600">OLT Vendor Name *</label>
          <select
            v-model="form.type"
            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-[#00bcd4] text-sm"
            :class="{ 'border-red-500': errors.type }"
          >
            <option value="">Select Vendor</option>
            <option v-for="vendor in vendors" :key="vendor" :value="vendor">{{ vendor }}</option>
          </select>
          <p v-if="errors.type" class="text-[10px] text-red-500">{{ errors.type[0] }}</p>
        </div>

        <div class="space-y-1">
          <label class="text-xs font-semibold text-gray-600">Name *</label>
          <input
            v-model="form.name"
            type="text"
            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-[#00bcd4] text-sm"
            :class="{ 'border-red-500': errors.name }"
            placeholder="Main OLT 01"
          />
          <p v-if="errors.name" class="text-[10px] text-red-500">{{ errors.name[0] }}</p>
        </div>

        <div class="space-y-1">
          <label class="text-xs font-semibold text-gray-600">PON Ports *</label>
          <input
            v-model="form.pon_ports"
            type="number"
            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-[#00bcd4] text-sm"
            :class="{ 'border-red-500': errors.pon_ports }"
          />
          <p v-if="errors.pon_ports" class="text-[10px] text-red-500">{{ errors.pon_ports[0] }}</p>
        </div>

        <div class="space-y-1">
          <label class="text-xs font-semibold text-gray-600">Host (IP) *</label>
          <input
            v-model="form.host"
            type="text"
            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-[#00bcd4] text-sm"
            :class="{ 'border-red-500': errors.host }"
            placeholder="192.168.1.10"
          />
          <p v-if="errors.host" class="text-[10px] text-red-500">{{ errors.host[0] }}</p>
        </div>

        <div class="space-y-1">
          <label class="text-xs font-semibold text-gray-600">Username</label>
          <input
            v-model="form.username"
            type="text"
            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-[#00bcd4] text-sm"
          />
          <p v-if="errors.username" class="text-[10px] text-red-500">{{ errors.username[0] }}</p>
        </div>

        <div class="space-y-1">
          <label class="text-xs font-semibold text-gray-600">Password</label>
          <input
            v-model="form.password"
            type="password"
            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-[#00bcd4] text-sm"
          />
          <p v-if="errors.password" class="text-[10px] text-red-500">{{ errors.password[0] }}</p>
        </div>

        <div class="space-y-1">
          <label class="text-xs font-semibold text-gray-600">Port *</label>
          <input
            v-model="form.port"
            type="number"
            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-[#00bcd4] text-sm"
            :class="{ 'border-red-500': errors.port }"
          />
          <p v-if="errors.port" class="text-[10px] text-red-500">{{ errors.port[0] }}</p>
        </div>

        <div class="flex items-center space-x-2 pt-6">
          <input
            v-model="form.status"
            id="status"
            type="checkbox"
            class="h-4 w-4 text-[#00bcd4] focus:ring-[#00bcd4] border-gray-300 rounded"
          />
          <label for="status" class="text-sm text-gray-700">Active</label>
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
import { toast, confirmAlert } from '../../components/config/swal';

const olts = ref([]);
const loading = ref(false);
const searchQuery = ref('');
const pagination = ref({
  current_page: 1,
  last_page: 1,
  total: 0
});



const vendors = [
  'VSOLE_EPON',
  'VSOLE_GPON',
  'CDATA_EPON',
  'BDCOM_EPON',
  'BDCOM_GPON',
  'DBC_GPON',
  'CORELINK_EPON'
];

const showModal = ref(false);
const saving = ref(false);
const editingOlt = ref(null);
const form = ref({
  name: '',
  type: '',
  pon_ports: 8,
  username: '',
  password: '',
  host: '',
  port: 161,
  status: true
});
const errors = ref({});

const fetchOlts = async (page = 1) => {
  loading.value = true;
  try {
    const response = await axios.get('/api/config/olts', {
      params: {
        page,
        search: searchQuery.value
      }
    });
    olts.value = response.data.data.map(item => ({
      ...item,
      created_at: new Date(item.created_at).toLocaleDateString()
    }));
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      total: response.data.total
    };
  } catch (error) {
    console.error('Error fetching OLTs:', error);
    toast.fire({ icon: 'error', title: 'Failed to fetch OLTs' });
  } finally {
    loading.value = false;
  }
};

const handleSearch = (query) => {
  searchQuery.value = query;
  fetchOlts(1);
};

const openModal = (olt = null) => {
  errors.value = {};
  if (olt) {
    editingOlt.value = olt;
    form.value = {
      name: olt.name,
      type: olt.type,
      pon_ports: olt.pon_ports,
      username: olt.username,
      password: '', // Don't show password
      host: olt.host,
      port: olt.port,
      status: !!olt.status
    };
  } else {
    editingOlt.value = null;
    form.value = {
      name: '',
      type: '',
      pon_ports: 8,
      username: '',
      password: '',
      host: '',
      port: 161,
      status: true
    };
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingOlt.value = null;
};

const saveOlt = async () => {
  saving.value = true;
  errors.value = {};
  try {
    if (editingOlt.value) {
      await axios.put(`/api/config/olts/${editingOlt.value.id}`, form.value);
      toast.fire({ icon: 'success', title: 'OLT updated successfully' });
    } else {
      await axios.post('/api/config/olts', form.value);
      toast.fire({ icon: 'success', title: 'OLT created successfully' });
    }
    closeModal();
    fetchOlts(pagination.value.current_page);
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors;
      toast.fire({ icon: 'error', title: 'Validation error' });
    } else {
      console.error('Error saving OLT:', error);
      toast.fire({ icon: 'error', title: 'Failed to save OLT' });
    }
  } finally {
    saving.value = false;
  }
};

const deleteOlt = async (item) => {
  const result = await confirmAlert('Are you sure?', 'You verify deleting this OLT!');
  if (result.isConfirmed) {
    try {
      await axios.delete(`/api/config/olts/${item.id}`);
      toast.fire({ icon: 'success', title: 'OLT deleted successfully' });
      fetchOlts(pagination.value.current_page);
    } catch (error) {
      toast.fire({ icon: 'error', title: 'Failed to delete OLT' });
      console.error(error);
    }
  }
};

import ConnectionStatus from '../../components/config/ConnectionStatus.vue';

// ... (other imports)

// ...

const columns = [
  { key: 'name', label: 'Name' },
  { key: 'type', label: 'OLT Vendor' },
  { key: 'pon_ports', label: 'Pon Port' },
  { key: 'port', label: 'Port' },
  { key: 'host', label: 'Host' },
  { key: 'connection', label: 'Connection', class: 'text-center' }, // New Column
  { key: 'created_at', label: 'Created At' },
  { key: 'actions', label: 'Action', class: 'text-right' }
];

// ...

const syncOltUsers = async () => {
   // Placeholder for global sync or modal trigger
   toast.fire({ icon: 'info', title: 'Sync OLT Users feature coming soon!' });
};

const syncUser = async (item) => {
    try {
        await axios.post(`/api/config/olts/${item.id}/sync-users`);
        toast.fire({ icon: 'success', title: 'User sync started' });
    } catch (e) {
        toast.fire({ icon: 'error', title: 'Failed to sync users' });
    }
};

const rebootOlt = async (item) => {
    const result = await confirmAlert('Are you sure?', 'You verify rebooting this OLT!');
    if (result.isConfirmed) {
        try {
             await axios.post(`/api/config/olts/${item.id}/reboot`);
             toast.fire({ icon: 'success', title: 'Reboot command sent' });
        } catch (e) {
             toast.fire({ icon: 'error', title: 'Failed to reboot OLT' });
        }
    }
};

const getActions = (item) => [
   { 
        label: 'admin', 
        icon: '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',
        onClick: () => syncUser(item) // Placeholder action for "admin" / View Users
   },
  { 
        label: 'Edit', 
        icon: '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>',
        onClick: () => openModal(item) 
  },
  {
        label: 'Reboot',
        icon: '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>',
        onClick: () => rebootOlt(item),
        danger: true
  },
  { 
        label: 'Delete', 
        icon: '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>',
        onClick: () => deleteOlt(item), 
        danger: true 
  }
];

const exportOlts = () => {
  // Use DataTable's built-in export if available or implement local export
  console.log('Exporting OLTs...');
};

onMounted(() => {
  fetchOlts();
});
</script>
