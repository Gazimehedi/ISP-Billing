<template>
  <div class="p-2 h-full flex flex-col">
    <!-- Data Table -->
    <DataTable 
      :columns="columns" 
      :data="types" 
      :loading="loading" 
      :pagination="pagination"
      title="List Of Client Type"
      @update:search="search = $event; fetchTypes()"
      @page-change="fetchTypes"
      @refresh="fetchTypes"
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
      :title="editingType ? 'Edit Client Type' : 'Add New Client Type'" 
      :loading="saving"
      @close="closeModal"
      @submit="saveType"
      maxWidth="md"
    >
      <div class="space-y-4">
        <!-- Name -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Name</label>
            <input v-model="form.name" type="text" class="w-full text-sm text-gray-700 border border-gray-200 rounded px-3 py-2 leading-tight focus:outline-none focus:border-[#00bcd4] transition-colors" placeholder="e.g. Home User">
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

const types = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const editingType = ref(null);
const search = ref('');
const pagination = ref({ current_page: 1, total: 0 });
const errors = ref({});

const columns = [
  { key: 'id', label: '#' },
  { key: 'name', label: 'Name', class: 'font-medium text-gray-900' },
  { key: 'description', label: 'Description', class: 'max-w-xs truncate' },
  { key: 'client_count', label: 'Client' },
  { key: 'created_at', label: 'Created At' },
  { key: 'actions', label: 'Actions', class: 'text-right' },
];

const form = ref({
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
        onClick: () => deleteType(item)
    }
];

const fetchTypes = async (page = 1) => {
  loading.value = true;
  try {
    const response = await axios.get('/api/config/client-types', {
      params: { page, search: search.value }
    });
    types.value = response.data.data.map(t => ({
        ...t,
        created_at: new Date(t.created_at).toLocaleDateString()
    }));
    pagination.value = response.data;
  } catch (error) {
    console.error('Error fetching client types:', error);
  } finally {
    loading.value = false;
  }
};

const openModal = (type = null) => {
  errors.value = {};
  if (type) {
    editingType.value = type;
    form.value = { 
        name: type.name, 
        description: type.description 
    };
  } else {
    editingType.value = null;
    form.value = {
      name: '',
      description: '',
    };
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingType.value = null;
};

const saveType = async () => {
  saving.value = true;
  errors.value = {};
  try {
    if (editingType.value) {
      await axios.put(`/api/config/client-types/${editingType.value.id}`, form.value);
      toast.fire({ icon: 'success', title: 'Client Type updated successfully' });
    } else {
      await axios.post('/api/config/client-types', form.value);
      toast.fire({ icon: 'success', title: 'Client Type created successfully' });
    }
    closeModal();
    fetchTypes(pagination.value.current_page);
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors;
       toast.fire({ icon: 'error', title: 'Validation error' });
    } else {
      console.error('Error saving client type:', error);
       toast.fire({ icon: 'error', title: 'Failed to save client type' });
    }
  } finally {
    saving.value = false;
  }
};

const deleteType = async (item) => {
    const result = await confirmAlert('Are you sure?', 'You verify deleting this client type!');
    if (result.isConfirmed) {
        try {
            await axios.delete(`/api/config/client-types/${item.id}`);
            toast.fire({ icon: 'success', title: 'Client type deleted successfully' });
            fetchTypes(pagination.value.current_page);
        } catch (error) {
            toast.fire({ icon: 'error', title: 'Failed to delete client type' });
            console.error(error);
        }
    }
};

onMounted(() => {
  fetchTypes();
});
</script>
