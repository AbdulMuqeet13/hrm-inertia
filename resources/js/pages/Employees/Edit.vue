<template>
    <AppSidebarLayout>
        <Card class="space-y-4 p-6">
            <h2 class="text-xl font-semibold">Edit Employee</h2>
            <form
                @submit.prevent="submit"
                class="flex flex-col flex-wrap justify-between md:flex-row"
            >
                <div class="flex w-full flex-col gap-2 p-3 md:w-6/12">
                    <Label>First Name</Label>
                    <Input v-model="form.first_name" />
                </div>
                <div class="flex w-full flex-col gap-2 p-3 md:w-6/12">
                    <Label>Last Name</Label>
                    <Input v-model="form.last_name" />
                </div>
                <div class="flex w-full flex-col gap-2 p-3 md:w-6/12">
                    <Label>Salary</Label>
                    <Input type="number" v-model="form.salary" />
                </div>
                <div class="flex w-full flex-col gap-2 p-3 md:w-6/12">
                    <Label>Department</Label>
                    <Input type="text" v-model="form.department" />
                </div>
                <div class="flex w-full flex-col gap-2 p-3 md:w-6/12">
                    <Label>Designation</Label>
                    <Input type="text" v-model="form.designation" />
                </div>
                <div class="flex w-full flex-col gap-2 p-3 md:w-6/12">
                    <Label>Role</Label>
                    <Select v-model="form.role">
                        <SelectTrigger>
                            <SelectValue placeholder="Select a Role" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Roles</SelectLabel>
                                <SelectItem
                                    v-for="role in roles"
                                    :key="role.id"
                                    :value="role.name"
                                >
                                    {{ role.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>
                <div class="w-full">
                    <Button type="submit" class="mt-4">Update</Button>
                </div>
            </form>
        </Card>
    </AppSidebarLayout>
</template>

<script setup lang="ts">
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import { Label } from '@/components/ui/label/index.ts';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectGroup, SelectLabel, SelectItem } from '@/components/ui/select';


const props = defineProps({
    employee: [],
    roles: [],
});
const form = reactive({
    first_name: props.employee.first_name,
    last_name: props.employee.last_name,
    salary: props.employee.salary,
    department: props.employee.department,
    designation: props.employee.designation,
    role: props.employee.user.roles[0]?.name,
});

function submit() {
    router.put(`/employees/${props.employee.id}`, form); 
}
</script>
