<template>
  <div class="px-2 sm:px-6 py-4 space-y-6 max-w-[1600px] mx-auto min-h-screen bg-[#f8fafc]">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-3">
           <div class="p-2 bg-[#00bcd4]/10 rounded-xl">
             <svg class="h-6 w-6 text-[#00bcd4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
             </svg>
           </div>
           Message Templates
        </h1>
        <p class="text-[10px] text-slate-400 font-bold ml-[52px] -mt-1 uppercase tracking-[0.2em]">Automated Client Communication</p>
      </div>

      <!-- System Status -->
      <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
          <div class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#00bcd4] opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-[#00bcd4]"></span>
          </div>
          <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Gateway Connected</span>
      </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white p-1.5 rounded-xl shadow-sm border border-gray-100 flex overflow-x-auto no-scrollbar scroll-smooth">
      <button 
        v-for="tab in tabs" 
        :key="tab.id"
        @click="activeTab = tab.id"
        :class="[
          'px-5 py-2.5 rounded-lg text-sm font-bold transition-all duration-300 min-w-fit flex items-center gap-2',
          activeTab === tab.id 
            ? 'bg-[#00bcd4] text-white shadow-md shadow-[#00bcd4]/20 scale-[1.02]' 
            : 'bg-transparent text-gray-500 hover:bg-gray-50 hover:text-gray-700'
        ]"
      >
        <component :is="tab.icon" class="h-4 w-4" />
        {{ tab.label }}
      </button>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">
      <!-- Left Column: Form -->
      <div class="xl:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <!-- Form Header -->
          <div class="bg-slate-900 px-6 py-5 flex items-center justify-between border-b border-slate-800">
            <div class="flex items-center gap-4">
              <div class="bg-[#00bcd4]/20 p-2.5 rounded-xl border border-[#00bcd4]/30">
                <svg class="h-5 w-5 text-[#00bcd4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
              </div>
              <div>
                <h3 class="text-xs font-black text-white uppercase tracking-[0.15em]">{{ activeTab.replace('_', ' ') }}</h3>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Edit Configuration</p>
              </div>
            </div>

            <div class="flex items-center bg-slate-800/50 p-1 rounded-xl border border-slate-700">
                <button 
                  @click="form.status = false"
                  :class="['px-5 py-1.5 text-[10px] font-black rounded-lg transition-all', !form.status ? 'bg-red-500 text-white shadow-lg shadow-red-500/20' : 'text-slate-500 hover:text-slate-300']"
                >OFF</button>
                <button 
                  @click="form.status = true"
                  :class="['px-5 py-1.5 text-[10px] font-black rounded-lg transition-all', form.status ? 'bg-[#00bcd4] text-white shadow-lg shadow-[#00bcd4]/20' : 'text-slate-500 hover:text-slate-300']"
                >ON</button>
            </div>
          </div>

          <!-- Form Body -->
          <div v-if="loading" class="flex justify-center items-center py-20">
            <div class="flex flex-col items-center gap-4">
              <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-[#00bcd4]"></div>
              <span class="text-xs font-bold text-gray-400 uppercase tracking-widest animate-pulse">Syncing Template...</span>
            </div>
          </div>

          <div v-else class="p-6 sm:p-8 space-y-8">
            <!-- Row 1: Title & Type -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div class="space-y-2">
                <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Template Title</label>
                <div class="relative group">
                  <input 
                    v-model="form.title"
                    type="text" 
                    class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-100 rounded-xl focus:bg-white focus:border-[#00bcd4] focus:ring-4 focus:ring-[#00bcd4]/5 outline-none transition-all font-medium text-gray-700 font-bangla"
                    placeholder="e.g., পেমেন্ট নিশ্চিতকরণ"
                  >
                  <div class="absolute inset-y-0 right-4 flex items-center text-gray-300 group-focus-within:text-[#00bcd4] transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                  </div>
                </div>
              </div>

              <div class="space-y-3">
                <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Route Specification</label>
                <div class="flex flex-wrap gap-4">
                  <label v-for="type in messageTypes" :key="type.value" class="relative flex items-center gap-2 cursor-pointer group">
                    <input type="radio" :value="type.value" v-model="form.message_type" class="hidden peer">
                    <div class="w-5 h-5 border-2 border-gray-200 rounded-lg flex items-center justify-center peer-checked:border-[#00bcd4] peer-checked:bg-[#00bcd4] transition-all">
                      <svg class="h-3 w-3 text-white scale-0 peer-checked:scale-100 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-500 peer-checked:text-gray-900 group-hover:text-gray-700 transition-colors">{{ type.label }}</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Dynamic Tags Section -->
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                  <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                  Available Placeholder Tags
                </label>
                <span class="text-[10px] font-bold text-[#00bcd4] bg-[#00bcd4]/10 px-2 py-0.5 rounded-full animate-pulse">Click to Insert</span>
              </div>
              <div class="flex flex-wrap gap-2.5 p-4 bg-gray-50/50 rounded-2xl border border-gray-100">
                <button 
                  v-for="tag in filteredTags" 
                  :key="tag"
                  @click="insertTag(tag)"
                  class="px-3 py-2 bg-white hover:bg-[#00bcd4] hover:text-white text-gray-600 text-[10px] font-black uppercase tracking-tight rounded-lg transition-all border border-gray-100 shadow-sm hover:shadow-md hover:scale-105 active:scale-95 flex items-center gap-1.5"
                >
                  <span class="w-1.5 h-1.5 rounded-full bg-[#00bcd4] group-hover:bg-white"></span>
                  {{ tag.replace('_', ' ') }}
                </button>
              </div>
            </div>

            <!-- Message Editor -->
            <div class="space-y-4">
              <div class="flex items-center justify-between text-[11px] font-black uppercase tracking-widest ml-1">
                <div class="flex items-center gap-3">
                  <span class="text-gray-400 font-bold tracking-widest">Compose Message (Bangla Support)</span>
                  <button 
                    @click="loadDemo"
                    class="bg-[#00bcd4]/10 text-[#00bcd4] px-3 py-1 rounded-lg hover:bg-[#00bcd4] hover:text-white transition-all text-[10px] border border-[#00bcd4]/20 font-black uppercase tracking-tighter"
                  >
                    Load Bangla Example
                  </button>
                </div>
                <span :class="[
                  'px-3 py-1 rounded-lg transition-colors font-black text-[10px]',
                  charCount > smsLimit ? 'bg-orange-100 text-orange-600 shadow-sm shadow-orange-100' : 'bg-[#00bcd4]/10 text-[#00bcd4] shadow-sm shadow-[#00bcd4]/10'
                ]">
                  {{ charCount }} / {{ smsParts }} SMS ({{ hasUnicode ? 'Unicode' : 'GSM-7' }})
                </span>
              </div>
              <div class="relative group">
                <textarea 
                  v-model="form.content"
                  ref="contentArea"
                  rows="10"
                  class="w-full px-6 py-5 bg-gray-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-[#00bcd4] focus:ring-4 focus:ring-[#00bcd4]/5 outline-none transition-all font-medium text-slate-700 leading-relaxed shadow-inner font-bangla text-lg"
                  placeholder="আপনার বার্তা এখানে লিখুন..."
                ></textarea>
                
                <!-- Bottom Toolbar for Textarea -->
                <div class="absolute bottom-4 right-6 flex items-center gap-4">
                   <div class="flex items-center gap-2">
                      <div class="w-40 h-2 bg-slate-200 rounded-full overflow-hidden">
                        <div 
                          class="h-full bg-[#00bcd4] transition-all duration-500 shadow-[0_0_10px_#00bcd4]"
                          :style="{ width: ((charCount % smsLimit) / smsLimit) * 100 + '%' }"
                        ></div>
                      </div>
                      <span class="text-[9px] font-black text-slate-400">{{ remainingChars }} left</span>
                   </div>
                </div>
              </div>
            </div>

            <!-- Save Action -->
            <div class="flex justify-end pt-6 border-t border-slate-50">
              <button 
                @click="saveTemplate"
                :disabled="saving"
                class="group px-14 py-4 bg-slate-900 text-white font-black uppercase tracking-[0.2em] text-[10px] rounded-[20px] shadow-2xl shadow-slate-900/30 hover:bg-slate-800 hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-4 disabled:opacity-50"
              >
                <div v-if="saving" class="animate-spin h-4 w-4 border-2 border-[#00bcd4] border-t-transparent rounded-full"></div>
                <svg v-else class="h-4 w-4 text-[#00bcd4] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ saving ? 'Syncing...' : 'Update Template' }}</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Instructions Card -->
        <div class="bg-gradient-to-br from-[#0f3443] to-[#00bcd4] p-[1px] rounded-2xl shadow-xl shadow-cyan-500/10">
          <div class="bg-white rounded-[15px] p-6 flex gap-5">
              <div class="w-14 h-14 bg-[#00bcd4]/10 rounded-2xl flex items-center justify-center shrink-0">
                <svg class="h-7 w-7 text-[#00bcd4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="space-y-2">
                <h4 class="text-sm font-black text-slate-800 uppercase tracking-tight">ডায়নামিক প্লেসহোল্ডার ব্যবহারের নিয়ম</h4>
                <p class="text-[13px] text-slate-500 leading-relaxed font-bangla font-medium">
                  আপনার মেসেজে সরাসরি ডায়নামিক ট্যাগগুলো যুক্ত করুন। উদাহরণস্বরূপ: <code class="bg-[#00bcd4]/10 px-2 py-0.5 rounded text-[#00bcd4] font-bold">{CLIENT_NAME}</code> ব্যবহার করলে মেসেজ পাঠানোর সময় সেখানে ক্লায়েন্টের নাম চলে আসবে। পেমেন্ট লিঙ্কের জন্য <code class="bg-[#00bcd4]/10 px-2 py-0.5 rounded text-[#00bcd4] font-bold">{PAYMENT_LINK}</code> ব্যবহার করা বাধ্যতামূলক।
                </p>
              </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Preview -->
      <div class="space-y-6 sticky top-6">
        <div class="bg-white rounded-3xl shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
          <div class="bg-slate-50 border-b border-slate-100 px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <svg class="h-4 w-4 text-[#00bcd4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
              <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none">Smartphone Preview</h3>
            </div>
            <div class="w-2 h-2 rounded-full bg-green-500"></div>
          </div>
          
          <div class="p-8 pb-12 flex justify-center bg-slate-50/20">
            <!-- Mobile Shell -->
            <div class="relative w-[280px] h-[580px] bg-slate-900 rounded-[3.5rem] border-[8px] border-slate-800 shadow-[0_50px_100px_-20px_rgba(15,23,42,0.3)] p-3">
              <!-- Camera/Sensors -->
              <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-7 bg-slate-800 rounded-b-3xl z-10 flex items-center justify-center gap-3">
                  <div class="w-2 h-2 rounded-full bg-slate-700"></div>
                  <div class="w-10 h-1 rounded-full bg-slate-700"></div>
              </div>
              
              <!-- Screen UI -->
              <div class="w-full h-full bg-[#f8fafc] rounded-[2.8rem] overflow-hidden flex flex-col shadow-inner">
                <!-- Mobile Status Bar -->
                <div class="px-7 py-3 flex justify-between items-center bg-white/40 backdrop-blur-md">
                  <span class="text-[11px] font-black text-slate-700">{{ currentTime }}</span>
                  <div class="flex gap-1.5 items-center">
                    <svg class="w-3.5 h-3.5 text-slate-700" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" /></svg>
                    <div class="w-5 h-2.5 border border-slate-400 rounded-[2px] p-[1px]"><div class="h-full bg-slate-700 w-3/4 rounded-[1px]"></div></div>
                  </div>
                </div>

                <!-- Chat Header -->
                <div class="px-5 py-4 flex items-center gap-4 bg-white/90 backdrop-blur-md border-b border-slate-200/50">
                   <div class="w-10 h-10 bg-gradient-to-br from-[#00bcd4] to-cyan-600 rounded-full flex items-center justify-center font-black text-white text-[10px] shadow-lg shadow-cyan-500/20">ISP</div>
                   <div class="flex-1">
                      <h4 class="text-[12px] font-black text-slate-800 leading-tight">MH-ISP Billing</h4>
                      <p class="text-[9px] text-[#00bcd4] font-black uppercase tracking-tighter">Official Source</p>
                   </div>
                </div>

                <!-- Scrollable Messages Area -->
                <div class="flex-1 p-5 flex flex-col justify-end space-y-4">
                   <div class="self-start max-w-[95%] bg-white rounded-3xl rounded-bl-none p-4 shadow-sm border border-slate-100 relative group animate-fade-in-up">
                      <div class="bg-[#00bcd4]/5 rounded-xl p-0.5 mb-2 -mx-1 -mt-1 hidden group-hover:block">
                        <span class="text-[8px] font-black text-[#00bcd4] uppercase px-2">Bangla SMS Preview</span>
                      </div>
                      <p class="text-[12px] text-slate-700 leading-[1.6] whitespace-pre-wrap font-bangla font-medium">
                        {{ processedPreview || 'স্বাগতম! আপনার মেসেজের প্রিভিউ দেখতে এখানে টাইপ করুন অথবা সেম্পল মেসেজ লোড করুন।' }}
                      </p>
                      <div class="flex justify-between items-center mt-3 pt-2 border-t border-slate-50">
                        <span class="text-[9px] text-slate-300 font-bold tracking-widest">{{ currentTime }}</span>
                        <svg class="w-3 h-3 text-[#00bcd4]" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" /></svg>
                      </div>
                   </div>
                </div>

                <!-- Bottom Bar -->
                <div class="p-4 bg-white border-t border-slate-100 flex items-center gap-3">
                   <div class="h-10 grow bg-slate-50 rounded-2xl border border-slate-100 flex items-center px-4 text-slate-300 text-[11px] font-medium">
                      মেসেজ লিখুন...
                   </div>
                   <div class="w-10 h-10 bg-[#00bcd4] rounded-full flex items-center justify-center text-white shadow-lg shadow-cyan-500/30">
                      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                   </div>
                </div>
              </div>

              <!-- iOS Home Indicator -->
              <div class="absolute bottom-1.5 left-1/2 -translate-x-1/2 w-28 h-1.5 bg-slate-200/20 rounded-full"></div>
            </div>
          </div>
        </div>

        <!-- Analytics Card -->
        <div class="bg-slate-900 rounded-3xl p-6 shadow-2xl shadow-slate-900/40 relative overflow-hidden">
           <div class="absolute top-0 right-0 w-32 h-32 bg-[#00bcd4]/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
           <div class="relative z-10 space-y-5">
             <div class="flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-[#00bcd4] animate-pulse"></div>
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Live Insights</h4>
             </div>
             <div class="grid grid-cols-2 gap-5">
                <div class="bg-slate-800/50 p-4 rounded-2xl border border-slate-700/50">
                   <p class="text-[9px] font-black text-slate-500 uppercase leading-none mb-2 tracking-widest">Average Cost</p>
                   <p class="text-lg font-black text-white">৳0.50 <span class="text-[10px] text-slate-500 font-bold">/ SMS</span></p>
                </div>
                <div class="bg-slate-800/50 p-4 rounded-2xl border border-slate-700/50">
                   <p class="text-[9px] font-black text-slate-500 uppercase leading-none mb-2 tracking-widest">Efficiency</p>
                   <p class="text-lg font-black text-green-400">99.9%</p>
                </div>
             </div>
           </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

// Tab Icons
import { 
  CreditCardIcon, 
  UserPlusIcon, 
  BellIcon, 
  CalendarIcon, 
  ClockIcon, 
  BanknotesIcon, 
  ExclamationCircleIcon 
} from '@heroicons/vue/24/outline';

const notify = (msg, type = 'success') => {
  Swal.fire({
    icon: type,
    title: msg,
    toast: true,
    position: 'top-end',
    timer: 3000,
    timerProgressBar: true,
    showConfirmButton: false,
    background: '#ffffff',
    iconColor: type === 'success' ? '#00bcd4' : '#f44336'
  });
};

const defaultTemplates = {
  'bill_confirmation': {
    title: 'পেমেন্ট নিশ্চিতকরণ মেসেজ',
    content: 'প্রিয় {CLIENT_NAME}, আপনার {BILL_MONTH} মাসের বিল {BILL_AMOUNT} টাকা সফলভাবে জমা হয়েছে। আপনার বর্তমান বকেয়া {CLIENT_DUE} টাকা। MH-ISP এর সাথে থাকার জন্য ধন্যবাদ।',
    tags: ['CLIENT_NAME', 'BILL_MONTH', 'BILL_AMOUNT', 'CLIENT_DUE', 'PAYMENT_LINK']
  },
  'new_client': {
    title: 'স্বাগতম মেসেজ',
    content: 'MH-ISP-তে আপনাকে স্বাগতম! আপনার ইউজার আইডি: {USERNAME}, পাসওয়ার্ড: {PASSWORD}। যেকোনো সহায়তার জন্য আমাদের হেল্পলাইনে কল করুন। শুভ ইন্টারনেট ব্রাউজিং!',
    tags: ['CLIENT_NAME', 'USERNAME', 'PASSWORD', 'CLIENT_ID']
  },
  'alert': {
    title: 'জরুরী ঘোষণা',
    content: 'প্রিয় {CLIENT_NAME}, আপনার অবগতির জন্য জানানো যাচ্ছে যে {DATE} তারিখ {TIME} থেকে নেটওয়ার্ক রক্ষণাবেক্ষণের কাজ চলবে। আপনার ইন্টারনেট সেবায় বিঘ্ন ঘটতে পারে। সাময়িক অসুবিধার জন্য আমরা আন্তরিকভাবে দুঃখিত।',
    tags: ['CLIENT_NAME', 'DATE', 'TIME']
  },
  'calendar_alert': {
    title: 'মাসিক বিল অ্যালার্ট',
    content: 'প্রিয় {CLIENT_NAME}, আপনার {BILL_MONTH} মাসের বিল {BILL_AMOUNT} টাকা। বিচ্ছিন্নতা এড়াতে {EXPIRE_DATE} তারিখের মধ্যে বিল পরিশোধ করুন। অনলাইন পেমেন্ট লিঙ্ক: {PAYMENT_LINK}',
    tags: ['CLIENT_NAME', 'BILL_MONTH', 'BILL_AMOUNT', 'EXPIRE_DATE', 'PAYMENT_LINK']
  },
  'expire_client': {
    title: 'মেয়াদ শেষ হওয়ার অ্যালার্ট',
    content: 'প্রিয় {CLIENT_NAME}, আপনার অ্যাকাউন্টের মেয়াদ {EXPIRE_DATE} তারিখে শেষ হবে। নিরবচ্ছিন্ন সেবা পেতে অনুগ্রহ করে আপনার প্যাকেজটি রিনিউ করুন। পেমেন্ট লিঙ্ক: {PAYMENT_LINK}',
    tags: ['CLIENT_NAME', 'EXPIRE_DATE', 'PAYMENT_LINK']
  },
  'employee_salary': {
    title: 'বেতন পরিশোধের মেসেজ',
    content: 'প্রিয় সহকর্মী, আপনার {BILL_MONTH} মাসের বেতন পরিশোধ করা হয়েছে। পরিশোধিত পরিমাণ: {BILL_AMOUNT} টাকা। শুভকামনা, MH-ISP।',
    tags: ['EMPLOYEE_NAME', 'BILL_MONTH', 'BILL_AMOUNT']
  },
  'due_client': {
    title: 'বকেয়া বিলের তাগাদা',
    content: 'প্রিয় {CLIENT_NAME}, আপনার {CLIENT_DUE} টাকা বিল বকেয়া রয়েছে। নিরবচ্ছিন্ন ইন্টারনেট সেবা অব্যাহত রাখতে আপনার বকেয়া বিলটি দ্রুত পরিশোধ করুন। ধন্যবাদ, MH-ISP।',
    tags: ['CLIENT_NAME', 'CLIENT_DUE', 'PAYMENT_LINK']
  }
};

const tabs = [
  { id: 'bill_confirmation', label: 'Bill Confirmation', icon: CreditCardIcon },
  { id: 'new_client', label: 'New Client', icon: UserPlusIcon },
  { id: 'alert', label: 'General Alert', icon: BellIcon },
  { id: 'calendar_alert', label: 'Monthly Alert', icon: CalendarIcon },
  { id: 'expire_client', label: 'Expiry Alert', icon: ClockIcon },
  { id: 'employee_salary', label: 'Staff Salary', icon: BanknotesIcon },
  { id: 'due_client', label: 'Due Reminder', icon: ExclamationCircleIcon }
];

const filteredTags = computed(() => {
  const defaults = defaultTemplates[activeTab.value];
  if (defaults && defaults.tags) {
    return defaults.tags;
  }
  return ['USERNAME', 'CLIENT_ID', 'CLIENT_NAME', 'BILL_AMOUNT', 'FEE', 'CLIENT_DUE', 'BILL_VAT', 'BILL_MONTH', 'EXPIRE_DATE', 'PAYMENT_LINK'];
});

const messageTypes = [
  { value: 'non-masking', label: 'Non-Masking (94)' },
  { value: 'masking', label: 'Masking (0)' },
  { value: 'fixed-number', label: 'Fixed Number (0)' }
];

const activeTab = ref('bill_confirmation');
const loading = ref(false);
const saving = ref(false);
const contentArea = ref(null);
const currentTime = ref('');

const form = ref({
  type: 'bill_confirmation',
  title: '',
  content: '',
  status: false,
  message_type: 'non-masking'
});

const charCount = computed(() => form.value.content?.length || 0);

const hasUnicode = computed(() => {
    return /[^\u0000-\u007F]/.test(form.value.content);
});

const smsLimit = computed(() => (hasUnicode.value ? 70 : 160));
const smsParts = computed(() => Math.ceil(charCount.value / smsLimit.value) || 1);
const remainingChars = computed(() => (smsLimit.value * smsParts.value) - charCount.value);

// Simulate tag replacement for preview
const processedPreview = computed(() => {
  let content = form.value.content;
  if (!content) return '';
  
  const demoData = {
    'USERNAME': 'gazimehedi',
    'CLIENT_ID': 'M-1492',
    'CLIENT_NAME': 'গাজী মেহেদী',
    'BILL_AMOUNT': '১,২০০',
    'FEE': '০',
    'CLIENT_DUE': '৩৫০',
    'BILL_VAT': '১৫',
    'BILL_MONTH': 'ফেব্রুয়ারি',
    'EXPIRE_DATE': '০৫ মার্চ, ২০২৬',
    'PAYMENT_LINK': 'https://pay.example.com/M-1492'
  };

  filteredTags.value.forEach(tag => {
    const regex = new RegExp(`{${tag}}`, 'g');
    content = content.replace(regex, `<span class="text-[#00bcd4] font-black">${demoData[tag] || tag}</span>`);
  });

  return content;
});

const updateClock = () => {
    const now = new Date();
    currentTime.value = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
};

const fetchTemplate = async (type) => {
  loading.value = true;
  try {
    const res = await axios.get(`/api/config/sms-templates/${type}`);
    if (res.data && res.data.type) {
      form.value = {
        type: res.data.type,
        title: res.data.title || '',
        content: res.data.content || '',
        status: !!res.data.status,
        message_type: res.data.message_type || 'non-masking'
      };
      
      // Force load demo if record exists but content is empty
      if (!form.value.content || form.value.content.trim() === '') {
          const defaults = defaultTemplates[type];
          form.value.content = defaults.content;
          form.value.title = defaults.title;
      }
    } else {
        const defaults = defaultTemplates[type] || { title: '', content: '' };
        form.value = {
            type: type,
            title: defaults.title,
            content: defaults.content,
            status: false,
            message_type: 'non-masking'
        };
    }
  } catch (err) {
    console.error("Failed to fetch template", err);
  } finally {
    loading.value = false;
  }
};

const saveTemplate = async () => {
  if (!form.value.content) {
    return notify('মেসেজ কন্টেন্ট খালি রাখা যাবে না', 'error');
  }
  
  saving.value = true;
  try {
    await axios.post('/api/config/sms-templates', form.value);
    notify('টেমপ্লেট সফলভাবে আপডেট করা হয়েছে', 'success');
  } catch (err) {
    console.error("Failed to save template", err);
    notify('সংরক্ষণ করতে ব্যর্থ হয়েছে', 'error');
  } finally {
    saving.value = false;
  }
};

const loadDemo = () => {
  const defaults = defaultTemplates[activeTab.value] || { title: '', content: '' };
  form.value.title = defaults.title;
  form.value.content = defaults.content;
  notify('নমুনা মেসেজ লোড করা হয়েছে', 'success');
};

const insertTag = (tag) => {
  const textarea = contentArea.value;
  if (!textarea) return;

  const start = textarea.selectionStart;
  const end = textarea.selectionEnd;
  const tagText = `{${tag}}`;
  
  form.value.content = form.value.content.substring(0, start) + tagText + form.value.content.substring(end);
  
  setTimeout(() => {
    textarea.focus();
    const newPos = start + tagText.length;
    textarea.setSelectionRange(newPos, newPos);
  }, 0);
};

onMounted(() => {
  updateClock();
  fetchTemplate(activeTab.value);
  setInterval(updateClock, 10000);
});

watch(activeTab, (newTab) => {
  fetchTemplate(newTab);
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap');

.font-bangla {
  font-family: 'Hind Siliguri', sans-serif !important;
}

.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-up {
  animation: fade-in-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* Custom scrollbar for textarea */
textarea::-webkit-scrollbar {
  width: 6px;
}
textarea::-webkit-scrollbar-track {
  background: transparent;
}
textarea::-webkit-scrollbar-thumb {
  background: #e2e8f0;
  border-radius: 10px;
}
textarea::-webkit-scrollbar-thumb:hover {
  background: #cbd5e1;
}

/* Glassmorphism utility */
.backdrop-blur-md {
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
}
</style>
