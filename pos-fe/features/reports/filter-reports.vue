<template>
    <div class="flex flex-col">
        <label class="form-label">Reports</label>
        <Select
            v-model:data="reports"
            url="/api/setup/reports"
            v-model:selected="model"
            :mapResult="
                (result: any) =>
                    result.data.map((row: Reports) => ({
                        id: row.uuid,
                        value: row.uuid,
                        label: row.name,
                        template: row.template,
                    }))
            "
            :mapQuery="
                (search: any) => ({
                    query: { name: search },
                })
            "
            clearable
            remote
            loading
            placeholder="Select..."
        />
    </div>
</template>

<script setup lang="ts">
import type { Reports } from "~/types/reports";

const model = defineModel();
const reports = ref([]);
</script>
