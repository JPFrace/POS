<template>
    <div class="flex flex-col gap-y-2">
        <h5>Basic</h5>
        <div class="flex gap-x-2">
            <Badge label="New" />
            <Badge variant="secondary" label="New" />
            <Badge variant="success" label="New" />
            <Badge variant="info" label="New" />
            <Badge variant="warning" label="New" />
            <Badge variant="danger" label="New" />
            <Badge variant="dark" label="New" />
        </div>
    </div>
    <div class="flex flex-col gap-y-2">
        <h5>Light Style</h5>
        <div class="flex gap-x-2">
            <Badge type="light" label="New" />
            <Badge type="light" variant="secondary" label="New" />
            <Badge type="light" variant="success" label="New" />
            <Badge type="light" variant="info" label="New" />
            <Badge type="light" variant="warning" label="New" />
            <Badge type="light" variant="danger" label="New" />
            <Badge type="light" variant="dark" label="New" />
        </div>
    </div>
    <div class="flex flex-col gap-y-2">
        <h5>Square</h5>
        <div class="flex gap-x-2">
            <Badge type="light" label="5" />
            <Badge type="square" variant="secondary" label="5" />
            <Badge type="square" variant="success" label="5" />
            <Badge type="square" variant="info" label="5" />
            <Badge type="square" variant="warning" label="5" />
            <Badge type="square" variant="danger" label="5" />
            <Badge type="square" variant="dark" label="5" />
        </div>
    </div>
    <div class="flex flex-col gap-y-2">
        <h5>Circle</h5>
        <div class="flex gap-x-2">
            <Badge type="circle" label="5" />
            <Badge type="circle" variant="secondary" label="5" />
            <Badge type="circle" variant="success" label="5" />
            <Badge type="circle" variant="info" label="5" />
            <Badge type="circle" variant="warning" label="5" />
            <Badge type="circle" variant="danger" label="5" />
            <Badge type="circle" variant="dark" label="5" />
        </div>
    </div>
    <div class="flex flex-col gap-y-2">
        <h5>Outline</h5>
        <div class="flex gap-x-2">
            <Badge type="circle" label="5" />
            <Badge type="circle" variant="secondary" label="5" outline />
            <Badge type="circle" variant="success" label="5" outline />
            <Badge type="circle" variant="info" label="5" outline />
            <Badge type="circle" variant="warning" label="5" outline />
            <Badge type="circle" variant="danger" label="5" outline />
            <Badge type="circle" variant="dark" label="5" outline />
        </div>
    </div>
    <div class="flex flex-col gap-y-2">
        <h5>Custom Label</h5>
        <div class="flex gap-x-2">
            <Badge>
                <template #default>
                    <KTIcon
                        icon-name="abstract-36"
                        icon-class="fs-1 text-danger mr-2"
                    />
                    Custom
                </template>
            </Badge>
        </div>
    </div>
</template>
