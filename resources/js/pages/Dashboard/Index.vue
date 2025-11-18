<template>
    <AppSidebarLayout>
<!--        <div class="grid grid-cols-4 gap-4 mb-8">-->
<!--            <Card v-for="(val, key) in stats" :key="key" class="p-4 text-center">-->
<!--                <h3 class="text-sm text-gray-500">{{ formatTitle(key) }}</h3>-->
<!--                <p class="text-3xl font-bold">{{ val }}</p>-->
<!--            </Card>-->
<!--        </div>-->

<!--        <Card class="p-6 mb-8">-->
<!--            <h2 class="text-lg font-semibold mb-4">Monthly Attendance</h2>-->
<!--            <BarChart :chart-data="chartData" :chart-options="chartOptions" />-->
<!--        </Card>-->

        <!-- Employee Info -->
        <Card class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold">{{ employee?.first_name }} {{ employee?.last_name }}</h2>
                <p class="text-gray-500">Designation: {{ employee?.designation?.name || '—' }}</p>
                <p class="text-gray-500">Department: {{ employee?.department?.name || '—' }}</p>
            </div>
            <div class="flex gap-2">
                <Button
                    variant="outline"
                    @click="checkIn"
                    :disabled="attendance?.check_in"
                >
                    Check In
                </Button>
                <Button
                    variant="destructive"
                    @click="checkOut"
                    :disabled="!attendance?.check_in || attendance?.check_out"
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
                <span v-if="attendance?.check_in">Check-in: {{ attendance.check_in }}</span>
                <span v-if="attendance?.check_out">Check-out: {{ attendance.check_out }}</span>
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

<script setup>
import { usePage } from '@inertiajs/vue3'
import { Card } from '@/components/ui/card'
import { BarChart } from 'vue-chart-3'
import { Chart, registerables } from 'chart.js'
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import DataTable from '@/components/DataTable.vue';
import { Badge } from '@/components/ui/badge/index.ts';
import { Button } from '@/components/ui/button/index.ts';
Chart.register(...registerables)

const props = usePage().props
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
</script>
