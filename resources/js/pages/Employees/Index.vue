<template>
    <AppSidebarLayout>
        <Card class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold">Staff Management</h2>
                <Link href="/employees/create">
                    <Button size="sm">Add Staff</Button>
                </Link>
            </div>

            <div class="flex gap-2">
                <Input
                    v-model="filters.search"
                    placeholder="Search employees..."
                />
                <Button size="sm" @click="search">Search</Button>
            </div>

            <DataTable
                :columns="columns"
                :data="employees.data"
                :pagination="employees.meta"
                @paginate="paginate"
            />
        </Card>
    </AppSidebarLayout>
</template>

<script setup lang="ts">
import { reactive } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import DataTable from '@/components/DataTable.vue';
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import { h } from 'vue';
import { Pencil, Trash } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge/index.ts';

const props = defineProps({
    employees: [Object, Array],
    filters: {
    type: Object,
    default: () => ({ search: '' })
},
});
const filters = reactive({ search: props.filters?.search || '' });

const reloadData = (page = 1) => {
    router.reload({
        only: ['employees', 'filters'],
        data: {
            search: filters.search,
            page: page,
        },
    });
};
function search() {
    reloadData();
}
function paginate(page) {
    reloadData(page);
}

const columns = [
    {
        key: 'first_name',
        label: 'Name',
        render: (row) => `${row.first_name} ${row.last_name}`,
    },
    {
        key: 'department',
        label: 'Department',
        render: (row) => row.department ?? '—',
    },
    {
        key: 'status',
        label: 'Status',
        render: (row) =>
            h(
                Badge,
                {
                    variant:
                        row.status === 'active' ? 'success' : 'destructive',
                },
                () => row.status,
            ),
    },
    {
        key: 'actions',
        label: 'Actions',
        render: (row) => [
            h(
                Link,
                { href: `/employees/${row.id}/edit` },
                {
                    default: () =>
                        h(Button, { variant: 'outline', size: 'sm' }, () =>
                            h(Pencil),
                        ),
                },
            ),
            h(
                Button,
                {
                    size: 'sm',
                    variant: 'destructive',
                    class: 'ml-4 h-8',
                    onClick: () => router.delete(`/employees/${row.id}`),
                },
                () => h(Trash),
            ),
        ],
    },
];
</script>
