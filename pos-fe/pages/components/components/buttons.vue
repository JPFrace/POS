<template>
    <div class="flex flex-col gap-y-4">
        <div class="flex flex-col gap-y-2">
            <h5>Basic</h5>
            <div class="flex flex-wrap gap-y-2 gap-x-2">
                <Button variant="light" label="Light" />
                <Button variant="primary" label="Primary" />
                <Button variant="secondary" label="Secondary" />
                <Button variant="success" label="Success" />
                <Button variant="info" label="Info" />
                <Button variant="warning" label="Warning" />
                <Button variant="danger" label="Danger" />
                <Button variant="dark" label="Dark" />
            </div>
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Light</h5>
            <div class="flex flex-wrap gap-y-2 gap-x-2">
                <Button
                    light
                    variant="light"
                    label="Light"
                    :text-color="{ color: 'text-white' }"
                />
                <Button light variant="primary" label="Primary" />
                <Button light variant="secondary" label="Secondary" />
                <Button light variant="success" label="Success" />
                <Button light variant="info" label="Info" />
                <Button light variant="warning" label="Warning" />
                <Button light variant="danger" label="Danger" />
                <Button light variant="dark" label="Dark" />
            </div>
        </div>

        <div class="flex flex-col gap-y-2">
            <h5>Outline and Dashed</h5>
            <div class="flex flex-wrap gap-y-2 gap-x-2">
                <Button
                    outline
                    dashed
                    variant="light"
                    :text-color="{ color: 'text-white' }"
                    label="Light"
                />
                <Button outline dashed variant="primary" label="Primary" />
                <Button outline dashed variant="secondary" label="Secondary" />
                <Button outline dashed variant="success" label="Success" />
                <Button outline dashed variant="info" label="Info" />
                <Button outline dashed variant="warning" label="Warning" />
                <Button outline dashed variant="danger" label="Danger" />
                <Button outline dashed variant="dark" label="Dark" />
            </div>
        </div>
    </div>
    <div class="flex flex-col gap-y-4">
        <div class="flex flex-col gap-y-2">
            <h5>Basic</h5>
            <div class="flex flex-wrap gap-y-2 gap-x-2">
                <Button hover elevate variant="primary">Elevate up</Button>
                <Button hover elevate-down variant="secondary"
                    >Elevate down</Button
                >
                <Button hover scale variant="success">Scale</Button>
                <Button hover rotate variant="info">Rotate start</Button>
                <Button hover rotate-end variant="warning">Rotate end</Button>
            </div>
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Icon & Text Color</h5>
            <div class="flex flex-wrap gap-y-2 gap-x-2">
                <Button
                    active
                    :icon-color="{
                        color: 'danger',
                        active: 'success',
                    }"
                    icon="graph-3"
                    variant="primary"
                    label="Icon and Active"
                />
                <Button
                    active
                    :icon-color="{
                        color: 'danger',
                        active: 'text-white',
                    }"
                    :text-color="{
                        color: 'danger',
                        active: 'text-white',
                    }"
                    icon="graph-3"
                    variant="success"
                >
                    Icon & Text and Active
                </Button>
            </div>
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Progress</h5>
            <div class="flex flex-wrap gap-y-2 gap-x-2">
                <Button
                    active
                    :icon-color="{
                        color: 'danger',
                        active: 'success',
                    }"
                    icon="graph-3"
                    variant="primary"
                    label="Default with icon"
                />
                <Button
                    active
                    :icon-color="{
                        color: 'text-white',
                        active: 'success',
                    }"
                    :text-color="{
                        color: 'text-white',
                        active: 'success',
                    }"
                    icon="graph-3"
                    variant="primary"
                    loading
                    loading-text="Loading text"
                >
                    Enabled
                </Button>
                <Button
                    active
                    :icon-color="{
                        color: 'text-white',
                        active: 'success',
                    }"
                    :text-color="{
                        color: 'text-white',
                        active: 'success',
                    }"
                    icon="graph-3"
                    variant="success"
                    loading
                    label="Submit"
                />
            </div>
        </div>
    </div>
</template>
