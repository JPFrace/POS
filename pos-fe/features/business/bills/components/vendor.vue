<template>
    <div class="flex flex-col items-baseline justify-between gap-y-4">
        <div class="flex gap-x-4">
            <div>
                <label class="form-label"
                    >Vendor
                    <span class="text-xs italic">(Search records)</span></label
                >

                <Select
                    v-model:data="contacts"
                    v-model:selected="data!.vendor"
                    column
                    custom-column
                    url="/api/contacts/contacts"
                    :autoload="true"
                    :map-result="
                        (result: any) =>
                            result.data.map((row: any) => ({
                                id: row.uuid,
                                value: row.uuid,
                                label: row.full_name,
                                type: row.type_label,
                                id_no: row.id_no,
                                email: row.email,
                                billing_address: row.billing_address,
                                orders: row.orders,
                                columns: ['Name', 'type'],
                            }))
                    "
                    :map-query="
                        (search: any) => ({
                            query: {
                                name: search,
                                open_orders: true,
                                vendor_only: true,
                            },
                        })
                    "
                    :is-valid="isValid('vendor')"
                    clearable
                    remote
                    loading
                    @change="onChangeVendor"
                >
                    <template #customColumn="{ data }">
                        <ContactColumn :data="data" />
                    </template>
                </Select>
            </div>
            <div>
                <InputNative
                    v-model="data!.vendor_email"
                    block
                    placeholder="..."
                    label="Email Address"
                    :is-valid="isValid('vendor_email')"
                />
            </div>
        </div>

        <div class="w-full">
            <div>
                <InputNative
                    v-model="data!.billing_address"
                    block
                    label="Billing Address"
                    placeholder="..."
                    :is-valid="isValid('billing_address')"
                />
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { Bill } from "~/types/bill";
import ContactColumn from "./contact-column.vue";
import type { Option } from "~/types/form";

const data = defineModel<Partial<Bill>>();
const { send } = usePageEvent();

interface Props {
    errors: any;
}

const props = defineProps<Props>();

const contacts = ref<Option[]>([]);

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? (props.errors as any)[key]?.length <= 0
            : null
        : null;

const onChangeVendor = (value: any) => {
    if (!value) return;

    data.value!.vendor_name = value.label;
    data.value!.vendor_email = value.email;
    data.value!.billing_address = value.billing_address;

    send("add:order-items", value.orders);
};
</script>
