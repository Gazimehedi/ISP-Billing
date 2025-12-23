<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100 font-sans p-4">
        <div class="w-full max-w-4xl bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="p-6">
                <!-- Header with Login Link -->
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h2 class="text-2xl font-bold text-gray-800">Sign Up</h2>
                    <router-link to="/login" class="text-primary text-sm font-medium hover:underline">Already have an account?</router-link>
                </div>
                
                <form @submit.prevent="handleRegister" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input v-model="form.name" type="text" class="form-input" placeholder="Enter your name" required>
                        </div>
                        <!-- Username -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input v-model="form.username" type="text" class="form-input" placeholder="Enter your username" required>
                        </div>
                        <!-- Mobile -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Mobile <span class="text-red-500">*</span>
                            </label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    🇧🇩
                                </span>
                                <input v-model="form.mobile" type="text" class="form-input rounded-l-none" placeholder="Enter your mobile" required>
                            </div>
                        </div>

                        <!-- Company Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Company Name <span class="text-red-500">*</span>
                            </label>
                            <input v-model="form.company_name" type="text" class="form-input" placeholder="Enter your Company Name" required>
                        </div>
                        <!-- Division -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Division <span class="text-red-500">*</span>
                            </label>
                            <select v-model="form.division" class="form-select" required>
                                <option value="">Enter your Division</option>
                                <option value="Dhaka">Dhaka</option>
                                <option value="Chittagong">Chittagong</option>
                                <!-- More options from demo or database eventually -->
                            </select>
                        </div>
                        <!-- District -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                District <span class="text-red-500">*</span>
                            </label>
                            <select v-model="form.district" class="form-select" required>
                                <option value="">Enter your District</option>
                                <option v-if="form.division === 'Dhaka'" value="Dhaka">Dhaka</option>
                                <option v-if="form.division === 'Dhaka'" value="Gazipur">Gazipur</option>
                            </select>
                        </div>

                        <!-- Thana -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Thana <span class="text-red-500">*</span>
                            </label>
                            <select v-model="form.thana" class="form-select" required>
                                <option value="">Enter your Thana</option>
                            </select>
                        </div>
                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Address
                            </label>
                            <input v-model="form.address" type="text" class="form-input" placeholder="Enter your Address">
                        </div>
                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input v-model="form.password" type="password" class="form-input" placeholder="Enter your Password" required>
                        </div>
                    </div>

                    <!-- Checkboxes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center space-x-2">
                            <input v-model="form.default_billing" type="checkbox" class="rounded text-primary focus:ring-primary h-4 w-4">
                            <label class="text-sm text-gray-700">Default Billing Cycle <span class="text-red-500">*</span></label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input v-model="form.has_mikrotik" type="checkbox" class="rounded text-primary focus:ring-primary h-4 w-4">
                            <label class="text-sm text-gray-700">Has Route/Mikrotik <span class="text-red-500">*</span></label>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="pt-6 border-t border-gray-100 flex flex-col space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-red-500 italic">Required fields are marked with *</span>
                            <div class="flex space-x-2">
                                <button type="button" @click="resetForm" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 transition">
                                    Reset
                                </button>
                                <button type="submit" :disabled="loading" class="px-6 py-2 text-sm font-semibold text-white bg-green-500 rounded hover:bg-green-600 transition disabled:opacity-50">
                                    <span v-if="loading">Submitting...</span>
                                    <span v-else>Submit</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div v-if="error" class="mt-4 text-red-500 text-sm text-center">
                    {{ error }}
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.form-input {
    @apply w-full px-4 py-2 rounded border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition;
}
.form-select {
    @apply w-full px-4 py-2 rounded border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition bg-white;
}
</style>

<script setup>
import { ref, reactive } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const loading = ref(false);
const error = ref('');

const form = reactive({
    name: '',
    username: '',
    mobile: '',
    company_name: '',
    division: '',
    district: '',
    thana: '',
    address: '',
    password: '',
    default_billing: true,
    has_mikrotik: true
});

const resetForm = () => {
    Object.keys(form).forEach(key => {
        if (typeof form[key] === 'boolean') {
            form[key] = true;
        } else {
            form[key] = '';
        }
    });
    error.value = '';
};

const handleRegister = async () => {
    loading.value = true;
    error.value = '';
    
    try {
        await axios.post('/register', form);
        alert('Registration Successful! Please login.');
        router.push('/login');
    } catch (err) {
        error.value = err.response?.data?.message || 'Registration failed. Please check your data.';
    } finally {
        loading.value = false;
    }
};
</script>
