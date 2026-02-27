<template>
    <div class="flex flex-col items-baseline justify-between gap-y-4">
        <div class="flex gap-x-4 w-full">
            <div class="flex-[0.8]">
                <label class="form-label">
                    Contact
                    <span class="text-xs italic">(Search records)</span>
                </label>

                <Select
                    v-model:data="contacts"
                    v-model:selected="data!.customer"
                    column
                    custom-column
                    url="/api/contacts/contacts"
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
                                invoices: row.invoices,
                                columns: ['Name', 'type'],
                            }))
                    "
                    :map-query="
                        (search: any) => ({
                            query: {
                                name: search,
                                unpaid_invoice: true,
                            },
                        })
                    "
                    :is-valid="isValid('customer')"
                    clearable
                    remote
                    loading
                    @change="onChangeCustomer"
                >
                    <template #customColumn="{ data }">
                        <ContactColumn :data="data" />
                    </template>
                </Select>
            </div>

            <div class="flex-[0.6]">
                <InputNative
                    v-model="data!.customer_email"
                    block
                    placeholder="..."
                    label="Email Address"
                    :is-valid="isValid('customer_email')"
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
import ContactColumn from "./contact-column.vue";
import type { OfficialReceipt } from "~/types/official-receipts";

const data = defineModel<Partial<OfficialReceipt>>();
const { send } = usePageEvent();

interface Props {
    errors: any;
}

const props = defineProps<Props>();

const contacts = ref([]);

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? (props.errors as any)[key]?.length <= 0
            : null
        : null;

const onChangeCustomer = (value: any) => {
    if (!value) return;

    data.value!.customer_name = value.label;
    data.value!.customer_email = value.email;
    data.value!.billing_address = value.billing_address;

    send("add:invoice-items", value.invoices);
};
</script>
