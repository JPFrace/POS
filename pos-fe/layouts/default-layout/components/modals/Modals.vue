<template>
    <KTUpgradePlanModal />
    <KTCreateAppModal />
    <KTNewTargetModal />
    <KTViewUsersModal />
    <KTInviteFriendsModal />
</template>

<script lang="ts">
import { defineComponent } from "vue";
import KTUpgradePlanModal from "~/layouts/theme/modals/general/UpgradePlanModal.vue";
import KTCreateAppModal from "~/layouts/theme/modals/wizards/CreateAppModal.vue";
import KTNewTargetModal from "~/layouts/theme/modals/forms/NewTargetModal.vue";
import KTViewUsersModal from "~/layouts/theme/modals/general/ViewUsersModal.vue";
import KTInviteFriendsModal from "~/layouts/theme/modals/general/InviteFriendsModal.vue";

export default defineComponent({
    name: "GlobalModals",
    components: {
        KTUpgradePlanModal,
        KTCreateAppModal,
        KTNewTargetModal,
        KTViewUsersModal,
        KTInviteFriendsModal,
    },
});
</script>
