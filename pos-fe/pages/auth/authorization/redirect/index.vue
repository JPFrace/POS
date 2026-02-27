<template>
    <div>
        <NuxtLayout name="auth">
            <!--begin::Wrapper-->
            <div
                class="w-lg-500px p-10 flex items-center justify-center gap-x-4"
            >
                <div
                    v-if="success == true"
                    class="w-full flex items-center justify-center gap-x-4"
                >
                    <KTIcon icon-name="key" icon-class="fs-2x" />
                    <h3 class="text-green-400 p-0 m-0">
                        Authorization success!
                    </h3>
                </div>
                <div
                    v-else-if="success == null"
                    class="w-full flex items-center justify-center gap-x-4"
                >
                    <KTIcon icon-name="loading" icon-class="fs-2x fa fa-spin" />
                    <h3 class="text-slate-400 p-0 m-0">
                        Authorizing...Please wait.
                    </h3>
                </div>
                <div
                    v-else
                    class="w-full flex items-center justify-center gap-x-4"
                >
                    <KTIcon icon-name="lock" icon-class="fs-2x" />
                    <h3 class="text-red-400 p-0 m-0">
                        Authorization failed. Go to<a href="/auth/sign-in"
                            >Login</a
                        >
                        page.
                    </h3>
                </div>
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

const route = useRoute();
const success = ref<boolean | null>(null);

const authorization = async () => {
    const { token } = route.query;

    try {
        await useClient("/sanctum/csrf-cookie", {
            method: "GET",
        });

        await useClient("/api/auth/google/authorization", {
            method: "POST",
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
        });

        setTimeout(() => {
            success.value = true;
            setTimeout(() => {
                reloadNuxtApp({ path: "/dashboard" });
            }, 2000);
        }, 500);
    } catch {
        success.value = false;
    }
};

onMounted(() => {
    authorization();
});
</script>
