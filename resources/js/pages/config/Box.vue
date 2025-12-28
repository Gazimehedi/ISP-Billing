<template>
  <div class="p-2 h-full flex flex-col">
    <!-- Data Table -->
    <DataTable 
      :columns="columns" 
      :data="boxes" 
      :loading="loading" 
      :pagination="pagination"
      title="List Of Box"
      @update:search="search = $event; fetchBoxes()"
      @page-change="fetchBoxes"
      @refresh="fetchBoxes"
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

      <template #client_count="{ item }">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
          {{ item.client_count || 0 }}
        </span>
      </template>
      <template #zone_name="{ item }">
          <span class="text-gray-600 font-medium">{{ item.zone ? item.zone.name : '-' }}</span>
      </template>
      <template #sub_zone_name="{ item }">
          <span class="text-gray-600 font-medium">{{ item.sub_zone ? item.sub_zone.name : '-' }}</span>
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
      :title="editingBox ? 'Edit Box' : 'Add New Box'" 
      :loading="saving"
      @close="closeModal"
      @submit="saveBox"
      maxWidth="md"
    >
      <div class="space-y-4">
        <!-- Zone Dropdown -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Zone</label>
            <select 
                v-model="form.zone_id" 
                @change="handleZoneChange"
                class="w-full text-sm text-gray-700 border border-gray-200 rounded px-3 py-2 leading-tight focus:outline-none focus:border-[#00bcd4] transition-colors"
            >
                <option value="" disabled>Select a Zone</option>
                <option v-for="zone in zoneOptions" :key="zone.id" :value="zone.id">
                    {{ zone.name }}
                </option>
            </select>
            <p v-if="errors.zone_id" class="mt-1 text-xs text-red-500">{{ errors.zone_id[0] }}</p>
        </div>

        <!-- Sub Zone Dropdown -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Sub Zone</label>
            <select 
                v-model="form.sub_zone_id" 
                :disabled="!form.zone_id"
                class="w-full text-sm text-gray-700 border border-gray-200 rounded px-3 py-2 leading-tight focus:outline-none focus:border-[#00bcd4] disabled:bg-gray-100 disabled:cursor-not-allowed transition-colors"
            >
                <option value="" disabled>Select a Sub Zone</option>
                <option v-for="sz in filteredSubZones" :key="sz.id" :value="sz.id">
                    {{ sz.name }}
                </option>
            </select>
            <p v-if="errors.sub_zone_id" class="mt-1 text-xs text-red-500">{{ errors.sub_zone_id[0] }}</p>
        </div>

        <!-- Name -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Box Name</label>
            <input v-model="form.name" type="text" class="w-full text-sm text-gray-700 border border-gray-200 rounded px-3 py-2 leading-tight focus:outline-none focus:border-[#00bcd4] transition-colors" placeholder="e.g. Box 101">
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

const boxes = ref([]);
const zoneOptions = ref([]); 
const subZoneOptions = ref([]); // All subzones or filtered ones? 
// Strategy: Fetch all zones. For subzones, better to fetch only for selected zone to avoid massive list. 
// OR fetch all if manageable. Let's fetch by zone on change for scalability.
const filteredSubZones = ref([]);

const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const editingBox = ref(null);
const search = ref('');
const pagination = ref({ current_page: 1, total: 0 });
const errors = ref({});

const columns = [
  { key: 'id', label: '#' },
  { key: 'name', label: 'Name', class: 'font-medium text-gray-900' },
  { key: 'zone_name', label: 'Zone' },
  { key: 'sub_zone_name', label: 'Sub Zone' },
  { key: 'description', label: 'Description', class: 'max-w-xs truncate' },
  { key: 'client_count', label: 'Client' }, // Placeholder for now (0), as we don't track clients yet
  { key: 'created_at', label: 'Created At' },
  { key: 'actions', label: 'Actions', class: 'text-right' },
];

const form = ref({
  zone_id: '',
  sub_zone_id: '',
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
        onClick: () => deleteBox(item)
    }
];

const fetchBoxes = async (page = 1) => {
  loading.value = true;
  try {
    const response = await axios.get('/api/config/boxes', {
      params: { page, search: search.value }
    });
    boxes.value = response.data.data.map(b => ({
        ...b,
        zone_name: b.zone ? b.zone.name : '',
        sub_zone_name: b.subZone ? b.subZone.name : '',
        created_at: new Date(b.created_at).toLocaleDateString()
    }));
    pagination.value = response.data;
  } catch (error) {
    console.error('Error fetching boxes:', error);
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

const fetchSubZonesByZone = async (zoneId) => {
    if (!zoneId) {
        filteredSubZones.value = [];
        return;
    }
    try {
        const response = await axios.get(`/api/config/sub-zones?zone_id=${zoneId}&per_page=100`);
        filteredSubZones.value = response.data.data;
    } catch (e) {
         console.error("Error fetching sub zones", e);
    }
}

const handleZoneChange = () => {
    form.value.sub_zone_id = ''; // Reset sub zone
    fetchSubZonesByZone(form.value.zone_id); // Fetch new sub zones
}

const openModal = async (box = null) => {
  errors.value = {};
  await fetchZones(); // Refresh zones
  if (box) {
    editingBox.value = box;
    form.value = { 
        zone_id: box.zone_id,
        sub_zone_id: box.sub_zone_id,
        name: box.name, 
        description: box.description 
    };
    // Fetch subzones for the existing zone so the dropdown is populated
    await fetchSubZonesByZone(box.zone_id);
  } else {
    editingBox.value = null;
    form.value = {
      zone_id: '',
      sub_zone_id: '',
      name: '',
      description: '',
    };
    filteredSubZones.value = [];
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingBox.value = null;
};

const saveBox = async () => {
  saving.value = true;
  errors.value = {};
  try {
    if (editingBox.value) {
      await axios.put(`/api/config/boxes/${editingBox.value.id}`, form.value);
      toast.fire({ icon: 'success', title: 'Box updated successfully' });
    } else {
      await axios.post('/api/config/boxes', form.value);
      toast.fire({ icon: 'success', title: 'Box created successfully' });
    }
    closeModal();
    fetchBoxes(pagination.value.current_page);
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors;
       toast.fire({ icon: 'error', title: 'Validation error' });
    } else {
      console.error('Error saving box:', error);
       toast.fire({ icon: 'error', title: 'Failed to save box' });
    }
  } finally {
    saving.value = false;
  }
};

const deleteBox = async (item) => {
    const result = await confirmAlert('Are you sure?', 'You verify deleting this box!');
    if (result.isConfirmed) {
        try {
            await axios.delete(`/api/config/boxes/${item.id}`);
            toast.fire({ icon: 'success', title: 'Box deleted successfully' });
            fetchBoxes(pagination.value.current_page);
        } catch (error) {
            toast.fire({ icon: 'error', title: 'Failed to delete box' });
            console.error(error);
        }
    }
};

onMounted(() => {
  fetchBoxes();
});
</script>
