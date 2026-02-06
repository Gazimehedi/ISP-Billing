<template>
    <div class="grid grid-cols-12 gap-6 p-4 lg:p-0">
        <!-- Sidebar Navigation Card -->
        <div class="col-span-12 lg:col-span-3">
             <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                 <!-- Profile Header in Sidebar -->
                 <div class="p-8 pb-4 text-center border-b border-gray-50 bg-gradient-to-b from-blue-50/30 to-white">
                     <div class="relative inline-block mb-4 group cursor-pointer" @click="$refs.profilePicInput.click()">
                         <div class="w-24 h-24 rounded-full border-2 border-dashed border-blue-400 p-1 mx-auto relative overflow-hidden transition-all duration-300 group-hover:border-blue-600">
                            <img v-if="form.profile_pic_url" :src="form.profile_pic_url" class="w-full h-full rounded-full object-cover shadow-inner" />
                            <div v-else class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                 <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                            </div>
                            <div class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 rounded-full scale-95 group-hover:scale-100">
                                <svg class="w-6 h-6 text-white mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-white text-[10px] font-bold uppercase tracking-wider">Change</span>
                            </div>
                         </div>
                         <input type="file" ref="profilePicInput" class="hidden" @change="handleProfilePic($event)" accept="image/*" />
                     </div>

                     
                     <h2 class="text-xl font-bold text-gray-800 tracking-tight leading-tight">{{ form.company_name || 'Silicon ISP' }}</h2>
                     <div class="mt-2 flex items-center justify-center gap-2">
                         <span class="inline-flex items-center bg-blue-50 text-blue-600 text-[10px] uppercase font-bold px-3 py-1 rounded-full border border-blue-100">
                             <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 animate-pulse"></span>
                             Merchant
                         </span>
                     </div>
                 </div>


                 <!-- Nav Menu -->
                 <div class="p-4 space-y-1 bg-white">
                     <button 
                        @click="activeTab = 'personal'"
                        :class="['flex items-center gap-3 w-full px-4 py-3 font-semibold rounded-xl text-sm transition-all duration-300', activeTab === 'personal' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200 -translate-y-0.5' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600']"
                     >
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                         Personal Information
                     </button>
                     <button 
                        @click="activeTab = 'password'"
                        :class="['flex items-center gap-3 w-full px-4 py-3 font-semibold rounded-xl text-sm transition-all duration-300', activeTab === 'password' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200 -translate-y-0.5' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600']"
                     >
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                         Change Password
                     </button>
                     <button 
                        @click="activeTab = 'mobile'"
                        :class="['flex items-center gap-3 w-full px-4 py-3 font-semibold rounded-xl text-sm transition-all duration-300', activeTab === 'mobile' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200 -translate-y-0.5' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600']"
                     >
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                         Change Mobile Number
                     </button>
                     <button 
                        @click="activeTab = 'nid'"
                        :class="['flex items-center gap-3 w-full px-4 py-3 font-semibold rounded-xl text-sm transition-all duration-300', activeTab === 'nid' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200 -translate-y-0.5' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600']"
                     >
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                         NID File Upload
                     </button>
                 </div>
             </div>
        </div>


        <!-- Main Content Column -->
        <div class="col-span-12 lg:col-span-9">
             <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-10 min-h-[600px] relative overflow-hidden">
                 <!-- Background Decoration -->
                 <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
                 <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-green-50 rounded-full blur-3xl opacity-50"></div>

                 <!-- Content Tabs Header -->
                 <div class="flex items-center justify-between mb-8 relative z-10">
                     <div>
                         <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                            {{ activeTab === 'personal' ? 'Update Profile' : activeTab === 'password' ? 'Change Password' : activeTab === 'mobile' ? 'Mobile Settings' : 'NID Verification' }}
                         </h1>
                         <p class="text-sm text-gray-500 mt-1">Manage your account settings and preferences.</p>
                     </div>
                 </div>


                  <!-- Personal Info Tab -->
                  <div v-show="activeTab === 'personal'" class="space-y-8 relative z-10 transition-all duration-500 animate-in fade-in slide-in-from-bottom-4">
                    <!-- Section: Basic Identity -->
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                            <h4 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Basic Identity</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
                             <div class="group">
                                 <label class="block text-xs font-bold text-gray-500 mb-2 transition-colors group-focus-within:text-blue-600">Full Name <span class="text-red-500">*</span></label>
                                 <input v-model="form.name" type="text" placeholder="e.g. John Doe" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all bg-white">
                                 <p v-if="errors.name" class="text-[10px] text-red-500 mt-1.5 flex items-center gap-1">
                                     <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                     {{ errors.name[0] }}
                                 </p>
                             </div>
                             <div class="group">
                                 <label class="block text-xs font-bold text-gray-500 mb-2 transition-colors group-focus-within:text-blue-600">NID Number <span class="text-red-500">*</span></label>
                                 <input v-model="form.nid_number" type="text" placeholder="e.g. 1234567890" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all bg-white">
                                 <p v-if="errors.nid_number" class="text-[10px] text-red-500 mt-1.5 flex items-center gap-1">
                                     <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                     {{ errors.nid_number[0] }}
                                 </p>
                             </div>
                             <div class="md:col-span-2 group">
                                 <label class="block text-xs font-bold text-gray-500 mb-2 transition-colors group-focus-within:text-blue-600">Company Name <span class="text-red-500">*</span></label>
                                 <input v-model="form.company_name" type="text" placeholder="e.g. Silicon ISP" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all bg-white">
                                 <p v-if="errors.company_name" class="text-[10px] text-red-500 mt-1.5 flex items-center gap-1">
                                     <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                     {{ errors.company_name[0] }}
                                 </p>
                             </div>
                        </div>
                    </div>

                    <!-- Section: Location Details -->
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-6 bg-green-600 rounded-full"></div>
                            <h4 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Location Details</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
                             <div class="group">
                                 <label class="block text-xs font-bold text-gray-500 mb-2 transition-colors group-focus-within:text-blue-600">Division <span class="text-red-500">*</span></label>
                                 <select v-model="form.division_id" @change="fetchDistricts" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all bg-white appearance-none cursor-pointer">
                                     <option value="" disabled>Select Division</option>
                                     <option v-for="div in divisions" :key="div.id" :value="div.id">{{ div.name }}</option>
                                 </select>
                                 <p v-if="errors.division_id" class="text-[10px] text-red-500 mt-1.5">{{ errors.division_id[0] }}</p>
                             </div>
                             <div class="group">
                                 <label class="block text-xs font-bold text-gray-500 mb-2 transition-colors group-focus-within:text-blue-600">District <span class="text-red-500">*</span></label>
                                 <select v-model="form.district_id" @change="fetchThanas" :disabled="!form.division_id" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all bg-white disabled:opacity-50 appearance-none cursor-pointer">
                                     <option value="" disabled>Select District</option>
                                     <option v-for="dist in districts" :key="dist.id" :value="dist.id">{{ dist.name }}</option>
                                 </select>
                                 <p v-if="errors.district_id" class="text-[10px] text-red-500 mt-1.5">{{ errors.district_id[0] }}</p>
                             </div>
                             <div class="group">
                                 <label class="block text-xs font-bold text-gray-500 mb-2 transition-colors group-focus-within:text-blue-600">Thana <span class="text-red-500">*</span></label>
                                 <select v-model="form.thana_id" :disabled="!form.district_id" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all bg-white disabled:opacity-50 appearance-none cursor-pointer">
                                     <option value="" disabled>Select Thana</option>
                                     <option v-for="thana in thanas" :key="thana.id" :value="thana.id">{{ thana.name }}</option>
                                 </select>
                                 <p v-if="errors.thana_id" class="text-[10px] text-red-500 mt-1.5">{{ errors.thana_id[0] }}</p>
                             </div>
                             <div class="md:col-span-3 group">
                                 <label class="block text-xs font-bold text-gray-500 mb-2 transition-colors group-focus-within:text-blue-600">Full Address <span class="text-red-500">*</span></label>
                                 <textarea v-model="form.address" rows="3" placeholder="Enter your complete address..." class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all bg-white"></textarea>
                                 <p v-if="errors.address" class="text-[10px] text-red-500 mt-1.5">{{ errors.address[0] }}</p>
                             </div>
                        </div>
                    </div>
                  </div> 

                  <!-- NID Upload Tab -->
                  <div v-show="activeTab === 'nid'" class="space-y-8 relative z-10 transition-all duration-500 animate-in fade-in slide-in-from-bottom-4">
                      <div class="flex items-center gap-3 mb-2">
                          <div class="w-1.5 h-6 bg-purple-600 rounded-full"></div>
                          <h4 class="text-sm font-bold text-gray-800 uppercase tracking-widest">NID Verification</h4>
                      </div>
                      
                      <div class="bg-gray-50/50 rounded-2xl p-6 lg:p-8 border border-gray-100">
                          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                              <!-- Front Side -->
                              <div class="flex flex-col">
                                  <label class="block text-xs font-bold text-gray-500 mb-3 uppercase tracking-wider">National ID (Front Side)</label>
                                  <div 
                                    class="relative border-2 border-dashed border-gray-300 rounded-2xl p-2 flex flex-col items-center justify-center text-center bg-white hover:border-blue-400 hover:bg-blue-50/30 transition-all cursor-pointer min-h-[220px] group overflow-hidden shadow-sm"
                                    @click="$refs.nidFrontInput.click()"
                                    @dragover.prevent="$event.currentTarget.classList.add('border-blue-500', 'bg-blue-50')"
                                    @dragleave.prevent="$event.currentTarget.classList.remove('border-blue-500', 'bg-blue-50')"
                                    @drop.prevent="handleDrop($event, 'nid_front')"
                                  >
                                    <template v-if="!form.nid_front_file && !user?.nid_front">
                                        <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-700">Drop Front Side</p>
                                        <p class="text-[10px] text-gray-400 mt-1 uppercase font-semibold">Max 2MB • JPG, PNG</p>
                                    </template>
                                    <template v-else>
                                        <div class="relative w-full h-full group">
                                            <img :src="form.nid_front_file ? getFilePreview(form.nid_front_file) : `/storage/${user.nid_front}`" class="w-full h-[200px] object-cover rounded-xl transition-transform duration-500 group-hover:scale-105">
                                            <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 rounded-xl">
                                                <div class="bg-white/20 backdrop-blur-md p-2 rounded-full mb-2">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                </div>
                                                <span class="text-white text-[10px] font-bold uppercase tracking-widest">Replace File</span>
                                            </div>
                                        </div>
                                    </template>
                                  </div>
                                  <input type="file" ref="nidFrontInput" class="hidden" @change="handleFile($event, 'nid_front')" accept="image/*">
                              </div>

                              <!-- Back Side -->
                              <div class="flex flex-col">
                                  <label class="block text-xs font-bold text-gray-500 mb-3 uppercase tracking-wider">National ID (Back Side)</label>
                                  <div 
                                    class="relative border-2 border-dashed border-gray-300 rounded-2xl p-2 flex flex-col items-center justify-center text-center bg-white hover:border-blue-400 hover:bg-blue-50/30 transition-all cursor-pointer min-h-[220px] group overflow-hidden shadow-sm"
                                    @click="$refs.nidBackInput.click()"
                                    @dragover.prevent="$event.currentTarget.classList.add('border-blue-500', 'bg-blue-50')"
                                    @dragleave.prevent="$event.currentTarget.classList.remove('border-blue-500', 'bg-blue-50')"
                                    @drop.prevent="handleDrop($event, 'nid_back')"
                                  >
                                    <template v-if="!form.nid_back_file && !user?.nid_back">
                                        <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-700">Drop Back Side</p>
                                        <p class="text-[10px] text-gray-400 mt-1 uppercase font-semibold">Max 2MB • JPG, PNG</p>
                                    </template>
                                    <template v-else>
                                        <div class="relative w-full h-full group">
                                            <img :src="form.nid_back_file ? getFilePreview(form.nid_back_file) : `/storage/${user.nid_back}`" class="w-full h-[200px] object-cover rounded-xl transition-transform duration-500 group-hover:scale-105">
                                            <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 rounded-xl">
                                                <div class="bg-white/20 backdrop-blur-md p-2 rounded-full mb-2">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                </div>
                                                <span class="text-white text-[10px] font-bold uppercase tracking-widest">Replace File</span>
                                            </div>
                                        </div>
                                    </template>
                                  </div>
                                  <input type="file" ref="nidBackInput" class="hidden" @change="handleFile($event, 'nid_back')" accept="image/*">
                              </div>
                          </div>
                      </div>
                  </div> 

                  <!-- Change Password Tab -->
                  <div v-show="activeTab === 'password'" class="relative z-10 transition-all duration-500 animate-in fade-in slide-in-from-bottom-4">
                      <div class="flex items-center gap-3 mb-6">
                          <div class="w-1.5 h-6 bg-red-600 rounded-full"></div>
                          <h4 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Security Settings</h4>
                      </div>
                      
                      <div class="max-w-xl mx-auto bg-gray-50/50 p-8 rounded-3xl border border-gray-100 shadow-sm">
                           <div class="mb-6 group">
                              <label class="block text-xs font-bold text-gray-500 mb-2 transition-colors group-focus-within:text-red-600">Current Password <span class="text-red-500">*</span></label>
                              <div class="relative">
                                  <input v-model="passwordForm.current_password" type="password" placeholder="••••••••" class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-3 text-sm focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all bg-white">
                                  <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                              </div>
                              <p v-if="errors.current_password" class="text-[10px] text-red-500 mt-2">{{ errors.current_password[0] }}</p>
                          </div>
                          
                          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 transition-all duration-300" v-if="passwordForm.current_password.length > 0">
                              <div class="group">
                                  <label class="block text-xs font-bold text-gray-500 mb-2 transition-colors group-focus-within:text-red-600">New Password <span class="text-red-500">*</span></label>
                                  <div class="relative">
                                      <input v-model="passwordForm.password" type="password" placeholder="Min. 4 chars" class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-3 text-sm focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all bg-white">
                                      <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                  </div>
                                  <p v-if="errors.password" class="text-[10px] text-red-500 mt-2">{{ errors.password[0] }}</p>
                              </div>
                              <div class="group">
                                  <label class="block text-xs font-bold text-gray-500 mb-2 transition-colors group-focus-within:text-red-600">Re-type Password <span class="text-red-500">*</span></label>
                                  <div class="relative">
                                      <input v-model="passwordForm.password_confirmation" type="password" placeholder="••••••••" class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-3 text-sm focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all bg-white">
                                      <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                  </div>
                              </div>
                          </div>

                          <div class="flex justify-end pt-8">
                              <button @click="submitPasswordChange" :disabled="loading" class="group flex items-center gap-2 px-8 py-3 bg-red-600 text-white font-extrabold rounded-2xl text-xs hover:bg-red-700 transition-all duration-300 uppercase tracking-widest disabled:opacity-50 shadow-lg shadow-red-100 hover:shadow-red-200 hover:-translate-y-1 active:translate-y-0">
                                  <svg class="w-4 h-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-if="!loading"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                  {{ loading ? 'Updating...' : 'Confirm Update' }}
                              </button>
                          </div>
                      </div>
                  </div>

                  <!-- Change Mobile Tab -->
                  <div v-show="activeTab === 'mobile'" class="relative z-10 transition-all duration-500 animate-in fade-in slide-in-from-bottom-4">
                      <div class="flex items-center gap-3 mb-6">
                          <div class="w-1.5 h-6 bg-cyan-600 rounded-full"></div>
                          <h4 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Mobile Settings</h4>
                      </div>
                      
                      <div class="max-w-xl mx-auto bg-gray-50/50 p-8 rounded-3xl border border-gray-100 shadow-sm">
                           <div class="mb-6 group">
                              <label class="block text-xs font-bold text-gray-500 mb-2 transition-colors group-focus-within:text-cyan-600">Current Password <span class="text-red-500">*</span></label>
                              <div class="relative">
                                  <input v-model="mobileForm.current_password" type="password" placeholder="••••••••" class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-3 text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 outline-none transition-all bg-white">
                                  <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-cyan-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                              </div>
                              <p v-if="errors.current_password" class="text-[10px] text-red-500 mt-2">{{ errors.current_password[0] }}</p>
                          </div>
                          
                           <div class="mb-8 group">
                              <label class="block text-xs font-bold text-gray-500 mb-2 transition-colors group-focus-within:text-cyan-600">New Mobile Number <span class="text-red-500">*</span></label>
                              <div class="relative flex items-center">
                                  <!-- Country Selector Overlay -->
                                  <div class="relative">
                                      <button 
                                        @click.stop="showCountryDropdown = !showCountryDropdown"
                                        type="button" 
                                        class="flex items-center gap-2 px-4 py-3 border border-gray-300 border-r-0 rounded-l-xl bg-gray-100 hover:bg-gray-200 transition-all text-sm font-bold text-gray-700"
                                      >
                                          <span>{{ selectedCountry.flag }}</span>
                                          <span>{{ selectedCountry.code }}</span>
                                          <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                      </button>
                                      
                                      <!-- Dropdown with Glassmorphism -->
                                      <div 
                                        v-if="showCountryDropdown" 
                                        class="absolute left-0 top-full mt-2 w-56 bg-white/95 backdrop-blur-xl border border-gray-100 rounded-2xl shadow-2xl z-[100] py-2 overflow-hidden animate-in fade-in zoom-in-95 duration-200"
                                        @click.stop
                                      >
                                          <div class="px-4 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 mb-1">Select Country</div>
                                          <div class="max-h-64 overflow-y-auto custom-scrollbar">
                                              <button 
                                                v-for="c in countries" 
                                                :key="c.code"
                                                @click="selectCountry(c)"
                                                class="flex items-center gap-3 w-full px-4 py-3 text-sm text-left hover:bg-blue-50 transition-all group/item"
                                              >
                                                  <span class="text-xl group-hover/item:scale-125 transition-transform">{{ c.flag }}</span>
                                                  <div class="flex flex-col">
                                                      <span class="font-bold text-gray-700">{{ c.code }}</span>
                                                      <span class="text-[10px] text-gray-400 font-medium">{{ c.name }}</span>
                                                  </div>
                                                  <svg v-if="selectedCountry.code === c.code" class="ml-auto w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                              </button>
                                          </div>
                                      </div>
                                  </div>
                                  
                                  <!-- Mobile Input -->
                                  <input 
                                    v-model="mobileForm.mobile" 
                                    type="text" 
                                    placeholder="e.g. 1899 999999" 
                                    class="w-full rounded-r-xl border border-gray-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 outline-none transition-all bg-white font-medium tracking-wide"
                                  >
                              </div>
                              <p v-if="errors.mobile" class="text-[10px] text-red-500 mt-2">{{ errors.mobile[0] }}</p>
                          </div>

                          <div class="flex justify-end">
                              <button @click="submitMobileChange" :disabled="loading" class="group flex items-center gap-2 px-8 py-3 bg-cyan-600 text-white font-extrabold rounded-2xl text-xs hover:bg-cyan-700 transition-all duration-300 uppercase tracking-widest disabled:opacity-50 shadow-lg shadow-cyan-100 hover:shadow-cyan-200 hover:-translate-y-1 active:translate-y-0">
                                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-if="!loading"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                  {{ loading ? 'Updating...' : 'Update Mobile' }}
                              </button>
                          </div>
                      </div>
                  </div>

                  <!-- Global Actions Bar -->
                  <div class="mt-12 flex items-center justify-between gap-4 border-t border-gray-100 pt-8 relative z-10" v-if="!['password', 'mobile'].includes(activeTab)">
                     <div v-if="activeTab === 'personal'" class="flex items-center gap-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                         <span class="w-2 h-2 rounded-full bg-red-400"></span>
                         Required Fields
                     </div>
                     <div v-else></div>
                     
                     <div class="flex gap-4">
                         <button @click="resetForm" class="px-8 py-3 bg-gray-100 text-gray-600 font-extrabold rounded-2xl text-xs hover:bg-gray-200 transition-all duration-300 uppercase tracking-widest hover:text-gray-800">
                             Reset
                         </button>
                         <button @click="submitForm" :disabled="loading" class="group flex items-center gap-2 px-10 py-3 bg-emerald-600 text-white font-extrabold rounded-2xl text-xs hover:bg-emerald-700 transition-all duration-300 uppercase tracking-widest disabled:opacity-50 shadow-lg shadow-emerald-100 hover:shadow-emerald-200 hover:-translate-y-1 active:translate-y-0">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-if="!loading"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                             {{ loading ? 'Saving...' : 'Save Profile' }}
                         </button>
                     </div>
                  </div>
             </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const loading = ref(false);
