<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100 font-sans">
        <div class="w-full max-w-md bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Forgot Password</h2>
                
                <form @submit.prevent="handleSubmit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Mobile <span class="text-red-500">*</span>
                        </label>
                        <input 
                            v-model="mobile" 
                            type="text" 
                            class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition"
                            placeholder="Enter your mobile"
                            required
                        >
                    </div>

                    <div v-if="message" class="text-green-600 text-xs text-center">
                        {{ message }}
                    </div>
                    <div v-if="error" class="text-red-500 text-xs text-center">
                        {{ error }}
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex flex-col space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 italic">Required fields are marked with *</span>
                            <div class="flex space-x-2">
                                <button 
                                    type="button" 
                                    @click="resetForm"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 transition"
                                >
                                    Reset
                                </button>
                                <button 
                                    type="submit" 
                                    :disabled="loading"
                                    class="px-6 py-2 text-sm font-semibold text-white bg-green-500 rounded hover:bg-green-600 transition disabled:opacity-50"
                                >
                                    <span v-if="loading">Submitting...</span>
                                    <span v-else>Submit</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="mt-8 text-center">
                    <router-link to="/login" class="text-sm text-primary hover:underline">Back to Login</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const mobile = ref('');
const loading = ref(false);
const message = ref('');
const error = ref('');

const resetForm = () => {
    mobile.value = '';
    message.value = '';
    error.value = '';
};

const handleSubmit = async () => {
    loading.value = true;
    error.value = '';
    message.value = '';
    
    try {
        // Logic will be implemented in AuthController
        const response = await axios.post('/forgot-password', { mobile: mobile.value });
        message.value = response.data.message;
    } catch (err) {
        error.value = err.response?.data?.message || 'Something went wrong. Please try again.';
    } finally {
        loading.value = false;
    }
};
</script>
