<template>
  <div class="bg-white p-3 sm:p-4 rounded shadow-sm border border-gray-50 flex flex-col">
    <h3 class="text-xs font-black text-gray-700 uppercase tracking-tight mb-3">Ticket Summary</h3>
    
    <div class="grid grid-cols-2 gap-2 flex-1">
      <!-- Total Ticket -->
      <div class="bg-[#2196f3] rounded p-2 sm:p-3 text-white flex flex-col items-center justify-center text-center transition-all duration-300 hover:scale-[1.03] hover:shadow-md cursor-pointer group">
          <div class="bg-white/20 p-1.5 rounded-lg mb-1 group-hover:bg-white/30 transition-colors">
              <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
          </div>
          <div class="text-base sm:text-lg font-black leading-tight">0</div>
          <div class="text-[8px] sm:text-[9px] font-bold uppercase opacity-80">Total Ticket</div>
      </div>

      <!-- PENDING -->
      <div class="bg-[#ffc107] rounded p-2 sm:p-3 text-white flex flex-col items-center justify-center text-center transition-all duration-300 hover:scale-[1.03] hover:shadow-md cursor-pointer group">
          <div class="bg-white/20 p-1.5 rounded-lg mb-1 group-hover:bg-white/30 transition-colors">
              <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          </div>
          <div class="text-base sm:text-lg font-black leading-tight">0</div>
          <div class="text-[8px] sm:text-[9px] font-bold uppercase opacity-80">PENDING</div>
      </div>

      <!-- PROCESSING -->
      <div class="bg-[#673ab7] rounded p-2 sm:p-3 text-white flex flex-col items-center justify-center text-center transition-all duration-300 hover:scale-[1.03] hover:shadow-md cursor-pointer group">
          <div class="bg-white/20 p-1.5 rounded-lg mb-1 group-hover:bg-white/30 transition-colors">
              <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
          </div>
          <div class="text-base sm:text-lg font-black leading-tight">0</div>
          <div class="text-[8px] sm:text-[9px] font-bold uppercase opacity-80">PROCESSING</div>
      </div>

      <!-- RESOLVE -->
      <div class="bg-[#8bc34a] rounded p-2 sm:p-3 text-white flex flex-col items-center justify-center text-center transition-all duration-300 hover:scale-[1.03] hover:shadow-md cursor-pointer group">
          <div class="bg-white/20 p-1.5 rounded-lg mb-1 group-hover:bg-white/30 transition-colors">
              <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          </div>
          <div class="text-base sm:text-lg font-black leading-tight">0</div>
          <div class="text-[8px] sm:text-[9px] font-bold uppercase opacity-80">RESOLVE</div>
      </div>

    </div>
  </div>
</template>