const user = ref(null);
const divisions = ref([]);
const districts = ref([]);
const thanas = ref([]);
const errors = ref({});
const activeTab = ref('personal');

const form = ref({
    name: '',
    nid_number: '',
    division_id: '',
    district_id: '',
    thana_id: '',
    address: '',
    company_name: '',
    profile_pic_url: null,
    profile_pic_file: null,
    nid_back_file: null,
    nid_front_file: null
});

const passwordForm = ref({
    current_password: '',
    password: '',
    password_confirmation: ''
});

const mobileForm = ref({
    current_password: '',
    mobile: ''
});

const countries = ref([
    { code: '+880', flag: '🇧🇩', name: 'Bangladesh' },
    { code: '+91', flag: '🇮🇳', name: 'India' },
    { code: '+92', flag: '🇵🇰', name: 'Pakistan' },
    { code: '+971', flag: '🇦🇪', name: 'UAE' },
    { code: '+1', flag: '🇺🇸', name: 'USA' },
    { code: '+44', flag: '🇬🇧', name: 'UK' }
]);

const selectedCountry = ref(countries.value[0]);
const showCountryDropdown = ref(false);

const selectCountry = (country) => {
    selectedCountry.value = country;
    showCountryDropdown.value = false;
};

const handleProfilePic = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.value.profile_pic_file = file;
        form.value.profile_pic_url = URL.createObjectURL(file);
    }
};

