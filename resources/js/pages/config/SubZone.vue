<template>
  <div class="p-2 h-full flex flex-col">
    <!-- Data Table -->
    <DataTable 
      :columns="columns" 
      :data="subZones" 
      :loading="loading" 
      :pagination="pagination"
      title="List Of Sub Zone"
      @update:search="search = $event; fetchSubZones()"
      @page-change="fetchSubZones"
      @refresh="fetchSubZones"
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

      <template #boxes_count="{ item }">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
          {{ item.boxes_count || 0 }}
        </span>
      </template>
      <template #zone_name="{ item }">
          <span class="text-gray-600 font-medium">{{ item.zone ? item.zone.name : '-' }}</span>
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
      :title="editingSubZone ? 'Edit Sub Zone' : 'Add New Sub Zone'" 
      :loading="saving"
      @close="closeModal"
      @submit="saveSubZone"
      maxWidth="md"
    >
      <div class="space-y-4">
        <!-- Zone Dropdown -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Zone</label>
            <select v-model="form.zone_id" class="w-full text-sm text-gray-700 border border-gray-200 rounded px-3 py-2 leading-tight focus:outline-none focus:border-[#00bcd4] transition-colors">
                <option value="" disabled>Select a Zone</option>
                <option v-for="zone in zoneOptions" :key="zone.id" :value="zone.id">
                    {{ zone.name }}
                </option>
            </select>
            <p v-if="errors.zone_id" class="mt-1 text-xs text-red-500">{{ errors.zone_id[0] }}</p>
        </div>

        <!-- Name -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Sub Zone Name</label>
            <input v-model="form.name" type="text" class="w-full text-sm text-gray-700 border border-gray-200 rounded px-3 py-2 leading-tight focus:outline-none focus:border-[#00bcd4] transition-colors" placeholder="e.g. Area 1">
             <p v-if="errors.name" class="mt-1 text-xs text-red-500">{{ errors.name[0] }}</p>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Description</label>
            <textarea v-model="form.description" rows="3" class="w-full text-sm text-gray-700 border border-gray-200 rounded px-3 py-2 leading-tight focus:outline-none focus:border-[#00bcd4] transition-colors" placeholder="Optional description"></textarea>
            <p v-if="errors.description" class="mt-1 text-xs text-red-500">{{ errors.description[0] }}</p>
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
import { toast, confirmAlert } from '../../components/config/swal.js';

const subZones = ref([]);
const zoneOptions = ref([]); // For dropdown
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const editingSubZone = ref(null);
const search = ref('');
const pagination = ref({ current_page: 1, total: 0 });
const errors = ref({});

const columns = [
  { key: 'id', label: '#' },
  { key: 'name', label: 'Name', class: 'font-medium text-gray-900' },
  { key: 'boxes_count', label: 'Client' }, // Explained in notes: showing box count for now
  { key: 'zone_name', label: 'Zone' }, 
  { key: 'description', label: 'Description', class: 'max-w-xs truncate' },
  { key: 'created_at', label: 'Created At' },
  { key: 'actions', label: 'Actions', class: 'text-right' },
];

const form = ref({
  zone_id: '',
  name: '',
  description: '',
});

const getActions = (item) => [
    {
        label: 'Edit',
        icon: '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>',
        onClick: () => openModal(item)
    },
    {
        label: 'Delete',
        icon: '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>',
        class: 'text-red-600 hover:bg-red-50',
        onClick: () => deleteSubZone(item)
    }
];

const fetchSubZones = async (page = 1) => {
  loading.value = true;
  try {
    const response = await axios.get('/api/config/sub-zones', {
      params: { page, search: search.value }
    });
    subZones.value = response.data.data.map(z => ({
        ...z,
        zone_name: z.zone ? z.zone.name : '',
        created_at: new Date(z.created_at).toLocaleDateString()
    }));
    pagination.value = response.data;
  } catch (error) {
    console.error('Error fetching sub zones:', error);
  } finally {
    loading.value = false;
  }
};

const fetchZones = async () => {
    try {
        const response = await axios.get('/api/config/zones?per_page=100');
        zoneOptions.value = response.data.data;
    } catch (e) {
        console.error("Error fetching zones", e);
    }
}

const openModal = (subZone = null) => {
  errors.value = {};
  fetchZones();
  if (subZone) {
    editingSubZone.value = subZone;
    form.value = { 
        zone_id: subZone.zone_id,
        name: subZone.name, 
        description: subZone.description 
    };
  } else {
    editingSubZone.value = null;
    form.value = {
      zone_id: '',
      name: '',
      description: '',
    };
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingSubZone.value = null;
};

const saveSubZone = async () => {
  saving.value = true;
  errors.value = {};
  try {
    if (editingSubZone.value) {
      await axios.put(`/api/config/sub-zones/${editingSubZone.value.id}`, form.value);
      toast.fire({ icon: 'success', title: 'Sub Zone updated successfully' });
    } else {
      await axios.post('/api/config/sub-zones', form.value);
      toast.fire({ icon: 'success', title: 'Sub Zone created successfully' });
    }
    closeModal();
    fetchSubZones(pagination.value.current_page);
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors;
       toast.fire({ icon: 'error', title: 'Validation error' });
    } else {
      console.error('Error saving sub zone:', error);
       toast.fire({ icon: 'error', title: 'Failed to save sub zone' });
    }
  } finally {
    saving.value = false;
  }
};

const deleteSubZone = async (item) => {
    const result = await confirmAlert('Are you sure?', 'You verify deleting this sub zone!');
    if (result.isConfirmed) {
        try {
            await axios.delete(`/api/config/sub-zones/${item.id}`);
            toast.fire({ icon: 'success', title: 'Sub Zone deleted successfully' });
            fetchSubZones(pagination.value.current_page);
        } catch (error) {
            toast.fire({ icon: 'error', title: 'Failed to delete sub zone' });
            console.error(error);
        }
    }
};

onMounted(() => {
  fetchSubZones();
});
</script>
