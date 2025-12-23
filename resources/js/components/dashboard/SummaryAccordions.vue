<template>
  <div class="space-y-3">
    <!-- Merchant Accordion -->
    <AccordionItem title="Merchant">
      <div class="p-3 space-y-4">
          <!-- Merchant Stat Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
              <SummaryCard label="Total Collection" value="1,342,460 Tk" sub="VAT : 20 Tk" color="bg-[#4caf50]" icon="Collection" />
              <SummaryCard label="Collection" value="1,341,860 Tk" sub="Today : 0 Tk" color="bg-[#2196f3]" icon="Chart" />
              <SummaryCard label="Connection fee" value="600 (1) Tk" sub="Today : 0 (0) Tk" color="bg-[#43a047]" icon="Wifi" />
              <SummaryCard label="Deposit Collection" value="100 (1) Tk" sub="Today : 0 (0) Tk" color="bg-[#673ab7]" icon="Deposit" />
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
              <SummaryCard label="Discount" value="0 (0) Tk" sub="Today : 0 (0) Tk" color="bg-[#e91e63]" icon="Label" />
              <SummaryCard label="Salary" value="0 (0) Tk" sub="Today : 0 (0) Tk" color="bg-[#009688]" icon="Wallet" />
              <SummaryCard label="Expenditure" value="0 (0) Tk" sub="Today : 0 (0) Tk" color="bg-[#f44336]" icon="Wallet" />
              <SummaryCard label="Balance" value="1342560.00 Tk" color="bg-[#8bc34a]" icon="Bank" />
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <SummaryCard label="Masking: 0 / Non-masking: 97 / Fixed-number: 0" value="" sub="" color="bg-[#2196f3]" icon="Message" />
              <SummaryCard label="Merchant Client Creation" value="10000" sub="" color="bg-[#03a9f4]" icon="UserPlus" />
          </div>
      </div>
    </AccordionItem>

    <!-- Reseller Accordion -->
    <AccordionItem title="Reseller">
      <div class="p-3">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
              <SummaryCard label="Paid Balance" value="0(0)" color="bg-[#2196f3]" icon="Wallet" />
              <SummaryCard label="Unpaid Balance" value="0(0)" color="bg-[#f44336]" icon="Wallet" />
              <SummaryCard label="Balance / Client Creation : 0" value="0 Tk" color="bg-[#8bc34a]" icon="Bank" />
          </div>
      </div>
    </AccordionItem>

    <!-- Employee Accordion -->
    <AccordionItem title="Employee">
      <div class="p-3 space-y-3">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
              <SummaryCard label="Total Collection" value="210 Tk" sub="VAT : 15 Tk" color="bg-[#4caf50]" icon="Bank" />
              <SummaryCard label="Collection" value="210 Tk" sub="Today : 0 Tk" color="bg-[#2196f3]" icon="Cloud" />
              <SummaryCard label="Connection Fee" value="0 Tk" sub="Today : 0 Tk" color="bg-[#00bcd4]" icon="User" />
              <SummaryCard label="Deposit Collection" value="0 Tk" color="bg-[#4db6ac]" icon="Wallet" />
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
              <SummaryCard label="Expenditure" value="0 Tk" sub="Today : 0 Tk" color="bg-[#f44336]" icon="Wallet" />
              <SummaryCard label="Discount" value="0 Tk" sub="Today : 0 Tk" color="bg-[#ffc107]" icon="Label" />
              <SummaryCard label="Balance" value="110 Tk" color="bg-[#2196f3]" icon="Bank" />
          </div>

          <!-- Employee Table -->
          <div class="mt-4 overflow-x-auto rounded border border-gray-100">
              <table class="w-full text-[10px] text-left">
                  <thead class="bg-gray-50 text-gray-400 font-bold uppercase tracking-wider">
                      <tr>
                          <th class="px-3 py-2">Name</th>
                          <th class="px-3 py-2">Today Collection</th>
                          <th class="px-3 py-2">Total Collection</th>
                          <th class="px-3 py-2">VAT</th>
                          <th class="px-3 py-2">Total Deposit</th>
                          <th class="px-3 py-2">Total Discount</th>
                          <th class="px-3 py-2">Total Expenditure</th>
                          <th class="px-3 py-2">Balance</th>
                      </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-50">
                      <tr class="hover:bg-gray-50">
                          <td class="px-3 py-2 font-medium text-gray-700">Razzak</td>
                          <td class="px-3 py-2 text-gray-500">0 Tk (0)</td>
                          <td class="px-3 py-2 text-gray-500">210 Tk (1)</td>
                          <td class="px-3 py-2 text-gray-500">15 Tk</td>
                          <td class="px-3 py-2 text-gray-500">0 Tk</td>
                          <td class="px-3 py-2 text-gray-500">0 Tk</td>
                          <td class="px-3 py-2 text-gray-500">0 Tk</td>
                          <td class="px-3 py-2 font-bold text-gray-700">110 Tk</td>
                      </tr>
                      <tr class="hover:bg-gray-50 bg-gray-50/30">
                          <td class="px-3 py-2 font-medium text-gray-700">Ariyan</td>
                          <td class="px-3 py-2 text-gray-500">0 Tk (0)</td>
                          <td class="px-3 py-2 text-gray-500">0 Tk (0)</td>
                          <td class="px-3 py-2 text-gray-500">0 Tk</td>
                          <td class="px-3 py-2 text-gray-500">0 Tk</td>
                          <td class="px-3 py-2 text-gray-500">0 Tk</td>
                          <td class="px-3 py-2 text-gray-500">0 Tk</td>
                          <td class="px-3 py-2 font-bold text-gray-700">0 Tk</td>
                      </tr>
                  </tbody>
              </table>
          </div>
      </div>
    </AccordionItem>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const AccordionItem = {
  props: ['title'],
  template: `
    <div class="bg-white rounded shadow-sm border border-gray-100 overflow-hidden">
      <button 
        @click="isOpen = !isOpen"
        class="w-full flex items-center justify-between px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 transition-colors"
      >
        <span>{{ title }}</span>
        <span class="text-lg">{{ isOpen ? '-' : '+' }}</span>
      </button>
      <div v-show="isOpen" class="border-t border-gray-50">
        <slot></slot>
      </div>
    </div>
  `,
  setup() {
    const isOpen = ref(true);
    return { isOpen };
  }
};

