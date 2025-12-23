<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100 font-sans">
        <div class="w-full max-w-md bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="p-6">
                <!-- Header with Forgot Link -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Log in</h2>
                    <router-link to="/register" class="text-primary text-sm font-medium hover:underline">I have no account?</router-link>
                </div>

                <div class="flex justify-center mb-6">
                    <div class="h-10 w-10 bg-primary rounded-full flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                
                <form @submit.prevent="handleLogin" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Mobile <span class="text-red-500">*</span>
                        </label>
                        <input 
                            v-model="form.mobile" 
                            type="text" 
                            class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition"
                            placeholder="Enter your mobile"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                v-model="form.password" 
                                :type="showPassword ? 'text' : 'password'" 
                                class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition"
                                placeholder="Enter your Password"
                                required
                            >
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
                            >
                                <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.399 8.049 7.21 5 12 5c4.792 0 8.601 3.049 9.964 6.678.045.122.045.26 0 .382C20.601 15.951 16.79 19 12 19c-4.792 0-8.601-3.049-9.964-6.678Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-between items-center text-sm">
                        <router-link to="/forgot-password" class="text-primary hover:underline">Forgot password?</router-link>
                    </div>

                    <div v-if="error" class="text-red-500 text-xs text-center">
                        {{ error }}
                    </div>

                    <div class="flex flex-col space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 italic">Required fields are marked with *</span>
                            <div class="flex space-x-2">
                                <button 
                                    type="button" 
                                    @click="resetForm"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200 transition"
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

                <div class="mt-8 text-center text-xs text-gray-400">
                    <p>&copy; 2025 Silicon ISP. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const loading = ref(false);
const error = ref('');
const showPassword = ref(false);

const form = reactive({
    mobile: '',
    password: ''
});

const resetForm = () => {
    form.mobile = '';
    form.password = '';
    error.value = '';
};

const handleLogin = async () => {
    loading.value = true;
    error.value = '';
    
    try {
        await axios.get('/sanctum/csrf-cookie');
        const response = await axios.post('/login', form);
        localStorage.setItem('user', JSON.stringify(response.data.user));
        localStorage.setItem('is_auth', 'true');
        router.push('/dashboard');
    } catch (err) {
        error.value = err.response?.data?.message || 'Login failed. Please check your credentials.';
    } finally {
        loading.value = false;
    }
};
</script>
