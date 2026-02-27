<template>
    <div>
        <NuxtLayout>
            <template #toolbarLeft>
                <Input group placeholder="Search...">
                    <template #prepend>
                        <i class="fas fa-search"/>
                    </template>
                </Input>
            </template>
            <template #toolbarRight>
                <Button variant="primary">Create</Button>
                <Button variant="success">Update</Button>
                <Button variant="info">Edit</Button>
                <Button variant="danger">Delete</Button>
                <Button variant="warning">Cancel</Button>
            </template>
            <h1 class="mb-10">Re-usable components</h1>
            <div class="flex flex-col gap-y-6">
                <div class="grid grid-cols-3 gap-x-10">
                    <Form />
                </div>
                <div class="grid grid-cols-3 gap-x-10">
                    <div class="flex flex-col gap-y-4">
                        <h2>Modal</h2>
                        <Modals />
                        <h2>Alert</h2>
                        <Alerts />
                    </div>
                    <div class="flex flex-col gap-y-4">
                        <h2>Badges</h2>
                        <Badges />
                        <h2>Drawer</h2>
                        <Drawer />
                    </div>
                    <div class="flex flex-col gap-y-4">
                        <h2>Notifications</h2>
                        <Notification />
                        <h2>Confirmation</h2>
                        <Confirmation />
                        <h2>Message</h2>
                        <Message />
                    </div>
                </div>
                <div class="flex gap-x-4 gap-y-4">
                    <div class="flex flex-col gap-y-4">
                        <h2>Buttons</h2>
                        <div class="grid grid-cols-2 gap-x-10">
                            <Buttons />
                        </div>
                    </div>
                </div>
                <div class="flex gap-x-4 gap-y-4">
                    <div class="w-full flex flex-col gap-y-4">
                        <h2>DataTable</h2>
                        <DataTable />
                    </div>
                </div>
                <div>
                    <p>Cards</p>
                    <div class="grid grid-cols-3 gap-x-10 gap-y-4">
                        <Cards />
                    </div>
                </div>
            </div>
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import Buttons from "./components/buttons.vue";
import Badges from "./components/badges.vue";
import Alerts from "./components/alerts.vue";
import Modals from "./components/modals.vue";
import Form from "./components/form.vue";
import Cards from "./components/cards.vue";
import Notification from "./components/notification.vue";
import Confirmation from "./components/confirmation.vue";
import Message from "./components/message.vue";
import DataTable from "./components/datatable.vue";
import Drawer from "./components/drawer.vue";
</script>
