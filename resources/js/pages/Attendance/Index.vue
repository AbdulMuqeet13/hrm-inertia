<template>
    <AppSidebarLayout>
        <Card class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold">Attendance</h2>
                <div class="space-x-2">
                    <Button @click="checkIn">Check In</Button>
                    <Button variant="outline" @click="checkOut"
                        >Check Out</Button
                    >
                </div>
            </div>

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
import { router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import DataTable from '@/components/DataTable.vue';
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import { h } from 'vue';
import { Badge } from '@/components/ui/badge/index.ts';

const props = defineProps({
    attendances: []
});

function checkIn() {
    router.post('/attendances', {});
}
function checkOut() {
    router.put('/attendances/0', {});
} // using update route
function paginate(page) {
    router.reload({
        only: ['attendances'],
        data: {
            page: page
        }
    })
}

const columns = [
    {
        key: 'employee',
        label: 'Employee',
        render: (r) => `${r.employee?.first_name} ${r.employee?.last_name}`,
    },
    { key: 'date', label: 'Date' },
    { key: 'check_in', label: 'Check In' },
    { key: 'check_out', label: 'Check Out' },
    {
        key: 'status',
        label: 'Status',
        render: (r) =>
            h(
                Badge,
                { variant: r.status === 'present' ? 'success' : 'secondary' },
                () => r.status,
            ),
    },
];
</script>