const handleDrop = (event, field) => {
    event.currentTarget.classList.remove('border-blue-500', 'bg-blue-50');
    const file = event.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        if (field === 'nid_back') form.value.nid_back_file = file;
        if (field === 'nid_front') form.value.nid_front_file = file;
    }
};

const getFilePreview = (file) => {
    if(!file) return null;
    return URL.createObjectURL(file);
};

const handleFile = (event, field) => {
    const file = event.target.files[0];
    if (file) {
        if (field === 'nid_back') form.value.nid_back_file = file;
        if (field === 'nid_front') form.value.nid_front_file = file;
    }
};

const fetchDivisions = async () => {
    try {
        const res = await axios.get('/api/geo/divisions');
        divisions.value = res.data;
    } catch (e) {
        console.error(e);
    }
};

const fetchDistricts = async () => {
    if(!form.value.division_id) return;
    try {
        const res = await axios.get(`/api/geo/districts/${form.value.division_id}`);
        districts.value = res.data;
        thanas.value = []; // reset thanas
        if (!districts.value.find(d => d.id === form.value.district_id)) {
            form.value.district_id = '';
            form.value.thana_id = '';
        }
    } catch (e) {
        console.error(e);
    }
};

const fetchThanas = async () => {
    if(!form.value.district_id) return;
    try {
        const res = await axios.get(`/api/geo/thanas/${form.value.district_id}`);
        thanas.value = res.data;
        if (!thanas.value.find(t => t.id === form.value.thana_id)) {
            form.value.thana_id = '';
        }
    } catch (e) {
        console.error(e);
    }
};

