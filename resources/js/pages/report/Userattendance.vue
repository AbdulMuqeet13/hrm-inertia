<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Select, SelectTrigger, SelectValue, SelectContent, SelectGroup, SelectLabel, SelectItem } from '@/components/ui/select';

const props = defineProps({
    users: Object,
    // summary: Object,
    // filters: Object,
})


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
                    </div>
                    </div>
                
    </AppLayout>
</template>
