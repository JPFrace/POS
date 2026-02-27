<template>
    <div class="flex justify-between items-center mt-4 mb-4">
        <div class="max-w-xs">
            <Input group placeholder="Search">
                <template #default>
                    <InputNative
                        v-model="search"
                        placeholder="Search purchase orders..."
                        title="Type and enter key to search"
                        @keyup.enter="click"
                    />
                </template>
                <template #append>
                    <div>
                        <KTIcon
                            title="Click me to search"
                            icon-name="click"
                            icon-class="fs-2 cursor-pointer"
                            @click="click"
                        />
                    </div>
                </template>
            </Input>
        </div>
        <div class="mr-8">
            <NuxtLink to="/business/purchase-orders">
                <el-button type="primary" size="large">
                    <el-icon class="mr-2"><Plus /></el-icon>
                    New Purchase Order
                </el-button>
            </NuxtLink>
        </div>
    </div>
    <AppPageTable
        v-model:params="params"
        :prefix-key="tab"
        endpoint="/api/business/orders"
        :preserve-expanded-content="true"
        class="min-w-[1000px]"
    >
        <template #columns>
            <el-table-column type="selection" width="40" />
            <el-table-column type="expand">
                <template #default="props">
                    <div class="w-1/2 px-8 py-4">
                        <el-table
                            :data="props.row.details"
                            :border="true"
                            show-summary
                            :summary-method="summary"
                            sum-text="Total"
                        >
                            <el-table-column
                                prop="product.name"
                                label="Product"
                            >
                                <template #default="scope">
                                    <div class="flex flex-col gap-y-1">
                                        <span class="text-blue-400 font-bold">{{
                                            scope.row.product_name
                                        }}</span>
                                        <span
                                            class="text-slate-400 text-xs italic"
                                            >{{
                                                scope.row.product_description
                                            }}</span
                                        >
                                    </div>
                                </template>
                            </el-table-column>
                            <el-table-column
                                label="Quantity"
                                prop="quantity"
                                class-name="text-end"
                            >
                                <template #default="scope">
                                    <div class="flex flex-col gap-y-1">
                                        {{ money(scope.row.quantity) }}
                                    </div>
                                </template>
                            </el-table-column>
                            <el-table-column
                                label="Rate"
                                prop="rate"
                                class-name="text-end"
                            >
                                <template #default="scope">
                                    <div class="flex flex-col gap-y-1">
                                        {{ money(scope.row.rate, 2) }}
                                    </div>
                                </template>
                            </el-table-column>
                            <el-table-column
                                label="Sub Total"
                                prop="sub_total"
                                class-name="text-end"
                            >
                                <template #default="scope">
                                    <div class="flex flex-col gap-y-1">
                                        {{ money(scope.row.sub_total, 2) }}
                                    </div>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </template>
            </el-table-column>
            <el-table-column
                prop="order_no"
                label="Order No"
                min-width="30"
                sortable
            >
                <template #default="{ row }">
                    <NuxtLink
                        :to="`/business/purchase-orders/${row.uuid}`"
                        target="_blank"
                        class="font-bold !text-blue-500 hover:!underline underline-offset-2"
                        >{{ row.order_no }}
                    </NuxtLink>
                </template>
            </el-table-column>
            <el-table-column
                prop="vendor_name"
                label="Vendor"
                min-width="80"
                sortable
            >
                <template #default="{ row }">
                    <div class="flex flex-col gap-y-1">
                        <span class="font-bold">{{ row.vendor_name }}</span>
                        <div class="flex items-center text-sm">
                            <span
                                :class="
                                    row.vendor.type_label?.toLowerCase() ===
                                    'customer'
                                        ? 'text-blue-500'
                                        : 'text-cyan-500'
                                "
                            >
                                {{ row.vendor.type_label }}
                            </span>
                            <el-divider direction="vertical" />
                            <span class="italic text-[#7239f4]">
                                {{ row.vendor_idno }}
                            </span>
                        </div>
                    </div>
                </template>
            </el-table-column>
            <el-table-column prop="date" label="Date" min-width="30" sortable />
            <el-table-column
                prop="amount"
                label="Amount"
                min-width="30"
                sortable
            >
                <template #default="scope">
                    <div class="flex flex-col gap-y-1">
                        ₱{{ money(scope.row.amount, 2) }}
                    </div>
                </template>
            </el-table-column>
            <el-table-column
                prop="status"
                label="Status"
                min-width="30"
                sortable
            >
                <template #default="{ row }">
                    <div class="flex justify-between items-center">
                        <el-tag
                            round
                            effect="dark"
                            :type="getStatusType(row.status)"
                        >
                            {{ row.status }}
                        </el-tag>
                    </div>
                </template>
            </el-table-column>

            <el-table-column
                label="Actions"
                min-width="20"
                class-name="!static"
            >
                <template #default="{ row }">
                    <div class="flex space-x-standard mr-2">
                        <NuxtLink :to="`/business/purchase-orders/${row.uuid}`">
                            <KTIcon
                                title="Edit Record"
                                icon-name="notepad-edit"
                                icon-type="outline"
                                icon-class="!text-3xl cursor-pointer !text-blue-500 hover:!text-blue-700 dark:hover:!text-blue-400"
                            />
                        </NuxtLink>
                        <AppPageDelete
                            :id="tab"
                            endpoint="/api/business/orders"
                            :uuid="row.uuid"
                            :title="`Order No: ${row.order_no}`"
                        />
                    </div>
                </template>
            </el-table-column>
        </template>
    </AppPageTable>
