<template>
  <div class="space-y-3 sm:space-y-4 md:space-y-5 max-w-full mx-auto">
    <!-- Row 1: Charts (Expected, Collected, Mikrotik) - Now at Top -->
    <RevenueCharts />

    <!-- Row 2: Stat Cards Grid 1 -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 md:gap-4">
        <StatCard label="Total Client" value="10000" bgColor="bg-[#009688]" borderColor="border-[#00796b]" />
        <StatCard label="Active Client" value="1" bgColor="bg-[#4caf50]" borderColor="border-[#388e3c]" />
        <StatCard label="Inactive Client" value="2" bgColor="bg-[#9c27b0]" borderColor="border-[#7b1fa2]" />
        <StatCard label="Expired Client" value="9997" bgColor="bg-[#f44336]" borderColor="border-[#d32f2f]" />
    </div>

    <!-- Row 3: Stat Cards Grid 2 -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 md:gap-4">
        <StatCard label="Paid Client" value="2" bgColor="bg-[#4caf50]" borderColor="border-[#388e3c]" />
        <StatCard label="Unpaid Client" value="9998" bgColor="bg-[#f44336]" borderColor="border-[#d32f2f]" />
        <StatCard label="New Client" value="10000" bgColor="bg-[#03a9f4]" borderColor="border-[#0288d1]" />
        <StatCard label="Free Client" value="0" bgColor="bg-[#607d8b]" borderColor="border-[#455a64]" />
    </div>

    <!-- Row 4: Financial Summary Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 md:gap-4">
      <StatCard label="Total Collection" value="1,342,670 Tk" bgColor="bg-[#009688]" borderColor="border-[#00796b]" />
      <StatCard label="Expenditure" value="0 Tk" bgColor="bg-[#9c27b0]" borderColor="border-[#7b1fa2]" />
      <StatCard label="Salary" value="0 Tk" bgColor="bg-[#4caf50]" borderColor="border-[#388e3c]" />
      <StatCard label="Balance" value="1,342,670 Tk" bgColor="bg-[#03a9f4]" borderColor="border-[#0288d1]" />
    </div>

    <!-- Row 5: Collection Graph & Ticket Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4">
        <div class="lg:col-span-2">
            <CollectionGraph />
        </div>
        <div>
            <TicketSummary />
        </div>
    </div>

    <!-- Bottom Accordions -->
    <SummaryAccordions />
  </div>
</template>


<script setup>
import StatCard from '../components/StatCard.vue';
import RevenueCharts from '../components/dashboard/RevenueCharts.vue';
import CollectionGraph from '../components/dashboard/CollectionGraph.vue';
import TicketSummary from '../components/dashboard/TicketSummary.vue';
import SummaryAccordions from '../components/dashboard/SummaryAccordions.vue';
</script>
