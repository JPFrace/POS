<template>
    <Checkbox id="agreement_term" v-model="model!.yes" :is-valid="model!.yes">
        <template #default>
            <label
                class="form-check-label"
                :class="{
                    'underline underline-offset-4 decoration-danger':
                        !model!.yes,
                }"
                for="agreement_term"
            >
                I have read and agree to the <a href="">Privacy Policy.</a>
            </label>
        </template>
    </Checkbox>
</template>

<script lang="ts" setup>
const model = defineModel<{ yes: boolean | null; valid: boolean | null }>();
</script>
