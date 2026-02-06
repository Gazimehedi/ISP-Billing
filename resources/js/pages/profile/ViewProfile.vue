<template>
    <div class="grid grid-cols-12 gap-6 p-4 lg:p-0">
        <!-- Sidebar Navigation Card (Read Only Version) -->
        <div class="col-span-12 lg:col-span-3">
             <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                 <!-- Profile Header -->
                 <div class="p-8 pb-4 text-center border-b border-gray-50 bg-gradient-to-b from-blue-50/30 to-white">
                     <div class="relative inline-block mb-4">
                         <div class="w-24 h-24 rounded-full border-2 border-blue-100 p-1 mx-auto relative overflow-hidden transition-all duration-300">
                            <img v-if="user?.profile_pic_url" :src="user.profile_pic_url" class="w-full h-full rounded-full object-cover shadow-inner" />
                            <div v-else class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                 <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                            </div>
                         </div>
                     </div>
                     
                     <h2 class="text-xl font-bold text-gray-800 tracking-tight leading-tight">{{ user?.company_name || 'Silicon ISP' }}</h2>
                     <div class="mt-2 flex items-center justify-center gap-2">
                         <span class="inline-flex items-center bg-blue-50 text-blue-600 text-[10px] uppercase font-bold px-3 py-1 rounded-full border border-blue-100">
                             <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 animate-pulse"></span>
                             Merchant
                         </span>
                     </div>
                 </div>

                 <!-- Quick Actions -->
                 <div class="p-4 space-y-1 bg-white">
                     <router-link 
                        :to="{ name: 'EditProfile' }"
                        class="flex items-center gap-3 w-full px-4 py-3 font-semibold rounded-xl text-sm transition-all duration-300 text-gray-600 hover:bg-blue-50 hover:text-blue-600"
                     >
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                         Edit Profile
                     </router-link>
                     <div class="px-4 py-2 mt-4 border-t border-gray-50">
                         <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Member Since</p>
                         <p class="text-xs font-bold text-gray-700">{{ formatDate(user?.created_at) }}</p>
                     </div>
                 </div>
             </div>
        </div>

        <!-- Main Content Column -->
        <div class="col-span-12 lg:col-span-9">
             <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-10 min-h-[600px] relative overflow-hidden">
                 <!-- Background Decoration -->
                 <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
                 <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>

                 <!-- Header -->
                 <div class="flex items-center justify-between mb-8 relative z-10">
                     <div>
                         <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Account Overview</h1>
                         <p class="text-sm text-gray-500 mt-1">Full profile information and verification status.</p>
                     </div>
                     <router-link :to="{ name: 'EditProfile' }" class="hidden md:flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl text-xs hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                         Modify Profile
                     </router-link>
                 </div>

                 <!-- Profile Details Sections -->
                 <div class="space-y-10 relative z-10">
                     <!-- Section: Identity -->
                     <div>
                         <div class="flex items-center gap-3 mb-6">
                             <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                             <h4 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Personal Identification</h4>
                         </div>
                         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 bg-gray-50/50 p-8 rounded-3xl border border-gray-100">
                             <div>
                                 <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Full Name</p>
                                 <p class="text-sm font-bold text-gray-800">{{ user?.name || 'N/A' }}</p>
                             </div>
                             <div>
                                 <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">NID Number</p>
                                 <p class="text-sm font-bold text-gray-800">{{ user?.nid_number || 'N/A' }}</p>
                             </div>
                             <div>
                                 <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Company</p>
                                 <p class="text-sm font-bold text-gray-800">{{ user?.company_name || 'N/A' }}</p>
                             </div>
                         </div>
                     </div>

                     <!-- Section: Contact & Location -->
                     <div>
                         <div class="flex items-center gap-3 mb-6">
                             <div class="w-1.5 h-6 bg-emerald-600 rounded-full"></div>
                             <h4 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Contact & Location</h4>
                         </div>
                         <div class="bg-gray-50/50 p-8 rounded-3xl border border-gray-100">
                             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8 pb-8 border-b border-gray-100">
                                 <div>
                                     <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Email Address</p>
                                     <p class="text-sm font-bold text-gray-800">{{ user?.email || 'N/A' }}</p>
                                 </div>
                                 <div>
                                     <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Mobile Number</p>
                                     <p class="text-sm font-bold text-gray-800">{{ user?.mobile || 'N/A' }}</p>
                                 </div>
                                  <div>
                                     <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Division</p>
                                     <p class="text-sm font-bold text-gray-800">{{ user?.division?.name || 'N/A' }}</p>
                                 </div>
                             </div>
                             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8 pb-8 border-b border-gray-100">
                                 <div>
                                     <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">District</p>
                                     <p class="text-sm font-bold text-gray-800">{{ user?.district?.name || 'N/A' }}</p>
                                 </div>
                                 <div>
                                     <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Thana</p>
                                     <p class="text-sm font-bold text-gray-800">{{ user?.thana?.name || 'N/A' }}</p>
                                 </div>
                             </div>
                             <div>
                                 <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Full Address</p>
                                 <p class="text-sm font-bold text-gray-800 leading-relaxed">{{ user?.address || 'N/A' }}</p>
                             </div>
                         </div>
                     </div>

                     <!-- Section: NID Documents -->
                     <div v-if="user?.nid_front || user?.nid_back">
                         <div class="flex items-center gap-3 mb-6">
                             <div class="w-1.5 h-6 bg-purple-600 rounded-full"></div>
                             <h4 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Verification Documents</h4>
                         </div>
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                             <div class="group">
                                 <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Front Side</p>
                                 <div class="aspect-video rounded-3xl overflow-hidden border border-gray-100 shadow-sm bg-white relative">
                                     <img v-if="user?.nid_front" :src="`/storage/${user.nid_front}`" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                     <div v-else class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                          <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                          <span class="text-[10px] font-bold uppercase tracking-widest">Not Uploaded</span>
                                     </div>
                                 </div>
                             </div>
                             <div class="group">
                                 <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Back Side</p>
                                 <div class="aspect-video rounded-3xl overflow-hidden border border-gray-100 shadow-sm bg-white relative">
                                     <img v-if="user?.nid_back" :src="`/storage/${user.nid_back}`" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                     <div v-else class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                          <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                          <span class="text-[10px] font-bold uppercase tracking-widest">Not Uploaded</span>
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
import { ref, onMounted } from 'vue';
import axios from 'axios';

const user = ref(null);

const fetchUser = async () => {
    try {
        const res = await axios.get('/api/user');
        user.value = res.data;
    } catch (e) {
        console.error(e);
    }
};

const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
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
