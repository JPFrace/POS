<template>
    <div>
        <NuxtLayout>
            <template #toolbarRight>
                <EditTaxSetup
                    endpoint="/api/taxes/tax-setup"
                    width="60%"
                    width-lg="30%"
                    :params="{
                        query: {
                            calendar: true,
                            tax: true,
                        },
                    }"
                >
                    <template #form="{ errors, form, schema, dataRef }">
                        <TaxSetupForm
                            :errors="errors"
                            :form="form"
                            :schema="schema"
                            :data-ref="dataRef"
                        />
                    </template>
                    <template #drawerFooter="{ submit, hide }">
                        <Button
                            variant="light"
                            class="btn btn-light ms-auto fw-semibold"
                            icon="black-left"
                            @click="hide()"
                        >
                            <span>Cancel</span>
                        </Button>
                        <Button
                            variant="primary"
                            class="btn btn-primary fw-semibold"
                            icon="add-folder"
                            @click="submit()"
                        >
                            <span>Submit</span>
                        </Button>
                    </template>
                </EditTaxSetup>

                <AddTax
                    endpoint="/api/taxes/tax"
                    width="70%"
                    width-lg="40%"
                    :params="{
                        query: {},
                    }"
                    @success="onSuccess"
                    ><template
                        #form="{ errors, form, schema, dataRef, submit }"
                    >
                        <TaxForm
                            ref="taxFormRef"
                            :errors="errors"
                            :form="form"
                            :schema="schema"
                            :data-ref="dataRef"
                            :submit="submit"
                            :edit-submit="editSubmit"
                        />
                        <div class="tab-content" id="myTabContent">
                            <div
                                class="tab-pane active show"
                                id="kt_tab_all"
                                role="tabpanel"
                            >
                                <TaxTable
                                    @registerEditSubmit="onRegisterEditSubmit"
                                />
                            </div>
                            <div class="tab-pane" role="tabpanel">
                                <TaxTable
                                    @registerEditSubmit="onRegisterEditSubmit"
                                />
                            </div>
                        </div>
                    </template>
                </AddTax>
            </template>
            <div>
                <Tabs />
            </div>
        </NuxtLayout>
    </div>
</template>
<script lang="ts" setup>
import Tabs from "~/features/taxes/tabs.vue";
import EditTaxSetup from "~/features/taxes/actions/edit-tax-setup.vue";
import AddTax from "~/features/taxes/actions/add-tax.vue";
import TaxSetupForm from "../../features/taxes/taxes-setup/form.vue";
import TaxForm from "../../features/taxes/taxes/form.vue";
import TaxTable from "../../features/taxes/taxes/table.vue";
import { ref } from "vue";
import type { TaxesAgency } from "~/types/taxes-agency";
const taxFormRef = ref(null);
const onSuccess = () => {
    taxFormRef.value?.toggleForm();
};

let editSubmitFn: (() => void) | null = null;

const onRegisterEditSubmit = (fn: () => void) => {
    editSubmitFn = fn;
};

const editSubmit = () => {
    editSubmitFn?.();
};

definePageMeta({
    permission: "Taxes.Taxes.View",
});
</script>