</template>

<script setup lang="ts">
import moment from "moment";
import { h } from "vue";
import type { VNode } from "vue";
import type { TableColumnCtx } from "element-plus";
import type { OrderItem } from "~/types/order";
import { money } from "~/utils/helper";
import { Plus } from "@element-plus/icons-vue";

const { send, receive } = usePageEvent();

const search = ref();
const dates = defineModel<Date[]>("dates", {
    default: () => [new Date(), new Date()],
});
const tab = ref("purchase-orders");

const params = ref({
    query: {
        vendor: true,
        details: true,
        date_between: [
            moment(dates.value[0]).format("YYYY-MM-DD"),
            moment(dates.value[1]).format("YYYY-MM-DD"),
        ],
        search: undefined as string | undefined,
    },
    order_by: JSON.stringify(["date", "desc"]),
});

const getStatusType = (
    status: string
): "success" | "warning" | "danger" | "info" | "primary" => {
    const statusMap: Record<
        string,
        "success" | "warning" | "danger" | "info" | "primary"
    > = {
        completed: "success",
        open: "info",
    };
    return statusMap[status?.toLowerCase()] || "info";
};

interface SummaryMethodProps<T = OrderItem> {
    columns: TableColumnCtx<T>[];
    data: T[];
}

const summary = (param: SummaryMethodProps) => {
    const { columns, data } = param;
    const sums: (string | VNode)[] = [];
    columns.forEach((column, index) => {
        if (index === 0) {
            sums[index] = h("div", { style: { textDecoration: "underline" } }, [
                "Total",
            ]);
            return;
        }

        const property = column.property as keyof OrderItem;
        const values = data.map((item) => Number(item[property]));

        if (!values.every((value) => Number.isNaN(value))) {
            // If the column is 'quantity', do not append 'PHP'
            if (column.property === "quantity") {
                sums[index] = `${values.reduce((prev, curr) => {
                    const value = Number(curr);
                    if (!Number.isNaN(value)) {
                        return prev + curr;
                    } else {
                        return prev;
                    }
                }, 0)}`;
            } else {
                sums[index] = `PHP ${money(
                    values.reduce((prev, curr) => {
                        const value = Number(curr);
                        if (!Number.isNaN(value)) {
                            return prev + curr;
                        } else {
                            return prev;
                        }
                    }, 0),
                    2
                )}`;
            }
        } else {
            sums[index] = "N/A";
        }
    });

    return sums;
};

const click = () => {
    params.value = {
        ...params.value,
        query: {
            ...params.value.query,
            search: search.value,
        },
    };
};

watch(dates, (value) => {
    params.value = {
        ...params.value,
        query: {
            ...params.value.query,
            date_between: [
                moment(value[0]).format("YYYY-MM-DD"),
                moment(value[1]).format("YYYY-MM-DD"),
            ],
        },
    };
});

onMounted(() => {
    receive("refresh", (id: any) => {
        if (id && id !== tab.value) {
            return;
        }
        params.value = { ...params.value };
    });
});
</script>
