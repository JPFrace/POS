<template>
    <div>
        <NuxtLayout name="auth">
            <!--begin::Wrapper-->
            <div
                class="w-lg-500px p-10 flex items-center justify-center gap-x-4"
            >
                <KTIcon icon-name="lock" icon-class="fs-2x text-red-500" />
                <h3 class="text-red-400 p-0 m-0">
                    Authorization failed. Go to
                    <a href="/auth/sign-in">Login</a> page.
                </h3>
            </div>
            <!--end::Wrapper-->
        </NuxtLayout>
    </div>
</template>
<script lang="ts" setup>
definePageMeta({
    sanctum: {
        guestOnly: true,
    },
});
</script>
