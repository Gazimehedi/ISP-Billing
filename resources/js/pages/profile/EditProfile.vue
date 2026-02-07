<template>
    <div class="grid grid-cols-12 gap-6 p-6 font-sans">
        <!-- Sidebar Navigation Card -->
        <div class="col-span-12 lg:col-span-3">
             <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgb(0,0,0,0.03)] border border-slate-100 overflow-hidden sticky top-6">
                 <!-- Profile Header in Sidebar -->
                 <div class="p-6 pb-5 text-center border-b border-slate-50 bg-gradient-to-b from-slate-50/10 to-white">
                      <!-- Changeable Avatar -->
                      <div class="relative inline-block mb-5 group cursor-pointer" @click="$refs.profilePicInput.click()">
                          <div class="w-24 h-24 rounded-3xl p-1 mx-auto relative overflow-hidden transition-all duration-300 ring-4 ring-white shadow-lg hover:ring-[#00bcd4]/20 bg-white">
                             <img v-if="form.profile_pic_url" :src="form.profile_pic_url" class="w-full h-full rounded-2xl object-cover shadow-inner transition-transform duration-500 group-hover:scale-105" />
                             <div v-else class="w-full h-full rounded-2xl bg-slate-900 flex items-center justify-center text-white font-black text-xl tracking-tighter transition-transform duration-500 group-hover:scale-105">
                                  {{ userInitials }}
                             </div>
                             
                             <!-- Change Overlay -->
                             <div class="absolute inset-0 bg-slate-900/60 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 rounded-2xl">
                                 <svg class="w-6 h-6 text-white mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                 </svg>
                                 <span class="text-white text-[8px] font-black uppercase tracking-widest">Change</span>
                             </div>
                          </div>
                          <input type="file" ref="profilePicInput" class="hidden" @change="handleProfilePic($event)" accept="image/*" />
                          
                          <!-- Edit Status Badge -->
                          <div class="absolute -bottom-0.5 -right-0.5 bg-[#00bcd4] text-white p-1.5 rounded-xl shadow-lg border-2 border-white">
                             <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                          </div>
                      </div>
                      
                      <h2 class="text-sm font-black text-slate-800 tracking-tight leading-tight uppercase truncate px-2">{{ form.company_name || 'Loading...' }}</h2>
                      <div class="mt-2 flex items-center justify-center">
                          <span class="px-3 py-1 bg-slate-50 text-slate-500 text-[8px] font-black uppercase tracking-widest rounded-full border border-slate-100">
                              Data Editor
                          </span>
                      </div>
                 </div>

                 <!-- Compact Nav Menu -->
                 <div class="p-3 space-y-1.5 bg-white">
                      <button 
                         @click="activeTab = 'personal'"
                         :class="['flex items-center gap-2.5 w-full px-4 py-3 font-black rounded-xl text-[10px] uppercase tracking-widest transition-all duration-300', activeTab === 'personal' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900']"
                      >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                          Identity
                      </button>
                      <button 
                         @click="activeTab = 'password'"
                         :class="['flex items-center gap-2.5 w-full px-4 py-3 font-black rounded-xl text-[10px] uppercase tracking-widest transition-all duration-300', activeTab === 'password' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900']"
                      >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                          Security
                      </button>
                      <button 
                         @click="activeTab = 'mobile'"
                         :class="['flex items-center gap-2.5 w-full px-4 py-3 font-black rounded-xl text-[10px] uppercase tracking-widest transition-all duration-300', activeTab === 'mobile' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900']"
                      >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                          Phone
                      </button>
                      <button 
                         @click="activeTab = 'nid'"
                         :class="['flex items-center gap-2.5 w-full px-4 py-3 font-black rounded-xl text-[10px] uppercase tracking-widest transition-all duration-300', activeTab === 'nid' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900']"
                      >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                          NID/Docs
                      </button>
                 </div>
             </div>
        </div>

        <!-- Main Workspace Column -->
        <div class="col-span-12 lg:col-span-9 font-sans">
             <div class="bg-white rounded-[2rem] shadow-[0_4px_25px_rgb(0,0,0,0.02)] border border-slate-100 p-6 lg:p-10 min-h-[600px] relative">
                 <!-- Header -->
                 <div class="flex items-center space-x-4 mb-10 relative z-10 sm:px-2">
                      <div class="h-8 w-1 bg-[#00bcd4] rounded-full"></div>
                      <div>
                          <h1 class="text-xl font-black text-slate-800 tracking-tight uppercase leading-none">
                             {{ activeTab === 'personal' ? 'Update Profile' : activeTab === 'password' ? 'Key Security' : activeTab === 'mobile' ? 'Phone Settings' : 'ID Verification' }}
                          </h1>
                          <p class="text-[9px] font-bold text-slate-400 mt-2 uppercase tracking-[0.2em] leading-none">
                            System Identity Configuration
                          </p>
                      </div>
                 </div>

                  <!-- Tab 1: Personal Info -->
                  <div v-show="activeTab === 'personal'" class="space-y-10 relative z-10 px-0 sm:px-2 transition-all duration-300">
                    <div>
                        <div class="flex items-center mb-6">
                             <div class="w-2 h-2 bg-slate-200 rounded-full mr-3"></div>
                             <h4 class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Basic Identity</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50/20 p-6 rounded-3xl border border-slate-100">
                             <div class="flex flex-col space-y-1.5 focus-within:translate-x-1 transition-transform">
                                 <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name <span class="text-rose-500">*</span></label>
                                 <input v-model="form.name" type="text" :class="['w-full rounded-xl border px-4 py-2.5 text-[12px] font-bold outline-none transition-all bg-white shadow-sm', errors.name ? 'border-rose-300 text-rose-600 focus:ring-rose-500/5' : 'border-slate-200 text-slate-600 focus:border-[#00bcd4] focus:ring-4 focus:ring-[#00bcd4]/5']">
                                 <p v-if="errors.name" class="text-[9px] font-bold text-rose-500 mt-1.5 tracking-tight ml-1">{{ errors.name[0] }}</p>
                             </div>
                             <div class="flex flex-col space-y-1.5 focus-within:translate-x-1 transition-transform">
                                 <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">National ID <span class="text-rose-500">*</span></label>
                                 <input v-model="form.nid_number" type="text" :class="['w-full rounded-xl border px-4 py-2.5 text-[12px] font-bold outline-none transition-all bg-white shadow-sm', errors.nid_number ? 'border-rose-300 text-rose-600 focus:ring-rose-500/5' : 'border-slate-200 text-slate-600 focus:border-[#00bcd4] focus:ring-4 focus:ring-[#00bcd4]/5']">
                                 <p v-if="errors.nid_number" class="text-[9px] font-bold text-rose-500 mt-1.5 tracking-tight ml-1">{{ errors.nid_number[0] }}</p>
                             </div>
                             <div class="md:col-span-2 flex flex-col space-y-1.5 focus-within:translate-x-1 transition-transform">
                                 <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                                 <input v-model="form.email" type="email" :class="['w-full rounded-xl border px-4 py-2.5 text-[12px] font-bold outline-none transition-all bg-white shadow-sm', errors.email ? 'border-rose-300 text-rose-600 focus:ring-rose-500/5' : 'border-slate-200 text-slate-600 focus:border-[#00bcd4] focus:ring-4 focus:ring-[#00bcd4]/5']">
                                 <p v-if="errors.email" class="text-[9px] font-bold text-rose-500 mt-1.5 tracking-tight ml-1">{{ errors.email[0] }}</p>
                             </div>
                             <div class="md:col-span-2 flex flex-col space-y-1.5">
                                 <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">Company / Organization <span class="text-rose-500">*</span></label>
                                 <input v-model="form.company_name" type="text" :class="['w-full rounded-xl border px-4 py-2.5 text-[12px] font-bold outline-none transition-all bg-white shadow-sm', errors.company_name ? 'border-rose-300 text-rose-600 focus:ring-rose-500/5' : 'border-slate-200 text-slate-600 focus:border-[#00bcd4] focus:ring-4 focus:ring-[#00bcd4]/5']">
                                 <p v-if="errors.company_name" class="text-[9px] font-bold text-rose-500 mt-1.5 tracking-tight ml-1">{{ errors.company_name[0] }}</p>
                             </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center mb-6">
                             <div class="w-2 h-2 bg-slate-200 rounded-full mr-3"></div>
                             <h4 class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Location Details</h4>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 bg-slate-50/20 p-6 rounded-3xl border border-slate-100">
                             <div class="flex flex-col space-y-1.5">
                                 <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">Division</label>
                                 <select v-model="form.division_id" @change="fetchDistricts" :class="['w-full rounded-xl border px-4 py-2.5 text-[12px] font-bold outline-none transition-all bg-white appearance-none cursor-pointer', errors.division_id ? 'border-rose-300 text-rose-600' : 'border-slate-200 text-slate-600 focus:border-[#00bcd4] focus:ring-4 focus:ring-[#00bcd4]/5']">
                                     <option value="" disabled>Select Division</option>
                                     <option v-for="div in divisions" :key="div.id" :value="div.id">{{ div.name }}</option>
                                 </select>
                                 <p v-if="errors.division_id" class="text-[9px] font-bold text-rose-500 mt-1.5 tracking-tight ml-1">{{ errors.division_id[0] }}</p>
                             </div>
                             <div class="flex flex-col space-y-1.5">
                                 <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">District</label>
                                 <select v-model="form.district_id" @change="fetchThanas" :disabled="!form.division_id" :class="['w-full rounded-xl border px-4 py-2.5 text-[12px] font-bold outline-none transition-all bg-white disabled:opacity-50 appearance-none cursor-pointer', errors.district_id ? 'border-rose-300 text-rose-600' : 'border-slate-200 text-slate-600 focus:border-[#00bcd4] focus:ring-4 focus:ring-[#00bcd4]/5']">
                                     <option value="" disabled>Select District</option>
                                     <option v-for="dist in districts" :key="dist.id" :value="dist.id">{{ dist.name }}</option>
                                 </select>
                                 <p v-if="errors.district_id" class="text-[9px] font-bold text-rose-500 mt-1.5 tracking-tight ml-1">{{ errors.district_id[0] }}</p>
                             </div>
                             <div class="flex flex-col space-y-1.5">
                                 <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">Thana / Area</label>
                                 <select v-model="form.thana_id" :disabled="!form.district_id" :class="['w-full rounded-xl border px-4 py-2.5 text-[12px] font-bold outline-none transition-all bg-white disabled:opacity-50 appearance-none cursor-pointer', errors.thana_id ? 'border-rose-300 text-rose-600' : 'border-slate-200 text-slate-600 focus:border-[#00bcd4] focus:ring-4 focus:ring-[#00bcd4]/5']">
                                     <option value="" disabled>Select Thana</option>
                                     <option v-for="thana in thanas" :key="thana.id" :value="thana.id">{{ thana.name }}</option>
                                 </select>
                                 <p v-if="errors.thana_id" class="text-[9px] font-bold text-rose-500 mt-1.5 tracking-tight ml-1">{{ errors.thana_id[0] }}</p>
                             </div>
                             <div class="sm:col-span-2 md:col-span-3 flex flex-col space-y-1.5">
                                 <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">Street Address</label>
                                 <textarea v-model="form.address" rows="2" :class="['w-full rounded-xl border px-4 py-3 text-[12px] font-bold outline-none transition-all bg-white resize-none', errors.address ? 'border-rose-300 text-rose-600 focus:ring-rose-500/5' : 'border-slate-200 text-slate-600 focus:border-[#00bcd4] focus:ring-4 focus:ring-[#00bcd4]/5']"></textarea>
                                 <p v-if="errors.address" class="text-[9px] font-bold text-rose-500 mt-1.5 tracking-tight ml-1">{{ errors.address[0] }}</p>
                             </div>
                        </div>
                    </div>
                  </div> 

                  <!-- Tab 2: NID Verification -->
                  <div v-show="activeTab === 'nid'" class="space-y-10 relative z-10 px-0 sm:px-2 transition-all duration-300">
                      <div>
                          <div class="flex items-center mb-8">
                             <div class="w-2 h-2 bg-slate-200 rounded-full mr-3"></div>
                             <h4 class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Document Vault</h4>
                          </div>
                      
                          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                              <div class="flex flex-col space-y-3">
                                  <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">NID Front Side</label>
                                  <div 
                                    :class="['relative aspect-[1.6/1] border-2 border-dashed rounded-2xl flex flex-col items-center justify-center transition-all duration-300 cursor-pointer overflow-hidden shadow-inner group', errors.nid_front ? 'border-rose-300 bg-rose-50/20' : 'border-slate-100 bg-slate-50/50 hover:border-[#00bcd4]/30 hover:bg-[#00bcd4]/5']"
                                    @click="$refs.nidFrontInput.click()"
                                  >
                                    <template v-if="!form.nid_front_file && !user?.nid_front">
                                         <svg class="w-6 h-6 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                    </template>
                                    <template v-else>
                                         <img :src="form.nid_front_file ? getFilePreview(form.nid_front_file) : `/storage/${user.nid_front}`" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                         <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[1px]">
                                             <span class="bg-white/90 text-slate-900 text-[8px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg shadow-sm">Replace</span>
                                         </div>
                                    </template>
                                   </div>
                                  <p v-if="errors.nid_front" class="text-[9px] font-bold text-rose-500 mt-1.5 tracking-tight ml-1">{{ errors.nid_front[0] }}</p>
                                  <input type="file" ref="nidFrontInput" class="hidden" @change="handleFile($event, 'nid_front')" accept="image/*">
                              </div>

                              <div class="flex flex-col space-y-3">
                                  <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">NID Back Side</label>
                                  <div 
                                    :class="['relative aspect-[1.6/1] border-2 border-dashed rounded-2xl flex flex-col items-center justify-center transition-all duration-300 cursor-pointer overflow-hidden shadow-inner group', errors.nid_back ? 'border-rose-300 bg-rose-50/20' : 'border-slate-100 bg-slate-50/50 hover:border-[#00bcd4]/30 hover:bg-[#00bcd4]/5']"
                                    @click="$refs.nidBackInput.click()"
                                  >
                                    <template v-if="!form.nid_back_file && !user?.nid_back">
                                         <svg class="w-6 h-6 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                    </template>
                                    <template v-else>
                                         <img :src="form.nid_back_file ? getFilePreview(form.nid_back_file) : `/storage/${user.nid_back}`" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                         <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[1px]">
                                             <span class="bg-white/90 text-slate-900 text-[8px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg shadow-sm">Replace</span>
                                         </div>
                                    </template>
                                  </div>
                                  <p v-if="errors.nid_back" class="text-[9px] font-bold text-rose-500 mt-1.5 tracking-tight ml-1">{{ errors.nid_back[0] }}</p>
                                  <input type="file" ref="nidBackInput" class="hidden" @change="handleFile($event, 'nid_back')" accept="image/*">
                              </div>
                          </div>
                      </div>
                  </div> 

                  <!-- Tab 3: Security -->
                  <div v-show="activeTab === 'password'" class="relative z-10 px-0 sm:px-2 transition-all duration-300">
                       <div class="flex items-center mb-8">
                             <div class="w-2 h-2 bg-slate-200 rounded-full mr-3"></div>
                             <h4 class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Access Key Management</h4>
                       </div>
                      
                      <div class="max-w-xl mx-auto space-y-6 bg-slate-50/20 p-8 rounded-3xl border border-slate-100">
                           <div class="flex flex-col space-y-1.5 focus-within:translate-x-1 transition-transform">
                               <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">Current Password</label>
                               <input v-model="passwordForm.current_password" type="password" :class="['w-full rounded-xl border px-4 py-2.5 text-[12px] font-bold outline-none transition-all font-mono tracking-widest', errors.current_password ? 'border-rose-300 text-rose-600 bg-rose-50/20' : 'border-slate-200 text-slate-600 bg-white focus:border-[#00bcd4] focus:ring-4 focus:ring-[#00bcd4]/5 shadow-sm']">
                               <p v-if="errors.current_password" class="text-[9px] font-bold text-rose-500 mt-1.5 tracking-tight ml-1">{{ errors.current_password[0] }}</p>
                           </div>
                          
                           <div class="grid grid-cols-1 md:grid-cols-2 gap-6" v-if="passwordForm.current_password.length > 0">
                               <div class="flex flex-col space-y-1.5">
                                   <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">New Key</label>
                                   <input v-model="passwordForm.password" type="password" :class="['w-full rounded-xl border px-4 py-2.5 text-[12px] font-bold outline-none transition-all font-mono tracking-widest shadow-sm', errors.password ? 'border-rose-300 text-rose-600' : 'border-slate-200 text-slate-600 focus:border-[#00bcd4]']">
                                   <p v-if="errors.password" class="text-[9px] font-bold text-rose-500 mt-1.5 tracking-tight ml-1">{{ errors.password[0] }}</p>
                               </div>
                               <div class="flex flex-col space-y-1.5">
                                   <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">Verify Key</label>
                                   <input v-model="passwordForm.password_confirmation" type="password" :class="['w-full rounded-xl border px-4 py-2.5 text-[12px] font-bold outline-none transition-all font-mono tracking-widest shadow-sm', errors.password ? 'border-rose-300 text-rose-600' : 'border-slate-200 text-slate-600 focus:border-[#00bcd4]']">
                               </div>
                           </div>

                           <div class="flex justify-end mt-4">
                               <button @click="submitPasswordChange" :disabled="loading" class="px-8 py-2.5 bg-slate-900 text-white font-black rounded-xl text-[10px] uppercase tracking-widest hover:bg-rose-500 transition-all duration-300 shadow-sm disabled:opacity-30">
                                   Update Security
                                </button>
                           </div>
                      </div>
                  </div>

                  <!-- Tab 4: Phone -->
                  <div v-show="activeTab === 'mobile'" class="relative z-10 px-0 sm:px-2 transition-all duration-300">
                      <div class="flex items-center mb-8">
                             <div class="w-2 h-2 bg-slate-200 rounded-full mr-3"></div>
                             <h4 class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Device Linking</h4>
                       </div>
                      
                      <div class="max-w-xl mx-auto space-y-8 bg-slate-50/20 p-8 rounded-3xl border border-slate-100">
                           <div class="flex flex-col space-y-1.5 focus-within:translate-x-1 transition-transform">
                               <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">Identity Password</label>
                               <input v-model="mobileForm.current_password" type="password" :class="['w-full rounded-xl border px-4 py-2.5 text-[12px] font-bold outline-none transition-all font-mono tracking-widest', errors.current_password ? 'border-rose-300 text-rose-600 bg-rose-50/20' : 'border-slate-200 text-slate-600 bg-white focus:border-[#00bcd4] focus:ring-4 focus:ring-[#00bcd4]/5 shadow-sm']">
                               <p v-if="errors.current_password" class="text-[9px] font-bold text-rose-500 mt-1.5 tracking-tight ml-1">{{ errors.current_password[0] }}</p>
                           </div>
                          
                           <div class="flex flex-col space-y-1.5">
                               <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">Mobile Subscriber Number</label>
                               <div class="flex items-center space-x-2">
                                   <button @click.stop="showCountryDropdown = !showCountryDropdown" :class="['flex items-center justify-between min-w-[100px] px-3 py-2.5 border rounded-xl text-[12px] font-bold transition-all relative', errors.country_code ? 'border-rose-300 text-rose-600 bg-rose-50/10' : 'bg-white border-slate-200 text-slate-700 hover:border-[#00bcd4]']">
                                       <span>{{ selectedCountry.flag }} {{ selectedCountry.code }}</span>
                                       <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                        <div v-if="showCountryDropdown" class="absolute left-0 top-full mt-2 w-56 bg-white border border-slate-100 rounded-xl shadow-2xl z-[100] py-1.5">
                                            <div class="max-h-56 overflow-y-auto px-1 space-y-0.5">
                                                <button v-for="c in countries" :key="c.code" @click="selectCountry(c)" class="flex items-center gap-3 w-full px-3 py-2 text-[10px] font-bold text-left hover:bg-slate-50 rounded-lg transition-all">
                                                    <span>{{ c.flag }}</span>
                                                    <span class="flex-1 text-slate-400 text-[8px]">{{ c.name }}</span>
                                                    <span>{{ c.code }}</span>
                                                </button>
                                            </div>
                                        </div>
                                   </button>
                                   <input v-model="mobileForm.mobile" type="text" :class="['flex-1 rounded-xl border px-4 py-2.5 text-[12px] font-bold outline-none transition-all font-mono tracking-wider shadow-sm', errors.mobile ? 'border-rose-300 text-rose-600' : 'border-slate-200 text-slate-700 focus:border-[#00bcd4] focus:ring-4 focus:ring-[#00bcd4]/5']">
                               </div>
                               <p v-if="errors.mobile" class="text-[9px] font-bold text-rose-500 mt-1.5 tracking-tight ml-1">{{ errors.mobile[0] }}</p>
                               <p v-if="errors.country_code" class="text-[9px] font-bold text-rose-500 mt-1.5 tracking-tight ml-1">{{ errors.country_code[0] }}</p>
                           </div>

                           <div class="flex justify-end pt-2">
                               <button @click="submitMobileChange" :disabled="loading" class="px-10 py-2.5 bg-slate-900 text-white font-black rounded-xl text-[10px] uppercase tracking-widest hover:bg-[#00bcd4] transition-all shadow-sm disabled:opacity-30">
                                   Update Mobile
                               </button>
                           </div>
                      </div>
                  </div>

                  <!-- Footer Actions -->
                  <div class="mt-12 pt-8 border-t border-slate-50 flex items-center justify-end relative z-10" v-if="!['password', 'mobile'].includes(activeTab)">
                      <button @click="submitForm" :disabled="loading" class="px-12 py-2.5 bg-[#00bcd4] text-white font-black rounded-xl text-[10px] uppercase tracking-widest shadow-lg shadow-[#00bcd4]/10 hover:shadow-[#00bcd4]/25 hover:-translate-y-0.5 transition-all disabled:opacity-50">
                          {{ loading ? 'Processing...' : 'Update Information' }}
                      </button>
                  </div>
             </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const loading = ref(false);
