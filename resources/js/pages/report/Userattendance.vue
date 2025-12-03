<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/components/ui/select'
import CardDescription from '@/components/ui/card/CardDescription.vue'
import CardTitle from '@/components/ui/card/CardTitle.vue'
import DataTable from '@/components/DataTable.vue'
import { Label } from '@/components/ui/label'
import { Input } from '@/components/ui/input' 
import { Button } from '@/components/ui/button' 
// Define props
const props = defineProps({
    users: Array,
    filters: Object,
    reportData: Object,
})
console.log(props)
const selectedUser = ref(''); 
const fromDate = ref('');  // You can also initialize it with props.filters.from if needed
const toDate = ref(''); 
// Local reactive states for the filters
const filters = ref({
    type: props.filters.type || '',
    from: props.filters.from || '',
    to: props.filters.to || '',
})
watch(()=>props.reportData,()=>{
    console.log(props.reportData.data);
})
// Function to apply the filters and reload the data
function applyFilters() {
    router.reload({data: {
          
             ...filters.value,
            employee_id: selectedUser.value,
            from: fromDate.value,
            to: toDate.value,
        }})
    // router.get(route('reports.index'), , { preserveState: true })
}
const columns = [
    // { key: 'employee', label: 'Employee', render: (row) => `${row.employee.first_name} ${row.employee.last_name}`, },
    { key: 'check_in', label: 'Check-in Time' },
    { key: 'check_out', label: 'Check-out Time' },

    { key: 'date', label: 'Date' }
]
</script>

<template>
    <AppLayout>
        <div class="container mx-auto space-y-6 p-6">
            <h1 class="text-3xl font-bold">Attendance Reports</h1>
            <div class="flex flex-wrap gap-3 items-end justify-end">
                <!-- User Filter -->
                <div class="flex flex-col gap-3">
                    <Label>Select User</Label>
                    <Select v-model="selectedUser">
                        <SelectTrigger class="w-48">
                            <SelectValue placeholder="Select User" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="user in users" :key="user.id" :value="user.id">
                                {{ user.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <!-- Start Date Filter -->
                <div class="flex flex-col gap-3">
                    <Label>Start Date</Label>
                    <Input v-model="fromDate" type="date" placeholder="From" />
                </div>

                <!-- End Date Filter -->
                <div class="flex flex-col gap-3">
                    <Label>End Date</Label>
                    <Input v-model="toDate" type="date" placeholder="To" />
                </div>

                <!-- Filter Button -->
                <Button @click="applyFilters">Filter</Button>
            </div>

            <!-- Data Table Section -->
            <CardTitle>Attendence</CardTitle>
            <CardDescription>Showing details attendance</CardDescription>
            <!-- {{ reportData }} -->
             <div v-if="!selectedUser && !fromDate && !toDate">
                <p class="text-center text-gray-500">Please select a user to view the attendance report.</p>
            </div>
            
            <!-- Only show DataTable if a user is selected -->
            <DataTable 
                v-if="selectedUser && fromDate && toDate"
                :columns="columns" 
                :data="reportData?.data || []"
            />
        </div>
      
    </AppLayout>
</template>
