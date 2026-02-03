<template>
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 flex items-center justify-center p-4">
        <!-- Loading State -->
        <div v-if="loading" class="text-center">
            <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-indigo-600"></div>
            <p class="mt-4 text-gray-600 font-medium">Loading payment details...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-2xl p-8 text-center border-t-4 border-red-500">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Invalid Client Code</h2>
                <p class="text-gray-600 mb-6">{{ errorMessage }}</p>
                
                <div class="space-y-4">
                    <p class="text-sm text-gray-500">Please enter your client code to try again:</p>
                    <div class="flex gap-2">
                        <input 
                            v-model="manualClientCode" 
                            type="text" 
                            placeholder="e.g. CL-0024"
                            class="flex-1 rounded-lg border-gray-300 border px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm outline-none"
                            @keyup.enter="retryWithCode"
                        >
                        <button 
                            @click="retryWithCode" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium text-sm transition-colors"
                        >
                            Go
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success State -->
        <div v-else-if="paymentStatus === 'success'" class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-2xl p-8 text-center border-t-4 border-green-500">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Payment Successful!</h2>
                <p class="text-gray-600 mb-4">Your payment has been processed successfully.</p>
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-sm text-gray-600">Transaction ID</p>
                    <p class="text-lg font-bold text-indigo-600">{{ transactionId }}</p>
                </div>
                <p class="text-sm text-gray-500">Thank you for your payment!</p>
            </div>
        </div>

        <!-- Failed State -->
        <div v-else-if="paymentStatus === 'failed'" class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-2xl p-8 text-center border-t-4 border-orange-500">
                <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Payment Failed</h2>
                <p class="text-gray-600 mb-6">Unfortunately, your payment could not be processed.</p>
                <button @click="resetPayment" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 font-medium">
                    Try Again
                </button>
            </div>
        </div>

        <!-- Payment Form -->
        <div v-else class="max-w-lg w-full">
            <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-5 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-xl font-bold">ISP Bill Payment</h1>
                            <p class="text-indigo-100 text-xs mt-0.5">Secure payment with bKash</p>
                        </div>
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Client Info - Compact Grid -->
                <div class="p-5 bg-gray-50 border-b border-gray-200">
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs mb-0.5">Client Name</p>
                            <p class="font-semibold text-gray-900">{{ clientData.name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs mb-0.5">Client Code</p>
                            <p class="font-semibold text-indigo-600">{{ clientData.client_code }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs mb-0.5">Package</p>
                            <p class="font-semibold text-gray-900">{{ clientData.package }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs mb-0.5">Mobile</p>
                            <p class="font-semibold text-gray-900">{{ clientData.mobile }}</p>
                        </div>
                    </div>
                </div>

                <!-- Due Amount - Compact Display -->
                <div class="p-5">
                    <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-lg p-4 border border-red-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-xs text-gray-600 mb-1">Amount Due</p>
                                <p class="text-3xl font-bold text-red-600">৳{{ clientData.due_amount || 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- No Dues Message -->
                    <div v-if="clientData.due_amount <= 0" class="mt-3 bg-green-50 border border-green-200 rounded-lg p-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-green-700 font-medium text-sm">No outstanding dues</p>
                            <p class="text-xs text-green-600">Your account is up to date</p>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <button 
                        @click="initiatePayment" 
                        :disabled="processing || !clientData.due_amount || clientData.due_amount <= 0"
                        class="w-full mt-4 bg-gradient-to-r from-pink-500 to-pink-600 text-white py-3.5 rounded-lg font-bold text-base hover:from-pink-600 hover:to-pink-700 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 shadow-lg hover:shadow-xl"
                    >
                        <svg v-if="!processing" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"/>
                        </svg>
                        <div v-if="processing" class="inline-block animate-spin rounded-full h-5 w-5 border-t-2 border-b-2 border-white"></div>
                        <span>{{ processing ? 'Processing...' : 'Pay ৳' + (clientData.due_amount || 0) + ' with bKash' }}</span>
                    </button>
                    
                    <p class="text-xs text-gray-400 text-center mt-2.5 flex items-center justify-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                        </svg>
                        Secure payment powered by bKash
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'PaymentGateway',
    data() {
        return {
            clientCode: '',
            clientData: {},
            loading: true,
            error: false,
            errorMessage: '',
            processing: false,
            paymentStatus: null,
            transactionId: null,
            manualClientCode: '',
        };
    },
    mounted() {
        this.clientCode = this.$route.params.clientCode;
        this.checkPaymentStatus();
        this.fetchClientDetails();
    },
    methods: {
        checkPaymentStatus() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const trxID = urlParams.get('trxID');
            
            if (status === 'success') {
                this.paymentStatus = 'success';
                this.transactionId = trxID;
                this.loading = false;
            } else if (status === 'failed') {
                this.paymentStatus = 'failed';
                this.loading = false;
            }
        },
        async fetchClientDetails() {
            if (this.paymentStatus) return;

            try {
                const response = await axios.get(`/api/payment/${this.clientCode}`);
                
                if (response.data.success) {
                    this.clientData = response.data.data;
                } else {
                    this.error = true;
                    this.errorMessage = response.data.message || 'Client not found';
                }
            } catch (err) {
                this.error = true;
                this.errorMessage = err.response?.data?.message || 'Failed to load client details';
            } finally {
                this.loading = false;
            }
        },
        async initiatePayment() {
            if (!this.clientData.due_amount || this.clientData.due_amount <= 0) {
                alert('No outstanding dues to pay');
                return;
            }

            this.processing = true;

            try {
                const response = await axios.post('/api/payment/initiate', {
                    client_code: this.clientCode,
                    amount: this.clientData.due_amount,
                });

                if (response.data.success) {
                    // Redirect to bKash payment page
                    window.location.href = response.data.bkashURL;
                } else {
                    alert(response.data.message || 'Failed to initiate payment');
                    this.processing = false;
                }
            } catch (err) {
                alert(err.response?.data?.message || 'Failed to initiate payment');
                this.processing = false;
            }
        },
        resetPayment() {
            this.paymentStatus = null;
            this.transactionId = null;
            this.fetchClientDetails();
        },
        retryWithCode() {
            if (!this.manualClientCode) return;
            // Trim and convert to uppercase for consistency
            const code = this.manualClientCode.trim();
            if (code) {
                window.location.href = `/pay/${code}`;
            }
        },
    },
};
</script>
