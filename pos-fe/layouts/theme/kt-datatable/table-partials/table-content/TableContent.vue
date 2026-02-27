<template>
    <div class="table-responsive">
        <table
            :class="[loading && 'overlay overlay-block']"
            class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
        >
            <TableHeadRow
                :checkbox-enabled-value="check"
                :checkbox-enabled="checkboxEnabled"
                :sort-label="sortLabel"
                :sort-order="sortOrder"
                :header="header"
                @on-sort="onSort"
                @on-select="selectAll"
            />
            <TableBodyRow
                v-if="data.length !== 0"
                :currently-selected-items="selectedItems"
                :data="data"
                :header="header"
                :checkbox-enabled="checkboxEnabled"
                :checkbox-label="checkboxLabel"
                @on-select="itemsSelect"
            >
                <template v-for="(_, name) in $slots" #[name]="{ row: item }">
                    <slot :name="name" :row="item" />
                </template>
            </TableBodyRow>
            <template v-else>
                <tr class="odd">
                    <td colspan="7" class="dataTables_empty">
                        {{ emptyTableText }}
                    </td>
                </tr>
            </template>
            <Loading v-if="loading" />
        </table>
    </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref, watch } from "vue";
import TableHeadRow from "~/layouts/theme/kt-datatable/table-partials/table-content/table-head/TableHeadRow.vue";
import TableBodyRow from "~/layouts/theme/kt-datatable/table-partials/table-content/table-body/TableBodyRow.vue";
import Loading from "~/layouts/theme/kt-datatable/table-partials/Loading.vue";
import type { Sort } from "~/layouts/theme/kt-datatable/table-partials/models";

export default defineComponent({
    name: "TableBody",
    components: {
        TableHeadRow,
        TableBodyRow,
        Loading,
    },
    props: {
        header: { type: Array, required: true },
        data: { type: Array, required: true },
        emptyTableText: { type: String, default: "No data found" },
        sortLabel: { type: String, required: false, default: null },
        sortOrder: {
            type: String as () => "asc" | "desc",
            required: false,
            default: "asc",
        },
        checkboxEnabled: { type: Boolean, required: false, default: false },
        checkboxLabel: { type: String, required: false, default: "id" },
        loading: { type: Boolean, required: false, default: false },
    },
    emits: ["on-sort", "on-items-select"],
    setup(props, { emit }) {
        const selectedItems = ref<Array<unknown>>([]);
        const allSelectedItems = ref<Array<unknown>>([]);
        const check = ref<boolean>(false);

        watch(
            () => props.data,
            () => {
                selectedItems.value = [];
                allSelectedItems.value = [];
                check.value = false;
                // eslint-disable-next-line
                props.data.forEach((item: any) => {
                    if (item[props.checkboxLabel]) {
                        allSelectedItems.value.push(item[props.checkboxLabel]);
                    }
                });
            }
        );

        // eslint-disable-next-line
        const selectAll = (checked: any) => {
            check.value = checked;
            if (checked) {
                selectedItems.value = [
                    ...new Set([
                        ...selectedItems.value,
                        ...allSelectedItems.value,
                    ]),
                ];
            } else {
                selectedItems.value = [];
            }
        };

        //eslint-disable-next-line
        const itemsSelect = (value: any) => {
            selectedItems.value = [];
            //eslint-disable-next-line
            value.forEach((item: any) => {
                if (!selectedItems.value.includes(item))
                    selectedItems.value.push(item);
            });
        };

        const onSort = (sort: Sort) => {
            emit("on-sort", sort);
        };

        watch(
            () => [...selectedItems.value],
            (currentValue) => {
                if (currentValue) {
                    emit("on-items-select", currentValue);
                }
            }
        );

        onMounted(() => {
            selectedItems.value = [];
            allSelectedItems.value = [];
            check.value = false;
            // eslint-disable-next-line
            props.data.forEach((item: any) => {
                if (item[props.checkboxLabel]) {
                    allSelectedItems.value.push(item[props.checkboxLabel]);
                }
            });
        });

        return {
            onSort,
            selectedItems,
            selectAll,
            itemsSelect,
            check,
        };
    },
});
</script>
