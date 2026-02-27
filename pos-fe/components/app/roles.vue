<template>
    <Select        
        url="api/security/roles"
        v-model:data="roles"
        v-model:selected="model"
        :mapResult="
          (result: any) =>
            result.data.map((row: Role) => ({
              id: row.uuid,
              value: row.uuid,
              label: row.name
            }))
        "
        :mapQuery="
          (search: any) => ({
            query: {
              name: search,
            },
          })
        "       
        clearable
        remote
        loading
        multiple
        placeholder="Choose Roles"
      />
</template>

<script lang="ts" setup>
import type { Option } from "~/types/form";
import type { Role } from "~/types/policy";
const model = defineModel<Option | Option[]>();
const roles = ref<Option[]>([]);
</script>
