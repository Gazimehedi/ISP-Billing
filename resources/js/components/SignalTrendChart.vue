<template>
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-lg font-bold text-gray-800">{{ title }}</h3>
        <p class="text-xs text-gray-500 mt-1">{{ subtitle }}</p>
      </div>
      <div class="flex gap-2">
        <button 
          v-for="period in periods" 
          :key="period.value"
          @click="changePeriod(period.value)"
          :class="selectedPeriod === period.value ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600'"
          class="px-3 py-1 rounded-lg text-xs font-bold transition-colors"
        >
          {{ period.label }}
        </button>
      </div>
    </div>
    <div class="relative" style="height: 300px;">
      <Line v-if="chartData" :data="chartData" :options="chartOptions" />
      <div v-else class="flex items-center justify-center h-full">
        <div class="text-center">
          <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
          <p class="text-sm text-gray-400">Loading signal data...</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { Line } from 'vue-chartjs';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
} from 'chart.js';
import axios from 'axios';

// Register Chart.js components
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
);

const props = defineProps({
  oltUserId: {
    type: Number,
    required: true
  },
  title: {
    type: String,
    default: 'Signal Strength Trend'
  },
  subtitle: {
    type: String,
    default: 'Historical RX/TX power levels'
  }
});

const chartData = ref(null);
const selectedPeriod = ref(24);
const periods = [
  { label: '6H', value: 6 },
  { label: '24H', value: 24 },
  { label: '7D', value: 168 },
  { label: '30D', value: 720 }
];

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  interaction: {
    mode: 'index',
    intersect: false,
  },
  plugins: {
    legend: {
      position: 'top',
      labels: {
        usePointStyle: true,
        padding: 15,
        font: {
          size: 11,
          weight: 'bold'
        }
      }
    },
    tooltip: {
      backgroundColor: 'rgba(0, 0, 0, 0.8)',
      padding: 12,
      titleFont: {
        size: 12,
        weight: 'bold'
      },
      bodyFont: {
        size: 11
      },
      callbacks: {
        label: function(context) {
          let label = context.dataset.label || '';
          if (label) {
            label += ': ';
          }
          if (context.parsed.y !== null) {
            label += context.parsed.y.toFixed(2) + ' dBm';
          }
          return label;
        }
      }
    }
  },
  scales: {
    x: {
      grid: {
        display: false
      },
      ticks: {
        font: {
          size: 10
        },
        maxRotation: 0,
        autoSkip: true,
        maxTicksLimit: 12
      }
    },
    y: {
      beginAtZero: false,
      grid: {
        color: 'rgba(0, 0, 0, 0.05)'
      },
      ticks: {
        font: {
          size: 10
        },
        callback: function(value) {
          return value + ' dBm';
        }
      }
    }
  }
};

const fetchSignalData = async () => {
  try {
    const res = await axios.get(`/api/config/monitoring/signal-trends/${props.oltUserId}`, {
      params: { hours: selectedPeriod.value }
    });
    
    const data = res.data;
    
    if (!data || data.length === 0) {
      chartData.value = null;
      return;
    }

    const labels = data.map(item => {
      const date = new Date(item.captured_at);
      if (selectedPeriod.value <= 24) {
        return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
      } else {
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
      }
    });

    chartData.value = {
      labels,
      datasets: [
        {
          label: 'RX Power',
          data: data.map(item => item.rx_power),
          borderColor: 'rgb(59, 130, 246)',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.4,
          pointRadius: 2,
          pointHoverRadius: 5
        },
        {
          label: 'TX Power',
          data: data.map(item => item.tx_power),
          borderColor: 'rgb(16, 185, 129)',
          backgroundColor: 'rgba(16, 185, 129, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.4,
          pointRadius: 2,
          pointHoverRadius: 5
        }
      ]
    };
  } catch (error) {
    console.error('Failed to fetch signal trends', error);
    chartData.value = null;
  }
};

const changePeriod = (hours) => {
  selectedPeriod.value = hours;
  fetchSignalData();
};

watch(() => props.oltUserId, () => {
  fetchSignalData();
});

onMounted(() => {
  fetchSignalData();
});
</script>