const fetchUser = async () => {
    try {
        const res = await axios.get('/api/user');
        user.value = res.data;
        form.value.name = user.value.name;
        form.value.nid_number = user.value.nid_number;
        form.value.division_id = user.value.division_id;
        form.value.district_id = user.value.district_id;
        form.value.thana_id = user.value.thana_id;
        form.value.address = user.value.address;
        form.value.company_name = user.value.company_name;
        form.value.profile_pic_url = user.value.profile_pic_url; // from backend accessor/resource

        // Trigger cascading fetches if value exists
        if(form.value.division_id) await fetchDistricts();
        if(form.value.district_id) await fetchThanas();

        // Restore IDs after fetch (in case reset happened)
         form.value.district_id = user.value.district_id;
         form.value.thana_id = user.value.thana_id;

         // Detect country code
         if (user.value.mobile) {
             const found = countries.value.find(c => user.value.mobile.startsWith(c.code));
             if (found) {
                 selectedCountry.value = found;
                 mobileForm.value.mobile = user.value.mobile.replace(found.code, '');
             } else {
                 mobileForm.value.mobile = user.value.mobile;
             }
         }

    } catch (e) {
        console.error(e);
    }
};

const resetForm = () => {
    fetchUser(); // Reset to current db values
};

const submitForm = async () => {
    loading.value = true;
    errors.value = {};
    const formData = new FormData();
    formData.append('name', form.value.name || '');
    formData.append('nid_number', form.value.nid_number || '');
    formData.append('division_id', form.value.division_id || '');
    formData.append('district_id', form.value.district_id || '');
    formData.append('thana_id', form.value.thana_id || '');
    formData.append('address', form.value.address || '');
    formData.append('company_name', form.value.company_name || '');
    
    if (form.value.nid_front_file) formData.append('nid_front', form.value.nid_front_file);
    if (form.value.nid_back_file) formData.append('nid_back', form.value.nid_back_file);
    if (form.value.profile_pic_file) formData.append('profile_pic', form.value.profile_pic_file);
    
    try {
        const res = await axios.post('/api/user/update', formData);
        user.value = res.data.user;
        Swal.fire('Success', 'Profile updated successfully!', 'success');
    } catch (e) {
        console.error(e);
        if (e.response && e.response.status === 422) {
            errors.value = e.response.data.errors;
            Swal.fire('Error', 'Please check the required fields.', 'error');
        } else {
            Swal.fire('Error', 'Failed to update profile', 'error');
        }
    } finally {
        loading.value = false;
    }
};

