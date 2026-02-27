<template>
    <AppPageTable
        endpoint="/api/products/products"
        :params="{
            query: {
                category: true,
                income: true,
                file: true,
                expense: true,
                vendor: true,
                depository: true,
                payable: true,
                receivable_account: true,
                sales_tax: true,
                withholding_tax: true,
            },
        }"
    >
        <template #columns>
            <el-table-column type="selection" width="55" />
            <el-table-column prop="file.url" label="Photo">
                <template #default="scope">
                    <div class="symbol symbol-50px me-3">
                        <img
                            :src="
                                scope.row.file?.url || '/media/misc/image.png'
                            "
                            class=""
                            alt=""
                        />
                    </div>
                </template>
            </el-table-column>

            <el-table-column prop="sku" label="SKU" />
            <el-table-column prop="name" label="Name">
                <template #default="scope">
                    <div class="flex flex-col gap-y-1">
                        <span class="text-blue-400 font-bold">{{
                            scope.row.name
                        }}</span>
                        <span class="text-slate-400 text-xs italic">{{
                            scope.row.description
                        }}</span>
                    </div>
                </template>
            </el-table-column>
            <el-table-column prop="category.name" label="Category" />
            <el-table-column prop="price" label="Price" class-name="text-end">
                <template #default="scope">
                    {{ null === scope.row.price ? "" : money(scope.row.price) }}
                </template>
            </el-table-column>
            <el-table-column prop="income.name" label="Income" />
            <el-table-column prop="depository.name" label="Depository" />

            <el-table-column
                prop="purchase_description"
                label="Purchase Description"
            />
            <el-table-column prop="expense.name" label="Expense" />
            <el-table-column prop="vendor.name" label="Vendor" />
            <el-table-column prop="cost" label="Cost" class-name="text-end">
                <template #default="scope">
                    {{ null === scope.row.cost ? "" : money(scope.row.cost) }}
                </template>
            </el-table-column>
            <el-table-column label="Actions" width="150" class-name="!static">
                <template #default="scope">
                    <div class="space-x-standard justify-end">
                        <AppPageDelete
                            v-if="!scope.row?.children?.length"
                            endpoint="/api/products/products"
                            :uuid="scope.row.uuid"
                            :title="scope.row.name"
                        />
                        <AppPageEdit
                            :id="scope.row.uuid"
                            :endpoint="`/api/products/products/${scope.row.uuid}`"
                            width="60%"
                            width-lg="30%"
                        >
                            <template #form="{ errors, syncForm, schema }">
                                <Tabs
                                    :key="scope.row.uuid"
                                    :errors="errors"
                                    :form="syncForm"
                                    :schema="schema"
                                    :data="{
                                        ...scope.row,
                                    }"
                                />
                            </template>
                            <template #drawerFooter="{ submit, cancel }">
                                <Button
                                    variant="light"
                                    class="btn btn-light ms-auto fw-semibold"
                                    icon="black-left"
                                    @click="cancel()"
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
                        </AppPageEdit>
                    </div>
                </template>
            </el-table-column>
        </template>
    </AppPageTable>
</template>

<script setup lang="tsx">
import Tabs from "./tabs.vue";
import { money } from "~/utils/helper";
</script>
