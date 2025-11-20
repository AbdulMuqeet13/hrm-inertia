<template>
        <div v-if="role === 'employee'" class="space-y-6">
                <UserDashboard :stats="stats" />
            </div>

            <!-- ADMIN DASHBOARD -->
        <div v-if="role === 'admin'" class="space-y-6">
               <AdminDashboard :stats="stats" />
            </div>
        <div v-if="role === 'hr'" class="space-y-6">
               <HrDashboard :stats="stats" />
            </div>

</template>

<script setup lang="ts">
import UserDashboard from '@/components/UserDashboard.vue';
import AdminDashboard from '@/components/AdminDashboard.vue';
import HrDashboard from '@/components/HrDashboard.vue';
const props = defineProps({
    user: Object,
    role: String,
    stats: Object,
})

</script>