const user = ref(JSON.parse(localStorage.getItem('user')) || null);
const divisions = ref([]);
const districts = ref([]);
const thanas = ref([]);
const errors = ref({});
const activeTab = ref('personal');

const form = ref({
    name: '',
    email: '',
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

const userInitials = computed(() => {
    if (!form.value.name && !user.value?.name) return '??';
    const targetName = form.value.name || user.value?.name;
    const names = targetName.trim().split(/\s+/);
    if (names.length >= 2) return (names[0][0] + names[names.length - 1][0]).toUpperCase();
    return targetName.substring(0, 2).toUpperCase();
});

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

const getFilePreview = (file) => file ? URL.createObjectURL(file) : null;

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
        thanas.value = [];
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
        if (!thanas.value.find(t => t.id === form.value.thana_id)) form.value.thana_id = '';
    } catch (e) {
        console.error(e);
    }
};

const fetchUser = async () => {
    try {
        const res = await axios.get('/api/user');
        user.value = res.data;
        localStorage.setItem('user', JSON.stringify(res.data));
        
        form.value.name = user.value.name;
        form.value.email = user.value.email;
        form.value.nid_number = user.value.nid_number;
        form.value.division_id = user.value.division_id;
        form.value.address = user.value.address;
        form.value.company_name = user.value.company_name;
        form.value.profile_pic_url = user.value.profile_pic_url;

        if(form.value.division_id) await fetchDistricts();
        if(user.value.district_id) {
            form.value.district_id = user.value.district_id;
            await fetchThanas();
        }
        if(user.value.thana_id) form.value.thana_id = user.value.thana_id;

         if (user.value.mobile) {
             const found = countries.value.find(c => c.code === user.value.country_code);
             if (found) {
                 selectedCountry.value = found;
                 mobileForm.value.mobile = user.value.mobile;
             } else {
                 // Fallback if country_code is not in the predefined list but mobile exists
                 mobileForm.value.mobile = user.value.mobile;
             }
         }
    } catch (e) {
        console.error(e);
    }
};

