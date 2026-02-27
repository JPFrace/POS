<template>
    <!-- CONTACTS TAB -->
    <div class="card p-4 mb-6">
        <transition-group name="vanish" tag="div">
            <div
                v-for="(contact, index) in form.contacts"
                :key="index"
                :ref="(el) => (contactFields[index] = el)"
            >
                <div class="flex flex-wrap gap-4 items-end">
                    <!-- Name -->
                    <div class="flex-2 min-w-[250px]">
                        <span class="block text-sm font-semibold mb-2"
                            >Name</span
                        >
                        <Input
                            v-model="contact.name"
                            placeholder="Enter Name"
                            :is-valid="isValid(`contacts[${index}].name`)"
                        />
                    </div>

                    <!-- Contact No. -->
                    <div class="flex-1 min-w-[200px]">
                        <span class="block text-sm font-semibold mb-2"
                            >Contact No.</span
                        >
                        <Input
                            v-model="contact.contact_number"
                            placeholder="e.g. +63 917 123 4567"
                            :is-valid="
                                isValid(`contacts[${index}].contact_number`)
                            "
                        />
                    </div>
                </div>

                <!-- Second Row: Address + Remove Button -->
                <div class="flex items-center gap-2 mt-4">
                    <!-- Address Input -->
                    <div class="flex-1 flex flex-col">
                        <span class="block text-sm font-semibold mb-2"
                            >Address</span
                        >
                        <Input
                            v-model="contact.address"
                            placeholder="Enter Address"
                            :is-valid="isValid(`contacts[${index}].address`)"
                        />
                    </div>

                    <!-- Remove button on the right -->
                    <div class="flex-shrink-0 mt-9">
                        <button
                            class="text-red-600 hover:text-red-800 transition-colors"
                            @click.prevent="removeContact(index)"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <circle cx="12" cy="12" r="10" />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 12H8"
                                />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Separator -->
                <hr
                    v-if="index < form.contacts.length - 1"
                    class="my-4 border-gray-700 border-dashed"
                />
            </div>
        </transition-group>

        <!-- Add button, shown only if less than 3 contacts -->
        <div
            v-if="form.contacts.length < 3"
            :class="{ 'mt-4': form.contacts.length > 0 }"
        >
            <Button
                outline
                dashed
                variant="primary"
                label="Primary"
                class="flex items-center gap-2"
                @click.prevent="addNewContact"
            >
                <span class="flex items-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>
                    <span class="ml-2 text-sm">Add new contact</span>
                </span>
            </Button>
        </div>
    </div>
</template>

<script lang="ts" setup>
const props = defineProps<{
    form: any;
    isValid: (k: string) => any;
}>();

const emit = defineEmits<{
    (e: "update:form", value: any): void;
}>();

const { form, isValid } = toRefs(props);

const contactFields = ref<Array<HTMLElement | null>>([]);

const addNewContact = async (event: MouseEvent) => {
    // Blur the clicked element
    (event.currentTarget as HTMLElement)?.blur();

    if (!form.value.contacts) form.value.contacts = [];
    if (form.value.contacts.length < 3) {
        form.value.contacts.push({
            uuid: null,
            name: "",
            address: "",
            contact_number: "",
        });
        emit("update:form", form.value);
        await nextTick();
        const el = contactFields.value[form.value.contacts.length - 1];
        if (el) el.scrollIntoView({ behavior: "smooth", block: "start" });
    }
};

const removeContact = (index: number) => {
    if (!form.value.contacts) return;
    form.value.contacts.splice(index, 1);
    emit("update:form", form.value);
};
</script>

<style scoped>
/* Add and Remove buttons animation */
.vanish-enter-active,
.vanish-leave-active {
    transition: all 0.4s ease;
}

.vanish-enter-from,
.vanish-leave-to {
    opacity: 0;
    transform: translateY(30px);
}
</style>
