<template>
    <div v-if="visible" class="modal-mask" @click.self="close">
        <div class="modal-wrapper">
            <div class="modal-container">
                <!-- X Close Icon Button -->
                <button
                    type="button"
                    class="modal-close-btn mt-8 mr-4"
                    aria-label="Close"
                    @click="close"
                >
                    <KTIcon icon-name="abstract-11" icon-class="fs-2" />
                </button>
                <h4 class="mt-8">Audit Trail Logs</h4>
                <form class="space-y-4 py-1 px-1">
                    <div class="mt-6 space-y-2 border-t pt-8">
                        <Input
                            v-model="logs.user.name"
                            float
                            label="User Name"
                            readonly
                        />
                        <Input
                            v-model="logs.user.role"
                            float
                            label="User Role"
                            readonly
                        />
                        <Input
                            v-model="logs.user_type"
                            float
                            label="User Type"
                            readonly
                        />
                        <Input
                            v-model="logs.event"
                            float
                            label="Event"
                            readonly
                        />
                        <Input
                            v-model="logs.auditable_type"
                            float
                            label="Auditable Type"
                            readonly
                        />
                        <Input
                            v-model="logs.old_values"
                            float
                            label="Old Values"
                            readonly
                        />
                        <Input
                            v-model="logs.new_values"
                            float
                            label="New Values"
                            readonly
                        />
                        <Input v-model="logs.url" float label="URL" readonly />
                        <Input
                            v-model="logs.ip_address"
                            float
                            label="IP Address"
                            readonly
                        />
                        <Input
                            v-model="logs.user_agent"
                            float
                            label="User Agent"
                            readonly
                        />
                        <Input
                            v-model="logs.tags"
                            float
                            label="Tags"
                            readonly
                        />
                        <Input
                            v-model="logs.created_at"
                            float
                            label="Created At"
                            readonly
                        />
                        <Input
                            v-model="logs.updated_at"
                            float
                            label="Updated At"
                            readonly
                        />
                    </div>
                </form>
                <div class="modal-footer">
                    <Button type="light" data-bs-dismiss="modal" @click="close">
                        Close
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, watch, defineEmits } from "vue";
import type { AuditTrails } from "~/types/audit-trails";

interface Props {
    data?: AuditTrails;
}

const props = defineProps<Props>();
const emit = defineEmits(["close"]);

const logs = ref<AuditTrails>({
    user_type: "",
    event: "",
    auditable_type: "",
    old_values: "",
    new_values: "",
    url: "",
    ip_address: "",
    user_agent: "",
    tags: "",
    created_at: "",
    updated_at: "",
    user: { name: "", role: "" },
});

const visible = ref(true);

function close() {
    visible.value = false;
    emit("close");
}

watch(
    () => props.data,
    (val) => {
        if (val) logs.value = { ...logs.value, ...val };
    },
    { immediate: true }
);
</script>

<style scoped>
.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-container {
    background: #17181d;
    border-radius: 4px;
    width: 900px;
    max-width: 98vw;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
}
/* X Close Button Styling */
.modal-close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 2rem;
    color: gray;
    background: none;
    border: none;
    cursor: pointer;
    z-index: 10;
    line-height: 1;
    padding: 0;
    transition: color 0.2s;
}
.modal-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 1rem;
}

.border-t {
    border-top: 1px solid #6b7280 !important;
}
</style>