const resetForm = () => fetchUser();

const submitForm = async () => {
    loading.value = true;
    errors.value = {};
    const formData = new FormData();
    Object.keys(form.value).forEach(key => {
        if(form.value[key] && !key.endsWith('_url') && !key.endsWith('_file')) {
            formData.append(key, form.value[key]);
        }
    });
    if (form.value.nid_front_file) formData.append('nid_front', form.value.nid_front_file);
    if (form.value.nid_back_file) formData.append('nid_back', form.value.nid_back_file);
    if (form.value.profile_pic_file) formData.append('profile_pic', form.value.profile_pic_file);
    
    try {
        const res = await axios.post('/api/user/update', formData);
        user.value = res.data.user;
        localStorage.setItem('user', JSON.stringify(res.data.user));
        Swal.fire({ icon: 'success', title: 'Identity Updated', text: 'Core sync successful.', confirmButtonColor: '#00bcd4' });
    } catch (e) {
        if (e.response && e.response.status === 422) errors.value = e.response.data.errors;
    } finally {
        loading.value = false;
    }
};

const submitPasswordChange = async () => {
    loading.value = true;
    errors.value = {};
    try {
        await axios.post('/api/user/update-password', passwordForm.value);
        Swal.fire({ icon: 'success', title: 'Security Updated', text: 'Key reset confirmed.', confirmButtonColor: '#00bcd4' });
        passwordForm.value = { current_password: '', password: '', password_confirmation: '' };
    } catch (e) {
        if (e.response && e.response.status === 422) errors.value = e.response.data.errors;
    } finally {
        loading.value = false;
    }
};

const submitMobileChange = async () => {
    loading.value = true;
    errors.value = {};
    try {
        await axios.post('/api/user/update-mobile', { 
            ...mobileForm.value, 
            country_code: selectedCountry.value.code 
        });
        Swal.fire({ icon: 'success', title: 'Phone Linked', text: 'Device connection updated.', confirmButtonColor: '#00bcd4' });
        mobileForm.value.current_password = '';
        fetchUser();
    } catch (e) {
        if (e.response && e.response.status === 422) errors.value = e.response.data.errors;
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchDivisions();
    fetchUser();
    window.addEventListener('click', () => { showCountryDropdown.value = false; });
});
</script>

<style scoped>
input, select, textarea {
    transition: all 0.2s ease;
}
</style>
