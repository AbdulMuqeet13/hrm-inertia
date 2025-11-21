<template>
    <div class="overflow-x-auto rounded-lg border">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <!-- Table Head -->
            <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                <th
                    v-for="col in columns"
                    :key="col.key"
                    class="px-4 py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                >
                    {{ col.label }}
                </th>
            </tr>
            </thead>

            <!-- Table Body -->
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="row in data" :key="row.id">
                <td v-for="col in columns" :key="col.key" class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                    <component
                        v-if="col.render"
                        :is="{ render: () => col.render(row) }"
                    />
                    <span v-else>{{ row[col.key] }}</span>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="p-4 flex justify-end">
            <Pagination
                v-if="pagination"
                :links="employees.links"
                @paginate="onPaginate"
                :items-per-page="10"/>
        </div>
    </div>
</template>

<script setup="ts">
import { defineProps, defineEmits } from 'vue'
import { Pagination } from '@/components/ui/pagination'
import employees from '@/routes/employees'

const props = defineProps({
    columns: { type: Array, required: true },
    data: { type: Array, required: true },
    pagination: { type: Object, default: null },
})

const emit = defineEmits(['paginate'])

function onPaginate(page) {
    emit('paginate', page)
}
</script>

<style scoped>
/* Optional: style for hover or striped rows */
tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.03);
}
</style>
