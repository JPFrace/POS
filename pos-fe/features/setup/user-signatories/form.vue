<template>
    <form class="space-y-4 px-1 py-1">
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Name</span>
            <Input
                v-model="form.name"
                placeholder="Enter Name"
                :is-valid="isValid('name')"
            />
        </div>
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Position</span>
            <Select
                v-model:data="positions"
                v-model:selected="form.position"
                url="api/security/user-positions"
                :map-result="
                    (result: any) =>
                        result.data.map((row: any) => ({
                            id: row.uuid,
                            value: row.uuid,
                            label: row.title,
                        }))
                "
                :map-query="
                    (search: any) => ({
                        query: { name: search },
                    })
                "
                clearable
                remote
                loading
                placeholder="Select..."
            />
        </div>
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Department</span>
            <Select
                v-model:data="departments"
                v-model:selected="form.department"
                url="/api/setup/departments"
                :map-result="
                    (result: any) =>
                        result.data.map((row: any) => ({
                            id: row.uuid,
                            value: row.uuid,
                            label: row.name,
                        }))
                "
                :map-query="
                    (search: any) => ({
                        query: { name: search },
                    })
                "
                clearable
                remote
                loading
                placeholder="Select..."
            />
        </div>
        <div class="flex flex-col">
            <span class="text-sm font-semibold mb-2">Upload E-Signature</span>
            <input
                ref="fileInput"
                type="file"
                @change="handleFileChange"
                class="hidden"
                accept="image/*"
            />
            <div class="flex gap-4">
                <Button
                    variant="dark"
                    type="button"
                    @click="fileInput?.click()"
                >
                    <KTIcon
                        :icon-name="
                            selectedFileName ? 'update-file' : 'add-files'
                        "
                        icon-type="outline"
                        icon-class="fs-3"
                    />
                    {{ selectedFileName || "Choose File" }}
                </Button>
                <Button
                    v-if="selectedFileName"
                    light
                    variant="danger"
                    type="button"
                    @click="clearFile"
                >
                    <KTIcon
                        icon-name="delete-files"
                        icon-type="outline"
                        icon-class="fs-3"
                    />
                    Remove
                </Button>
            </div>
        </div>
    </form>
</template>

<script lang="ts" setup>
import type { Option } from "~/types/form";
import type { UserSignatories } from "~/types/user-signatories";

const { yup } = useYup();

const fileInput = ref<HTMLInputElement | null>(null);

const files = ref();

interface Props {
    errors?: object;
    schema: Function;
    form: Function;
    data?: UserSignatories;
}

const props = defineProps<Props>();

const departments = ref<Option[]>();
const positions = ref<Option[]>();
const form = ref<UserSignatories>({
    name: "",
    position: null,
    department: null,
    e_signature: null,
});

const Yup = yup();

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? (props?.errors as any)[key]?.length <= 0
            : null
        : null;

const setForm = (value: any) => {
    form.value = {
        name: value.name ?? "",
        position: value.position_id ?? "",
        department: value.department_id ?? "",
        e_signature: value.e_signature ?? "",
    };
    if (value.department) {
        departments.value = [
            {
                id: value.department.uuid,
                value: value.department.uuid,
                label: value.department.name,
            } as Option,
        ];

        form.value.department = departments.value.filter(
            (d) => d.id == value.department.uuid
        )[0] as Option;
    }
    if (value.position) {
        positions.value = [
            {
                id: value.position.uuid,
                value: value.position.uuid,
                label: value.position.name,
            } as Option,
        ];

        form.value.position = positions.value.filter(
            (d) => d.id == value.position.uuid
        )[0] as Option;
    }
};

watch(
    form,
    (value) => {
        props.form(value);
    },
    {
        deep: true,
    }
);

onMounted(() => {
    if (props.data) {
        setForm(props.data);
    }

    props.schema(
        Yup.object().shape({
            name: Yup.string().required(),
            position: Yup.object().required(),
            department: Yup.object().required(),
            e_signature: Yup.mixed().notRequired(),
        })
    );
});

const selectedFileName = ref<string>("");

function handleFileChange() {
    files.value = fileInput.value?.files;
    if (files.value?.[0]) {
        selectedFileName.value = files.value[0].name;
        uploadImage();
    }
}

function uploadImage() {
    const file = files.value[0];

    const reader = new FileReader();
    reader.onload = (e) => {
        form.value.e_signature = e.target?.result as string;
    };
    reader.readAsDataURL(file);
}

function clearFile() {
    if (fileInput.value) {
        fileInput.value.value = "";
    }
    files.value = null;
    selectedFileName.value = "";
    form.value.e_signature = null;
}
</script>
