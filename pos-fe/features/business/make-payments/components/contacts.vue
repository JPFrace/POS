<template>
  <div class="flex flex-col items-baseline justify-between gap-y-4">
    <div class="flex gap-x-4 w-full">
      <div class="flex-[0.8]">
        <label class="form-label"
          >Contact <span class="text-xs italic">(Search records)</span></label
        >

        <Select
          v-model:data="contacts"
          v-model:selected="data!.contact"
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
                bills: row.bills,
                columns: ['Name', 'type'],
              }))
          "
          :map-query="
            (search: any) => ({
              query: {
                name: search,
                unpaid_bills: true,
              },
            })
          "
          :is-valid="isValid('contact')"
          clearable
          remote
          loading
          @change="onChangeContact"
        >
          <template #customColumn="{ data }">
            <ContactColumn :data="data" />
          </template>
        </Select>
      </div>

      <div class="flex-[0.6]">
        <InputNative
          v-model="data!.payee_email"
          block
          placeholder="..."
          label="Email Address"
          :is-valid="isValid('payee_email')"
        />
      </div>
    </div>

    <div class="w-full">
      <div>
        <InputNative
          v-model="data!.payee_address"
          block
          label="Billing Address"
          placeholder="..."
          :is-valid="isValid('payee_address')"
        />
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import ContactColumn from "./contact-column.vue";
import type { Payment } from "~/types/payment";
import type { Contacts } from "~/types/contacts";
const { send } = usePageEvent();

interface Props {
  errors: any;
}

const data = defineModel<Partial<Payment>>();
const contacts = defineModel<Contacts[]>("contacts");

const props = defineProps<Props>();

const isValid = (key: string) =>
  props.errors
    ? Object.keys(props.errors).includes(key)
      ? (props.errors as any)[key]?.length <= 0
      : null
    : null;

const onChangeContact = (value: any) => {
  
    if (value == null) {
    data.value!.payee_name = null;
    data.value!.payee_email = null;
    data.value!.payee_address = null;
    send("add:bill-items", []);
    return;
  }

  data.value!.payee_name = value.label;
  data.value!.payee_email = value.email;
  data.value!.payee_address = value.billing_address;
  send("add:bill-items", value.bills ?? []);
  
};
</script>