const SummaryCard = {
  props: ['label', 'value', 'sub', 'color', 'icon'],
  template: `
    <div :class="['p-3 rounded flex items-center justify-between text-white relative overflow-hidden group shadow-sm hover:shadow transition-shadow', color]">
      <div class="min-w-0 flex-1 z-10">
        <h4 class="text-xs font-black truncate leading-tight">{{ value }}</h4>
        <p class="text-[9px] font-bold opacity-80 mt-0.5 truncate uppercase leading-tight">{{ label }}</p>
        <p v-if="sub" class="text-[9px] font-bold opacity-70 mt-1 leading-tight">{{ sub }}</p>
      </div>
      <div class="z-10 bg-white/20 p-1.5 rounded-lg shrink-0 ml-2">
         <svg v-if="icon === 'Collection' || icon === 'Deposit' || icon === 'Wallet' || icon === 'Bank' || icon === 'Cloud'" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" /></svg>
         <svg v-else-if="icon === 'Chart'" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 4 4 4-4" /></svg>
         <svg v-else-if="icon === 'Wifi'" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" /></svg>
         <svg v-else-if="icon === 'Label'" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
         <svg v-else-if="icon === 'Message'" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
         <svg v-else-if="icon === 'UserPlus' || icon === 'User'" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
         <svg v-else class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
      </div>
      <div class="absolute bottom-[-8px] right-[-8px] text-white/10 group-hover:text-white/20 transition-all duration-300">
          <svg class="h-14 w-14" fill="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
      </div>
    </div>
  `
};
</script>
