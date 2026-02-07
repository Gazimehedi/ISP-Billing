<template>
    <div class="grid grid-cols-12 gap-6 p-6">
        <!-- Sidebar Navigation Card (Profile Summary) -->
        <div class="col-span-12 lg:col-span-3">
             <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-100 overflow-hidden sticky top-6">
                 <!-- Profile Header -->
                 <div class="p-6 pb-5 text-center border-b border-slate-50 bg-gradient-to-b from-slate-50/30 to-white">
                     <div class="relative inline-block mb-4">
                         <!-- Large Initials Avatar -->
                         <div class="w-24 h-24 rounded-3xl p-1 mx-auto relative overflow-hidden ring-4 ring-white shadow-lg group">
                             <img v-if="user?.profile_pic_url" :src="user.profile_pic_url" class="w-full h-full rounded-2xl object-cover shadow-inner" />
                             <div v-else class="w-full h-full rounded-2xl bg-slate-900 flex items-center justify-center text-white font-black text-xl tracking-tighter">
                                  {{ userInitials }}
                             </div>
                          </div>
                     </div>
                     
                     <h2 class="text-base font-black text-slate-800 tracking-tight leading-tight uppercase truncate">{{ user?.name || 'User Name' }}</h2>
                     <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1.5">{{ user?.role || 'Administrator' }}</p>
                 </div>

                 <!-- Detailed Stats / Info -->
                 <div class="p-5 space-y-3">
                     <div class="flex items-center justify-between px-3.5 py-2.5 bg-slate-50/50 rounded-xl border border-slate-100">
                         <div class="flex flex-col">
                             <span class="text-[7px] font-black text-slate-400 uppercase tracking-widest leading-none">Status</span>
                             <span class="text-[10px] font-black text-green-600 uppercase mt-0.5">Active</span>
                         </div>
                         <div class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.4)]"></div>
                     </div>

                     <div class="space-y-1">
                         <router-link 
                            :to="{ name: 'EditProfile' }"
                            class="flex items-center gap-2.5 w-full px-4 py-2.5 font-bold rounded-xl text-[10px] uppercase tracking-wider transition-all duration-300 text-slate-600 hover:bg-slate-900 hover:text-white group shadow-sm hover:shadow-md border border-slate-50"
                         >
                             <svg class="w-4 h-4 text-slate-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                             Edit Information
                         </router-link>
                     </div>

                     <div class="px-3 py-3 mt-2 border-t border-slate-50">
                         <div class="flex items-center space-x-2.5">
                             <div class="bg-slate-50 p-1.5 rounded-lg text-slate-300">
                                 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                             </div>
                             <div class="flex flex-col">
                                 <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest leading-none">Join Date</p>
                                 <p class="text-[10px] font-bold text-slate-600 mt-1 uppercase">{{ formatDate(user?.created_at) }}</p>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
        </div>

        <!-- Main Content Column -->
        <div class="col-span-12 lg:col-span-9">
             <div class="bg-white rounded-[2rem] shadow-[0_4px_25px_rgb(0,0,0,0.02)] border border-slate-100 p-6 lg:p-10 min-h-[600px] relative">
                 <!-- Header -->
                 <div class="flex items-center justify-between mb-10 relative z-10 sm:px-2">
                     <div class="flex items-center space-x-4">
                         <div class="h-10 w-1 bg-[#00bcd4] rounded-full"></div>
                         <div>
                             <h1 class="text-xl font-black text-slate-900 tracking-tight uppercase leading-none">Account Overview</h1>
                             <p class="text-[9px] font-bold text-slate-400 mt-1.5 uppercase tracking-widest flex items-center">
                                <svg class="w-3 h-3 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                Verified Access
                             </p>
                         </div>
                     </div>
                     <router-link :to="{ name: 'EditProfile' }" class="hidden md:flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white font-bold rounded-xl text-[10px] uppercase tracking-widest hover:bg-[#00bcd4] transition-all duration-300">
                         <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                         Update Info
                     </router-link>
                 </div>

                 <!-- Content Sections -->
                 <div class="space-y-10 relative z-10 px-0 sm:px-2">
                     <!-- Section: Identity -->
                     <div>
                         <div class="flex items-center mb-6">
                             <div class="w-2 h-2 bg-slate-200 rounded-full mr-3"></div>
                             <h4 class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Personal Identification</h4>
                         </div>
                         <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 bg-slate-50/30 p-6 rounded-3xl border border-slate-100">
                             <div class="flex flex-col space-y-1">
                                 <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Full Name</span>
                                 <span class="text-[12px] font-bold text-slate-700">{{ user?.name || '---' }}</span>
                             </div>
                             <div class="flex flex-col space-y-1">
                                 <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">National ID (NID)</span>
                                 <span class="text-[12px] font-bold text-slate-700 tracking-wider">{{ user?.nid_number || '---' }}</span>
                             </div>
                             <div class="flex flex-col space-y-1">
                                 <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Organization</span>
                                 <span class="text-[12px] font-bold text-[#00bcd4]">{{ user?.company_name || '---' }}</span>
                             </div>
                         </div>
                     </div>

                     <!-- Section: Contact -->
                     <div>
                         <div class="flex items-center mb-6">
                             <div class="w-2 h-2 bg-slate-200 rounded-full mr-3"></div>
                             <h4 class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Contact Information</h4>
                         </div>
                         <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 bg-slate-50/30 p-6 rounded-3xl border border-slate-100">
                             <div class="flex flex-col space-y-1">
                                 <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Email Address</span>
                                 <span class="text-[12px] font-bold text-slate-700 truncate">{{ user?.email || 'N/A' }}</span>
                             </div>
                             <div class="flex flex-col space-y-1">
                                 <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Mobile Number</span>
                                 <span class="text-[12px] font-bold text-slate-700">{{ user?.country_code ? user.country_code + ' ' : '' }}{{ user?.mobile || '---' }}</span>
                             </div>
                             <div class="col-span-1 sm:col-span-2 lg:col-span-1 flex flex-col space-y-1">
                                 <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Current Location</span>
                                 <span class="text-[12px] font-bold text-slate-700 leading-tight uppercase">{{ user?.address || '---' }}</span>
                             </div>
                         </div>
                     </div>

                     <!-- Regional -->
                     <div>
                         <div class="flex items-center mb-6">
                             <div class="w-2 h-2 bg-slate-200 rounded-full mr-3"></div>
                             <h4 class="text-[10px] font-black text-slate-800 uppercase tracking-widest text-[#00bcd4]">Regional Presence</h4>
                         </div>
                         <div class="grid grid-cols-3 gap-4">
                             <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center text-center">
                                 <div class="text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1.5 leading-none">Division</div>
                                 <div class="text-[11px] font-black text-slate-700 uppercase truncate w-full px-1">{{ user?.division?.name || '---' }}</div>
                             </div>
                             <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center text-center">
                                 <div class="text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1.5 leading-none">District</div>
                                 <div class="text-[11px] font-black text-slate-700 uppercase truncate w-full px-1">{{ user?.district?.name || '---' }}</div>
                             </div>
                             <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center text-center">
                                 <div class="text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1.5 leading-none">Thana</div>
                                 <div class="text-[11px] font-black text-slate-700 uppercase truncate w-full px-1">{{ user?.thana?.name || '---' }}</div>
                             </div>
                         </div>
                     </div>

                     <!-- Documents -->
                     <div v-if="user?.nid_front || user?.nid_back">
                         <div class="flex items-center mb-6">
                             <div class="w-2 h-2 bg-slate-200 rounded-full mr-3"></div>
                             <h4 class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Verification Documents</h4>
                         </div>
                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                             <div class="flex flex-col space-y-3">
                                 <div class="flex items-center justify-between px-1">
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">NID Front Side</span>
                                    <span class="text-[7px] font-black text-[#00bcd4] bg-[#00bcd4]/5 px-2 py-0.5 rounded-full uppercase tracking-tighter">Verified</span>
                                 </div>
                                 <div class="aspect-[1.6/1] rounded-3xl overflow-hidden border border-slate-100 shadow-sm bg-slate-50 relative group cursor-pointer ring-1 ring-slate-100/50">
                                     <img v-if="user?.nid_front" :src="`/storage/${user.nid_front}`" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                                     <div v-else class="w-full h-full flex items-center justify-center">
                                          <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                     </div>
                                 </div>
                             </div>
                             
                             <div class="flex flex-col space-y-3">
                                 <div class="flex items-center justify-between px-1">
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">NID Back Side</span>
                                    <span class="text-[7px] font-black text-[#00bcd4] bg-[#00bcd4]/5 px-2 py-0.5 rounded-full uppercase tracking-tighter">Verified</span>
                                 </div>
                                 <div class="aspect-[1.6/1] rounded-3xl overflow-hidden border border-slate-100 shadow-sm bg-slate-50 relative group cursor-pointer ring-1 ring-slate-100/50">
                                     <img v-if="user?.nid_back" :src="`/storage/${user.nid_back}`" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                                     <div v-else class="w-full h-full flex items-center justify-center">
                                          <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const user = ref(JSON.parse(localStorage.getItem('user')) || null);

const fetchUser = async () => {
    try {
        const res = await axios.get('/api/user');
        user.value = res.data;
        localStorage.setItem('user', JSON.stringify(res.data));
    } catch (e) {
        console.error(e);
    }
};

const userInitials = computed(() => {
    if (!user.value?.name) return '??';
    const names = user.value.name.trim().split(/\s+/);
    if (names.length >= 2) {
        return (names[0][0] + names[names.length - 1][0]).toUpperCase();
    }
    return user.value.name.substring(0, 2).toUpperCase();
});

const formatDate = (dateString) => {
    if (!dateString) return '---';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
};

onMounted(() => {
    fetchUser();
});
</script>

<style scoped>
.shadow-inner {
    box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);
}
</style>
