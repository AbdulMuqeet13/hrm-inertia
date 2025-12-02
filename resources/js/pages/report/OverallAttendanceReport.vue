<script setup lang="ts">
import { ref, computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Select, SelectTrigger, SelectValue, SelectContent, SelectGroup, SelectLabel, SelectItem } from '@/components/ui/select'
import { Label } from '@/components/ui/label'
import { Inertia } from '@inertiajs/inertia'
const props = defineProps({
    users: Object,
    summary: Object,
    filters: Object,
})
const filters = ref({
    type: props.filters.type || '',
    from: props.filters.from || '',
    to: props.filters.to || '',
})
// Local state for filters
const selectedUser = ref(null)
const fromDate = ref('')
const toDate = ref('')

function applyFilters() {
    router.reload({data: {
            ...filters.value
        }})
    // router.get(route('reports.index'), , { preserveState: true })
}

const columns = [
    { accessorKey: 'user.name', header: 'User' },
    { accessorKey: 'type', header: 'Type' },
    { accessorKey: 'amount', header: 'Amount' },
    { accessorKey: 'created_at', header: 'Date' },
]





</script>

<template>
    <AppLayout >
       <div class="container mx-auto space-y-6 p-6">
            <h1 class="text-3xl font-bold">Attendance Reports</h1>
            <div class="flex flex-wrap gap-3 items-end justify-end">
                <div class="flex flex-col gap-3">
                    <Label>Select User</Label>
                    <Select>
                        <SelectTrigger class="w-48">
                            <SelectValue placeholder="Select User" />
                        </SelectTrigger>
                        <SelectContent>               
                           <SelectItem
                                v-for="user in users"
                                :key="user.id"
                                :value="user.id"
                            >
                                {{ user.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                       </div>
                         <div class="flex flex-col gap-3">
                    <Label>Start Date</Label>
                    <Input v-model="filters.from" type="date" placeholder="From" />
                </div>
                <div class="flex flex-col gap-3">
                    <Label>End Date</Label>
                    <Input v-model="filters.to" type="date" placeholder="To" />
                </div>

                <Button @click="applyFilters">Filter</Button>
            </div>

                    </div>
               <CardTitle>Transactions</CardTitle>
            <CardDescription>Showing detailed income records</CardDescription>
            <DataTable :columns="columns" :tableData="props.transactions.data" />
                
    </AppLayout>
</template>
