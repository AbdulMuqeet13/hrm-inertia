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
            <Button variant="outline" @click="open = true">
             Import Excel
            </Button>

            <Dialog v-model:open="open">
                <DialogContent class="sm:max-w-md">
                    <DialogHeader>
                        <DialogTitle>Upload Excel File</DialogTitle>
                    </DialogHeader>
                      <Button variant="outline" @click="excel"
                        >Download Sample Excel</Button
                    >
                    <Input type="file" ref="fileInput" @change="handleFile" accept=".xlsx,.xls" />
                    <DialogFooter>
                        <Button @click="importFile" :disabled="!selectedFile">Import</Button>        
                        <Button @click="open = false">Close</Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

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
import { reactive, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import DataTable from '@/components/DataTable.vue';
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import { h } from 'vue';
import { Pencil, Trash,RefreshCcw } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge/index.ts';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog'

const open = ref(false)

const props = defineProps({
    employees: [Object, Array],
    filters: {
    type: Object,
    default: () => ({ search: '' })
},
});
console.log(props)
const selectedFile = ref<File | null>(null)
const fileInput = ref<HTMLInputElement | null>(null)

// store the chosen file
function handleFile(event: Event) {
    const target = event.target as HTMLInputElement
    if (target.files && target.files.length > 0) {
        selectedFile.value = target.files[0]
    }
}

// submit file to backend
function importFile() {
    if (!selectedFile.value) return

    const formData = new FormData()
    formData.append('file', selectedFile.value)

    router.post('/import-employee', formData, {
        forceFormData: true,
        onSuccess: () => {
            open.value = false
            selectedFile.value = null
        },
    })
}
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
function excel(){
    window.location.href = '/export-employee';
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
                    disabled: row.status !== 'active',
                    onClick: () => router.delete(`/employees/${row.id}`),
                },
                () => h(Trash),
            ),
            h(
                
                Button,
                {
                    
                    size: 'sm',
                    variant: 'destructive',
                    class: 'ml-4 h-8',
                    disabled: row.status == 'active',
                    onClick: () => router.put(`/employees/${row.id}/reactivate`),
                },
                () => h(RefreshCcw),
            ), 
        ],
    },
];
</script>
