<template>
    <div class="p-6 bg-[#eff3f6] min-h-screen font-sans">
        <!-- Page Title -->
        <div class="flex items-center gap-2 mb-4 text-gray-700">
            <svg class="w-5 h-5 text-sky-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
            <h1 class="text-xl font-bold">Client</h1>
            <span class="text-xs text-gray-400 font-medium">Add New Client</span>
            <div class="flex-grow"></div>
            <div class="text-[10px] text-gray-400">Client / Add New Client</div>
        </div>

        <form @submit.prevent="submitClient" class="space-y-6">
            
            <!-- 1. Personal Information -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-[#0f3443] px-4 py-2 text-white">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span class="font-bold text-xs">Personal Information</span>
                    </div>
                    <div class="text-[9px] text-gray-300 ml-6">Fill Up All Required(✱) Field Data</div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Top Row: Profile Pic, Primary Info, Remarks -->
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        <!-- Profile Pic -->
                        <div class="md:col-span-2 flex flex-col pt-2">
                             <label class="text-[10px] font-bold text-gray-400 uppercase mb-4">PROFILE PICTURE</label>
                             <div class="relative group w-36 h-36 mx-auto">
                                <div class="w-full h-full bg-gray-100 rounded-full border-2 border-gray-100 flex items-center justify-center overflow-hidden shadow-inner">
                                    <img v-if="previews.profile" :src="previews.profile" class="w-full h-full object-cover">
                                    <svg v-else class="w-20 h-20 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                                </div>
                                <button type="button" @click="$refs.profileInput.click()" class="absolute top-2 right-2 p-1.5 bg-[#0f3443] text-white rounded-full border-2 border-white shadow hover:scale-110 transition-transform">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <input type="file" @change="handleFile($event, 'profile_pic')" class="hidden" ref="profileInput">
                             </div>
                        </div>

                        <!-- Name & Occupation -->
                        <div class="md:col-span-5 space-y-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">CUSTOMER NAME <span class="text-red-500">✱</span></label>
                                <input v-model="form.name" type="text" class="input-pill-light" required>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">OCCUPATION</label>
                                <input v-model="form.occupation" type="text" class="input-pill-light">
                            </div>
                        </div>

                        <!-- Remarks -->
                        <div class="md:col-span-5 relative">
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">REMARKS/SPECIAL NOTE</label>
                            <textarea v-model="form.remarks" class="input-pill-light h-32 py-4 resize-none"></textarea>
                            <div class="absolute bottom-3 right-3 flex gap-2 text-sky-400 opacity-50">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"></path></svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Middle Row: NID, Pictures, Details -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                        <!-- Column 1: NID, Gender, DOB -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">NID/BIRTH CERTIFICATE NO</label>
                                <input v-model="form.nid" type="text" class="input-pill-light">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">GENDER</label>
                                <select v-model="form.gender" class="select-pill-light">
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">DATE OF BIRTH</label>
                                <input v-model="form.dob" type="date" class="input-pill-light">
                            </div>
                        </div>

                        <!-- Column 2: NID Picture -->
                        <div class="flex flex-col items-center">
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 self-start">NID/BIRTH CERTIFICATE PICTURE</label>
                            <div class="relative w-32 h-32">
                                <div class="w-full h-full bg-gray-50 rounded-full border border-gray-200 flex items-center justify-center overflow-hidden">
                                     <img v-if="previews.nid" :src="previews.nid" class="w-full h-full object-cover">
                                     <svg v-else class="w-20 h-20 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <button type="button" @click="$refs.nidInput.click()" class="absolute top-1 right-1 p-1.5 bg-[#0f3443] text-white rounded-full border-2 border-white shadow">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <input type="file" @change="handleFile($event, 'nid_pic')" class="hidden" ref="nidInput">
                            </div>
                        </div>

                        <!-- Column 3: Reg Details -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">REGISTRATION FORM NO</label>
                                <input v-model="form.res_form_no" type="text" class="input-pill-light">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">FATHER NAME</label>
                                <input v-model="form.father_name" type="text" class="input-pill-light">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">MOTHER NAME</label>
                                <input v-model="form.mother_name" type="text" class="input-pill-light">
                            </div>
                        </div>

                        <!-- Column 4: Reg Form Picture -->
                        <div class="flex flex-col items-center">
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 self-start">REGISTRATION FORM PICTURE</label>
                            <div class="relative w-32 h-32">
                                <div class="w-full h-full bg-gray-50 rounded-full border border-gray-200 flex items-center justify-center overflow-hidden">
                                     <img v-if="previews.res_form" :src="previews.res_form" class="w-full h-full object-cover">
                                     <svg v-else class="w-20 h-20 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <button type="button" @click="$refs.resInput.click()" class="absolute top-1 right-1 p-1.5 bg-[#0f3443] text-white rounded-full border-2 border-white shadow">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <input type="file" @change="handleFile($event, 'res_form_pic')" class="hidden" ref="resInput">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Contact Information -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-[#0f3443] px-4 py-2 text-white">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span class="font-bold text-xs">Contact Information</span>
                    </div>
                    <div class="text-[9px] text-gray-300 ml-6">Fill Up All Required(✱) Field Data</div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Col 1 -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">MAP LATITUDE</label>
                                <input v-model="form.lat" type="text" class="input-pill-light" placeholder="Example: 23.8103">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">MAP LONGITUDE</label>
                                <input v-model="form.long" type="text" class="input-pill-light" placeholder="Example: 90.4125">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">FACEBOOK URL</label>
                                <input v-model="form.facebook_url" type="text" class="input-pill-light" placeholder="https://facebook.com/username">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">LINKEDIN URL</label>
                                <input v-model="form.linkedin_url" type="text" class="input-pill-light" placeholder="https://linkedin.com/in/username">
                            </div>
                        </div>
                        <!-- Col 2 -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">MOBILE NUMBER <span class="text-red-500 font-bold">✱</span></label>
                                <input v-model="form.mobile" type="text" class="input-pill-light" required placeholder="017XXXXXXXX">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">PHONE NUMBER</label>
                                <input v-model="form.phone" type="text" class="input-pill-light" placeholder="02XXXXXXX">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">EMAIL ADDRESS</label>
                                <input v-model="form.email" type="email" class="input-pill-light" placeholder="example@mail.com">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">TWITTER URL</label>
                                <input v-model="form.twitter_url" type="text" class="input-pill-light" placeholder="https://twitter.com/username">
                            </div>
                        </div>
                        <!-- Col 3 -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">DISTRICT</label>
                                <select v-model="form.district" class="select-pill-light">
                                    <option value="">Select</option>
                                    <option v-for="d in dropdowns.districts" :key="d" :value="d">{{ d }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">UPAZILA/THANA</label>
                                <select v-model="form.upazila" class="select-pill-light">
                                    <option value="">Select</option>
                                    <option v-for="u in dropdowns.upazilas" :key="u" :value="u">{{ u }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">ROAD NUMBER</label>
                                <input v-model="form.road_no" type="text" class="input-pill-light">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">HOUSE NUMBER</label>
                                <input v-model="form.house_no" type="text" class="input-pill-light">
                            </div>
                        </div>
                        <!-- Col 4 -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">PRESENT ADDRESS</label>
                                <textarea v-model="form.address" rows="3" class="input-pill-light h-20 py-2 resize-none" placeholder="Village, House, Area"></textarea>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">PERMANENT ADDRESS</label>
                                <textarea v-model="form.permanent_address" rows="3" class="input-pill-light h-20 py-2 resize-none" :disabled="form.is_same_address"></textarea>
                            </div>
                            <div class="flex items-center gap-2 mt-2">
                                <label class="text-[10px] font-bold text-gray-500 cursor-pointer flex items-center gap-2">
                                    Same As Present Address?
                                    <input v-model="form.is_same_address" type="checkbox" class="rounded text-sky-500 focus:ring-sky-500 h-4 w-4">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Network & Product Information -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-[#0f3443] px-4 py-2 text-white">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span class="font-bold text-xs">Network & Product Information</span>
                    </div>
                    <div class="text-[9px] text-gray-300 ml-6">Fill Up All Required(✱) Field Data</div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Row 1 -->
                         <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">SERVER <span class="text-red-500 font-bold">✱</span></label>
                            <select v-model="form.server_id" class="select-pill-light" required>
                                <option value="">Select</option>
                                <option v-for="router in dropdowns.servers" :key="router.id" :value="router.id">{{ router.name }} ({{ router.ip_address }})</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">PROTOCOL TYPE <span class="text-red-500 font-bold">✱</span></label>
                            <select v-model="form.protocol_type" class="select-pill-light" required>
                                <option value="">Select</option>
                                <option value="IPv4">IPv4</option>
                                <option value="IPv6">IPv6</option>
                                <option value="Dual Stack">Dual Stack</option>
                            </select>
                        </div>
                        <div>
                             <div class="flex justify-between items-center mb-1">
                                <label class="text-[10px] font-bold text-gray-400 uppercase">ZONE <span class="text-red-500 font-bold">✱</span></label>
                                <button type="button" @click="$router.push('/config/zones')" class="text-[9px] bg-[#0f3443] text-white px-2 py-0.5 rounded flex items-center gap-1 font-bold">+ ZONE</button>
                             </div>
                            <select v-model="form.zone_id" @change="fetchSubZones" class="select-pill-light" required>
                                <option value="">Select</option>
                                <option v-for="zone in zones" :key="zone.id" :value="zone.id">{{ zone.name }}</option>
                            </select>
                        </div>

                        <!-- Row 2 -->
                        <div>
                             <div class="flex justify-between items-center mb-1">
                                <label class="text-[10px] font-bold text-gray-400 uppercase">SUB ZONE</label>
                                <button type="button" @click="$router.push('/config/sub-zones')" class="text-[9px] bg-[#0f3443] text-white px-2 py-0.5 rounded flex items-center gap-1 font-bold">+ SUB ZONE</button>
                             </div>
                            <select v-model="form.sub_zone_id" class="select-pill-light">
                                <option value="">Select</option>
                                <option v-for="sub in subZones" :key="sub.id" :value="sub.id">{{ sub.name }}</option>
                            </select>
                        </div>
                        <div>
                             <div class="flex justify-between items-center mb-1">
                                <label class="text-[10px] font-bold text-gray-400 uppercase">BOX</label>
                                <button type="button" @click="$router.push('/config/boxes')" class="text-[9px] bg-[#0f3443] text-white px-2 py-0.5 rounded flex items-center gap-1 font-bold">+ BOX</button>
                             </div>
                            <select v-model="form.box_id" class="select-pill-light">
                                <option value="">Select</option>
                                <option v-for="box in boxes" :key="box.id" :value="box.id">{{ box.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">CONNECTION TYPE <span class="text-red-500 font-bold">✱</span></label>
                            <select v-model="form.connection_type" class="select-pill-light" required>
                                <option value="">Select</option>
                                <option v-for="type in dropdowns.connection_types" :key="type.id" :value="type.id">{{ type.name }}</option>
                                <option v-if="dropdowns.connection_types.length === 0" value="pppoe">PPPoE</option>
                                <option v-if="dropdowns.connection_types.length === 0" value="static">Static IP</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                        <!-- Row 3 -->
                        <div>
                             <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">CABLE REQUIREMENT IN METRE</label>
                             <input v-model="form.cable_meter" type="text" class="input-pill-light" placeholder="Example: 100">
                        </div>
                        <div>
                             <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">FIBER CODE</label>
                             <input v-model="form.fiber_code" type="text" class="input-pill-light" placeholder="Example: 121">
                        </div>
                        <div>
                             <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">NUMBER OF CORE</label>
                             <input v-model="form.core_no" type="text" class="input-pill-light" placeholder="Example: 2">
                        </div>
                        <div>
                             <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">CORE COLOR</label>
                             <input v-model="form.core_color" type="text" class="input-pill-light" placeholder="Example: Red">
                        </div>

                        <!-- Row 4 -->
                        <div>
                             <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">DEVICE</label>
                             <select v-model="form.device_id" class="select-pill-light">
                                <option value="">Select</option>
                                <option v-for="d in dropdowns.devices" :key="d" :value="d">{{ d }}</option>
                             </select>
                        </div>
                        <div>
                             <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">DEVICE MAC/SERIAL NO</label>
                             <input v-model="form.device_mac_serial" type="text" class="input-pill-light" placeholder="FF:FF:FF:FF:FF:FF">
                        </div>
                        <div>
                             <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">VENDOR</label>
                             <select v-model="form.vendor_id" class="select-pill-light">
                                <option value="">Select</option>
                                <option v-for="v in dropdowns.vendors" :key="v" :value="v">{{ v }}</option>
                             </select>
                        </div>
                        <div>
                             <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">PURCHASE DATE</label>
                             <input v-model="form.device_purchase_date" type="date" class="input-pill-light">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Service Information -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-[#0f3443] px-4 py-2 text-white">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span class="font-bold text-xs">Service Information</span>
                    </div>
                    <div class="text-[9px] text-gray-300 ml-6">Fill Up All Required(✱) Field Data</div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Row 1 -->
                        <div>
                             <div class="flex justify-between items-center mb-1">
                                <label class="text-[10px] font-bold text-gray-400 uppercase">PACKAGE <span class="text-red-500 font-bold">✱</span></label>
                                <button type="button" @click="$router.push('/config/packages')" class="text-[9px] bg-[#0f3443] text-white px-2 py-0.5 rounded flex items-center gap-1 font-bold">+ PACKAGE</button>
                             </div>
                            <select v-model="form.package_id" class="select-pill-light" required>
                                <option value="">Select</option>
                                <option v-for="pkg in packages" :key="pkg.id" :value="pkg.id">{{ pkg.name }} ({{ pkg.price }} BDT)</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">PROFILE <span class="text-red-500 font-bold">✱</span></label>
                             <select v-model="form.service_profile_id" class="select-pill-light" required>
                                <option value="">Select</option>
                                <option v-for="prof in dropdowns.service_profiles" :key="prof.id" :value="prof.id">{{ prof.name }} ({{ prof.bandwidth_down }}M/{{ prof.bandwidth_up }}M)</option>
                             </select>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="text-[10px] font-bold text-gray-400 uppercase">CLIENT TYPE <span class="text-red-500 font-bold">✱</span></label>
                                <button type="button" @click="$router.push('/config/client-types')" class="text-[9px] bg-[#0f3443] text-white px-2 py-0.5 rounded flex items-center gap-1 font-bold">+ CLIENT TYPE</button>
                             </div>
                             <select v-model="form.client_type" class="select-pill-light" required>
                                <option value="">Select</option>
                                <option v-for="ct in dropdowns.client_types" :key="ct.id" :value="ct.name">{{ ct.name }}</option>
                                <option v-if="dropdowns.client_types.length === 0" value="Home">Home</option>
                                <option v-if="dropdowns.client_types.length === 0" value="Office">Office</option>
                             </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">BILLING STATUS <span class="text-red-500 font-bold">✱</span></label>
                            <select v-model="form.payment_status" class="select-pill-light" required>
                                <option value="">Select</option>
                                <option value="paid">Paid</option>
                                <option value="unpaid">Unpaid</option>
                            </select>
                        </div>

                        <!-- Row 2 -->
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">USERNAME/IP <span class="text-red-500 font-bold">✱</span></label>
                            <input v-model="form.username" type="text" class="input-pill-light" required placeholder="example_user">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">REMOTE ADDRESS</label>
                            <input v-model="form.remote_address" type="text" class="input-pill-light" placeholder="192.168.X.X">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">PASSWORD <span class="text-red-500 font-bold">✱</span></label>
                            <input v-model="form.password" type="password" class="input-pill-light" required placeholder="********">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                             <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">JOINING DATE <span class="text-red-500 font-bold">✱</span></label>
                                <input v-model="form.joining_date" type="date" class="input-pill-light" required>
                                <span class="text-[8px] text-red-400 font-bold mt-1 block uppercase leading-none">(NO RELATION IN BILLING)</span>
                             </div>
                             <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">MONTHLY BILL <span class="text-red-500 font-bold">✱</span></label>
                                <div class="relative">
                                    <input v-model="form.monthly_fee" type="number" class="input-pill-light pr-8" placeholder="Provide client monthly bill" required>
                                    <svg class="w-3.5 h-3.5 absolute right-3 top-2 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </div>
                             </div>
                        </div>

                        <!-- Row 3 -->
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">BILLING START MONTH <span class="text-sky-500 cursor-help">ℹ</span></label>
                            <input v-model="form.billing_start_month" type="month" class="input-pill-light">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">EXPIRE DATE/DAY <span class="text-red-500 font-bold">✱</span></label>
                            <select v-model="form.expire_date" class="select-pill-light" required>
                                <option value="">Select Day</option>
                                <option v-for="day in 31" :key="day" :value="day">{{ day }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">REFERENCE BY</label>
                            <input v-model="form.ref_by" type="text" class="input-pill-light" placeholder="Referral name">
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-4">
                            <div class="flex flex-col">
                                <label class="text-[10px] font-bold text-[#ffae1a] uppercase mb-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                                    IS VIP CLIENT?
                                </label>
                                <label class="flex items-center gap-2 text-[11px] font-bold text-gray-600 cursor-pointer border border-gray-100 bg-[#f3f6f9] rounded-2xl px-3 py-1.5 w-fit">
                                    <input v-model="form.is_vip" type="checkbox" class="rounded text-sky-500 h-4 w-4">
                                    Click to Set
                                </label>
                            </div>
                             <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">CONNECTED BY <span class="text-sky-400 cursor-help" title="Select Employee">ℹ</span></label>
                                <select v-model="form.employee_id" class="select-pill-light">
                                    <option value="">Select Employee</option>
                                    <option v-for="emp in dropdowns.employees" :key="emp" :value="emp">{{ emp }}</option>
                                </select>
                             </div>
                        </div>

                        <!-- Row 4 -->
                        <div class="md:col-span-1">
                             <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">AFFILIATOR</label>
                             <select v-model="form.affiliator_id" class="select-pill-light">
                                <option value="">Select</option>
                                <option v-for="aff in dropdowns.affiliators" :key="aff" :value="aff">{{ aff }}</option>
                             </select>
                        </div>
                    </div>

                    <!-- Footer Checkbox -->
                    <div class="flex justify-start mt-8">
                         <label class="flex items-center gap-2 text-xs font-bold text-gray-500 cursor-pointer">
                            <input v-model="form.send_greeting_sms" type="checkbox" class="rounded text-sky-500 h-4 w-4">
                            Send Greetings SMS?
                        </label>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between items-center pt-4 pb-12">
                <button type="button" @click="$router.push('/client/all')" class="px-8 py-2 bg-gray-400 text-white font-bold rounded-full hover:bg-gray-500 transition-colors text-sm uppercase tracking-wide">
                    Go To List
                </button>
                <button type="submit" :disabled="loading" class="px-8 py-2 bg-[#0f3443] text-white font-bold rounded-lg hover:bg-[#0a2530] shadow shadow-[#0f3443]/20 transition-all active:scale-95 disabled:opacity-50 flex items-center gap-2 text-sm uppercase tracking-wide">
                    <span v-if="loading" class="inline-block animate-spin w-4 h-4 border-2 border-white border-t-transparent rounded-full"></span>
                    {{ loading ? 'Saving...' : 'Save & Exit' }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import axios from 'axios';

const router = useRouter();
const loading = ref(false);

const packages = ref([]);
const zones = ref([]);
const subZones = ref([]);
const boxes = ref([]);

const dropdowns = ref({
    districts: ['Dhaka', 'Chittagong', 'Sylhet', 'Rajshahi', 'Khulna', 'Barisal', 'Rangpur', 'Mymensingh'],
    upazilas: ['Badda', 'Gulshan', 'Uttara', 'Mirpur', 'Dhanmondi', 'Mohammadpur', 'Rampura', 'Motijheel'],
    devices: ['ONU - HG8245H', 'ONU - ZTE F660', 'ONU - VSOLE', 'Router - TP-Link C6', 'Router - Tenda F3', 'Switch - Cisco 2960'],
    vendors: ['Huawei', 'ZTE', 'TP-Link', 'Tenda', 'Cisco', 'MikroTik', 'VSOLE'],
    employees: ['Admin User', 'Technician Area 1', 'Support Lead', 'Field Staff B'],
    affiliators: ['Direct Sales', 'Partner A', 'Referral Network'],
    servers: [],
    service_profiles: [],
    client_types: [],
    connection_types: []
});

const previews = ref({
    profile: null,
    nid: null,
    res_form: null
});

const form = ref({
    // Personal Info
    name: '',
    occupation: '',
    remarks: '',
    nid: '',
    gender: '',
    dob: '',
    res_form_no: '',
    father_name: '',
    mother_name: '',
    profile_pic: null,
    nid_pic: null,
    res_form_pic: null,

    // Contact Info
    lat: '',
    long: '',
    facebook_url: '',
    linkedin_url: '',
    twitter_url: '',
    mobile: '',
    phone: '',
    email: '',
    district: '',
    upazila: '',
    road_no: '',
    house_no: '',
    address: '',
    permanent_address: '',
    is_same_address: false,

    // Network & Product
    server_id: '',
    protocol_type: '',
    zone_id: '',
    sub_zone_id: '',
    box_id: '',
    connection_type: 'pppoe',
    cable_meter: '',
    fiber_code: '',
    core_no: '',
    core_color: '',
    device_id: '',
    device_mac_serial: '',
    vendor_id: '',
    device_purchase_date: '',

    // Service Info
    package_id: '',
    service_profile_id: '',
    client_type: 'Home',
    payment_status: 'unpaid',
    username: '',
    remote_address: '',
    password: '',
    joining_date: new Date().toISOString().slice(0, 10),
    monthly_fee: 0,
    billing_start_month: new Date().toISOString().slice(0, 7),
    expire_date: '',
    ref_by: '',
    is_vip: false,
    employee_id: '',
    affiliator_id: '',
    send_greeting_sms: false
});

// Sync permanent address if checkbox is checked
watch(() => form.value.is_same_address, (val) => {
    if (val) {
        form.value.permanent_address = form.value.address;
    }
});

watch(() => form.value.address, (val) => {
    if (form.value.is_same_address) {
        form.value.permanent_address = val;
    }
});

const loadData = async () => {
    try {
        const [pkgRes, zoneRes, serverRes, profileRes, typeRes, connRes] = await Promise.all([
            axios.get('/api/config/packages?no_paginate=true'),
            axios.get('/api/config/zones?no_paginate=true'),
            axios.get('/api/config/mikrotik-routers?no_paginate=true'),
            axios.get('/api/config/service-profiles?no_paginate=true'),
            axios.get('/api/config/client-types?no_paginate=true'),
            axios.get('/api/config/connection-types?no_paginate=true')
        ]);
        packages.value = pkgRes.data;
        zones.value = zoneRes.data;
        dropdowns.value.servers = serverRes.data;
        dropdowns.value.service_profiles = profileRes.data;
        dropdowns.value.client_types = typeRes.data;
        dropdowns.value.connection_types = connRes.data;
    } catch (error) {
        console.error('Failed to load dropdown data', error);
    }
};

const fetchSubZones = async () => {
    if (!form.value.zone_id) {
        subZones.value = [];
        boxes.value = [];
        return;
    }
    try {
        const res = await axios.get(`/api/config/sub-zones?zone_id=${form.value.zone_id}&no_paginate=true`);
        subZones.value = res.data;
        const boxRes = await axios.get(`/api/config/boxes?zone_id=${form.value.zone_id}&no_paginate=true`);
        boxes.value = boxRes.data;
    } catch (error) {
        console.error('Failed to load subzones/boxes', error);
    }
};

const handleFile = (event, field) => {
    const file = event.target.files[0];
    if (file) {
        form.value[field] = file;
        const previewMap = {
            'profile_pic': 'profile',
            'nid_pic': 'nid',
            'res_form_pic': 'res_form'
        };
        if (previewMap[field]) {
            previews.value[previewMap[field]] = URL.createObjectURL(file);
        }
    }
};

const submitClient = async () => {
    loading.value = true;
    const formData = new FormData();
    Object.keys(form.value).forEach(key => {
        if (form.value[key] !== null && form.value[key] !== undefined) {
            // Convert boolean to numeric for PHP backend if needed, 
            // but controller already handles boolean strings.
            formData.append(key, form.value[key]);
        }
    });

    try {
        await axios.post('/api/config/clients', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        await Swal.fire({
            icon: 'success',
            title: 'Customer Added!',
            timer: 1500,
            showConfirmButton: false
        });
        router.push('/client/all');
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.response?.data?.message || 'Failed to save customer data.'
        });
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    loadData();
});
</script>

<style scoped>
.input-pill-light {
    @apply w-full rounded-2xl border-transparent bg-[#f3f6f9] text-gray-700 text-[11px] py-1.5 px-4 outline-none focus:ring-2 focus:ring-sky-500/50 transition-all border border-gray-100;
}
.select-pill-light {
    @apply w-full rounded-2xl border-transparent bg-[#f3f6f9] text-gray-700 text-[11px] py-1.5 px-4 outline-none focus:ring-2 focus:ring-sky-500/50 transition-all border border-gray-100 appearance-none bg-no-repeat;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-size: 1.25em 1.25em;
}

/* Custom scrollbar */
textarea::-webkit-scrollbar {
    width: 6px;
}
textarea::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 10px;
}
</style>
