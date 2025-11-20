<template>
<AppSidebarLayout>
       <div class="grid grid-cols-4 gap-4 mb-8">
            <Card v-for="(val, key) in stats" :key="key" class="p-4 text-center">
              <h3 class="text-sm text-gray-500">{{ formatTitle(key) }}</h3>
             <p class="text-3xl font-bold">{{ val }}</p>        
            </Card>
      </div>

       <Card class="p-6 mb-8">
           <h2 class="text-lg font-semibold mb-4">Monthly Attendance</h2>
            <BarChart :chart-data="chartData" :chart-options="chartOptions" />
       </Card>
 </AppSidebarLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { BarChart } from 'vue-chart-3'
import { Chart, registerables } from 'chart.js'
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue'
Chart.register(...registerables)

const props = usePage().props

// Use a default empty array if monthly is undefined
const monthly = ref(props.monthly ?? [])
const stats = ref(props.stats ?? {})

// reactive chart data
const chartData = computed(() => ({
  labels: monthly.value.map(m => `Month ${m.month}`),
  datasets: [
    { label: 'Attendance', data: monthly.value.map(m => m.total), backgroundColor: '#4ade80' }
  ]
}))

const chartOptions = { responsive: true, plugins: { legend: { position: 'bottom' } } }

function formatTitle(str: string) {
  return str.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())
}
</script>
