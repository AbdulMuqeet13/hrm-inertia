<template>
    <AppSidebarLayout>
        <Card class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold">{{ employee?.first_name }} {{ employee?.last_name }}</h2>
                <p class="text-gray-500">Designation: {{ employee?.designation?.name || '—' }}</p>
                <p class="text-gray-500">Department: {{ employee?.department?.name || '—' }}</p>
            </div>
            <div class="flex gap-2">
                <!-- {{ props.stats.attendence_data.check_in}} -->
                <Button
                    variant="outline"
                    @click="checkIn"
                    :disabled="props.stats?.attendence_data?.check_in"

                >
                    Check In
                </Button>
                <Button
                    variant="destructive"
                    @click="checkOut"
                    :disabled="props.stats?.attendence_data?.check_in || props.stats?.attendence_data?.check_out"
                >
                    Check Out
                </Button>
            </div>
        </Card>

        <!-- Today’s Attendance Status -->
        <Card class="p-6">
            <h3 class="text-lg font-semibold mb-2">Today’s Attendance</h3>
            <div class="flex gap-4">
                <Badge :variant="attendance?.status === 'present' ? 'success' : 'secondary'">
                    {{ attendance?.status || 'Not Marked' }}
                </Badge>
                {{ attendance }}
                <span v-if="props.stats?.attendence_data
                ?.check_in">Check-in: {{ props.stats?.attendence_data
                ?.check_in }}</span>
                <span v-if="props.stats?.attendence_data?.check_out">Check-out: {{ props.stats?.attendence_data?.check_out }}</span>
            </div>
        </Card>

        <!-- Attendance History -->
        <Card class="p-6">
            <h3 class="text-lg font-semibold mb-4">Attendance History</h3>
            <DataTable
                :columns="columns"
                :data="attendances?.data ?? []"
                :pagination="attendances?.meta"
                @paginate="paginate"
            />
        </Card>
    </AppSidebarLayout>
</template>

<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'
import { Card } from '@/components/ui/card'
import { BarChart } from 'vue-chart-3'
import { Chart, registerables } from 'chart.js'
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import DataTable from '@/components/DataTable.vue';
import { Badge } from '@/components/ui/badge/index.ts';
import { Button } from '@/components/ui/button/index.ts';
import { router } from '@inertiajs/vue3';
Chart.register(...registerables)

const props = usePage().props
// console.log(props)
const stats = props.stats
const monthly = props.monthly

const chartData = {
    labels: monthly.map(m => `Month ${m.month}`),
    datasets: [{ label: 'Attendance', data: monthly.map(m => m.total), backgroundColor: '#4ade80' }],
}
const chartOptions = { responsive: true, plugins: { legend: { position: 'bottom' } } }

function formatTitle(str) {
    return str.replace(/_/g, ' ').replace(/\\b\\w/g, c => c.toUpperCase())
}

function checkIn() {
    router.post('/attendances', {}, {
        preserveScroll: true,
        onSuccess: () => console.log("Checked In")
    })
}
function checkOut() {
  router.put(`/attendences/${props.auth.user.id}`, {}, {
        onSuccess: () => console.log("Checked out!")
    })
}
</script>
