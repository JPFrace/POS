<template>
    <div class="flex flex-col items-baseline justify-between gap-y-9">
        <div>
            <label class="form-label"
                >Terms
                <span class="text-xs italic">(Search records)</span></label
            >
            <Select
                url="/api/business/terms"
                v-model:data="terms"
                v-model:selected="data!.term"
                :mapResult="
                    (result: any) =>
                        result.data.map((row: BillTerm) => ({
                            id: row.uuid,
                            value: row.uuid,
                            label: row.name,
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
                placeholder="..."
                :is-valid="isValid('term')"
            />
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { Bill, BillTerm } from "~/types/bill";
import type { Option } from "~/types/form";

const data = defineModel<Partial<Bill>>();

interface Props {
    errors: any;
}

const props = defineProps<Props>();

const terms = ref<Option[]>([]);

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? (props.errors as any)[key]?.length <= 0
            : null
        : null;

// onMounted(() => {
//     if (data.value?.term) {
//         terms.value = [
//             {
//                 id: (data.value.term as any).uuid,
//                 value: (data.value.term as any).uuid,
//                 label: (data.value.term as any).name,
//             },
//         ];
//     }
// });
</script>
