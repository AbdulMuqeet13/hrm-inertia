<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import Button from '@/components/ui/button/Button.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'


const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reports', href: '/reports' },
]

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex items-center gap-8 py-20">
                
                <Link href="/reports/user-attendance">
                    <Button size="lg">User Reports</Button>
                </Link>
                 <Link href="/reports/over-all-attendance">
                    <Button size="lg">Get Over All Report</Button>
                </Link>
            </div>
    </AppLayout>
</template>
