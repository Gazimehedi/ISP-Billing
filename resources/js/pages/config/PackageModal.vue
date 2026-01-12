<template>
  <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden">
      <div class="bg-[#0f4340] px-6 py-3 text-white flex items-center justify-between">
        <span class="font-bold text-sm uppercase">{{ isEdit ? 'Edit Package' : 'Add Package' }}</span>
        <button @click="$emit('close')">
          <svg class="w-5 h-5 text-white/70 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>
      
      <form @submit.prevent="handleSubmit" class="p-6 space-y-4">
        <!-- Package Name -->
        <div>
          <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Package Name *</label>
          <input 
            v-model="form.name" 
            type="text" 
            required
            class="w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-xs py-2 px-3 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
            placeholder="e.g., Home 10 Mbps"
          >
        </div>

        <!-- Speed -->
        <div>
          <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Speed</label>
          <input 
            v-model="form.speed" 
            type="text" 
            class="w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-xs py-2 px-3 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
            placeholder="e.g., 10 Mbps"
          >
        </div>

        <!-- Price -->
        <div>
          <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Price (৳) *</label>
          <input 
            v-model="form.price" 
            type="number" 
            step="0.01"
            min="0"
            required
            class="w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-xs py-2 px-3 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
            placeholder="e.g., 500"
          >
        </div>

        <!-- Description -->
        <div>
          <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Description</label>
          <textarea 
            v-model="form.description" 
            rows="3"
            class="w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-xs py-2 px-3 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
            placeholder="Package details..."
          ></textarea>
        </div>

        <!-- Is Active -->
        <div class="flex items-center gap-2">
          <input 
            v-model="form.is_active" 
            type="checkbox" 
            id="is_active"
            class="rounded border-gray-300 text-emerald-500 focus:ring-emerald-500"
          >
          <label for="is_active" class="text-xs font-medium text-gray-700">Active</label>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
          <button 
            type="button" 
            @click="$emit('close')" 
            class="px-6 py-2 border border-gray-300 rounded-lg text-xs font-bold text-gray-600 hover:bg-white transition-colors uppercase tracking-wide"
          >
            Cancel
          </button>
          <button 
            type="submit" 
            :disabled="saving"
            class="px-6 py-2 bg-[#0f4340] text-white rounded-lg text-xs font-bold hover:bg-[#0a2f2d] transition-colors uppercase tracking-wide shadow-md disabled:opacity-50"
          >
            {{ saving ? 'Saving...' : (isEdit ? 'Update' : 'Create') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({
  package: {
    type: Object,
    default: null
  }
});

const emit = defineEmits(['close', 'saved']);

const form = ref({
  name: '',
  speed: '',
  price: '',
  description: '',
  is_active: true
});

const saving = ref(false);

const isEdit = computed(() => !!props.package);

onMounted(() => {
  if (props.package) {
    form.value = {
      name: props.package.name || '',
      speed: props.package.speed || '',
      price: props.package.price || '',
      description: props.package.description || '',
      is_active: props.package.is_active ?? true
    };
  }
});

const handleSubmit = async () => {
  saving.value = true;
  try {
    if (isEdit.value) {
      await axios.put(`/api/config/packages/${props.package.id}`, form.value);
      Swal.fire({
        title: 'Updated!',
        text: 'Package has been updated successfully.',
        icon: 'success',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
      });
    } else {
      await axios.post('/api/config/packages', form.value);
      Swal.fire({
        title: 'Created!',
        text: 'Package has been created successfully.',
        icon: 'success',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
      });
    }
    emit('saved');
  } catch (error) {
    console.error('Error saving package:', error);
    Swal.fire('Error', 'Failed to save package. Please try again.', 'error');
  } finally {
    saving.value = false;
  }
};
</script>