const submitPasswordChange = async () => {
    loading.value = true;
    errors.value = {};
    
    try {
        await axios.post('/api/user/update-password', passwordForm.value);
        Swal.fire('Success', 'Password updated successfully!', 'success');
        passwordForm.value = { current_password: '', password: '', password_confirmation: '' };
    } catch (e) {
        console.error(e);
        if (e.response && e.response.status === 422) {
            errors.value = e.response.data.errors;
            Swal.fire('Error', 'Please check your inputs.', 'error');
        } else {
            Swal.fire('Error', 'Failed to update password', 'error');
        }
    } finally {
        loading.value = false;
    }
};

const submitMobileChange = async () => {
    loading.value = true;
    errors.value = {};
    
    try {
        const fullMobile = `${selectedCountry.value.code}${mobileForm.value.mobile}`;
        await axios.post('/api/user/update-mobile', {
            ...mobileForm.value,
            mobile: fullMobile
        });
        Swal.fire('Success', 'Mobile number updated successfully!', 'success');
        mobileForm.value.current_password = '';
        fetchUser(); // Refresh user data to show new mobile in UI
    } catch (e) {
        console.error(e);
        if (e.response && e.response.status === 422) {
            errors.value = e.response.data.errors;
            Swal.fire('Error', 'Please check your inputs.', 'error');
        } else {
            Swal.fire('Error', 'Failed to update mobile number', 'error');
        }
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchDivisions();
    fetchUser();
    
    // Close dropdown on click outside
    window.addEventListener('click', () => {
        showCountryDropdown.value = false;
    });
});
</script>

<style scoped>
/* Smooth slide-in animations */
@keyframes slide-in-bottom {
    0% { transform: translateY(10px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
}

.animate-in {
    animation: slide-in-bottom 0.5s ease-out forwards;
}

/* Glassmorphism focus effect */
input:focus, select:focus, textarea:focus {
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
}

/* Custom Scrollbar for dropdowns */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}
.overflow-y-auto::-webkit-scrollbar-track {
    background: transparent;
}
.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 10px;
}
.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #d1d5db;
}
</style>
