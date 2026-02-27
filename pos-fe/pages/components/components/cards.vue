<template>
    <div class="space-y-4">
        <Card title="Basic">
            Description
            <template #footer>
                <div class="text-end space-x-4">
                    <Button variant="light" label="Cancel" />
                    <Button label="Submit" />
                </div>
            </template>
        </Card>

        <Card title="Scrollable" scrollable height="h-200px">
            Lorem Ipsum is simply dummy text of the printing and typesetting
            industry. Lorem Ipsum has been the industry's standard dummy text
            ever since the 1500s, when an unknown printer took a galley of type
            and scrambled it to make a type specimen book. It has survived not
            only five centuries, but also the leap into electronic typesetting,
            remaining essentially unchanged. It was popularised in the 1960s
            with the release of Letraset sheets containing Lorem Ipsum passages,
            and more recently with desktop publishing software like Aldus
            PageMaker including versions of Lorem Ipsum. Lorem Ipsum is simply
            dummy text of the printing and typesetting industry. Lorem Ipsum has
            been the industry's standard dummy text ever since the 1500s, when
            an unknown printer took a galley of type and scrambled it to make a
            type specimen book. It has survived not only five centuries, but
            also the leap into electronic typesetting, remaining essentially
            unchanged. It was popularised in the 1960s with the release of
            Letraset sheets containing Lorem Ipsum passages, and more recently
            with desktop publishing software like Aldus PageMaker including
            versions of Lorem Ipsum. Lorem Ipsum is simply dummy text of the
            printing and typesetting industry. Lorem Ipsum has been the
            industry's standard dummy text ever since the 1500s, when an unknown
            printer took a galley of type and scrambled it to make a type
            specimen book. It has survived not only five centuries, but also the
            leap into electronic typesetting, remaining essentially unchanged.
            It was popularised in the 1960s with the release of Letraset sheets
            containing Lorem Ipsum passages, and more recently with desktop
            publishing software like Aldus PageMaker including versions of Lorem
            Ipsum.
            <template #footer>
                <div class="text-end space-x-4">
                    <Button variant="light" label="Cancel" />
                    <Button label="Submit" />
                </div>
            </template>
        </Card>
    </div>
    <div class="space-y-4">
        <Card title="Collapsible" collapsible
            >Description
            <template #footer>
                <div class="text-end space-x-4">
                    <Button variant="light" label="Cancel" />
                    <Button label="Submit" />
                </div> </template
        ></Card>

        <Card title="Linkable" linkable no-footer no-header
            >Description
            <template #footer>
                <div class="text-end space-x-4">
                    <Button variant="light" label="Cancel" />
                    <Button label="Submit" />
                </div> </template
        ></Card>

        <Card title="Removable" removable
            >Description
            <template #footer>
                <div class="text-end space-x-4">
                    <Button variant="light" label="Cancel" />
                    <Button label="Submit" />
                </div> </template
        ></Card>

        <Card title="Flush Borders" flush
            >Description
            <template #footer>
                <div class="text-end space-x-4">
                    <Button variant="light" label="Cancel" />
                    <Button label="Submit" />
                </div> </template
        ></Card>
    </div>
    <div class="space-y-4">
        <Card title="Reset Side Paddings" reset-side-paddings
            >Description
            <template #footer>
                <div class="text-end space-x-4">
                    <Button variant="light" label="Cancel" />
                    <Button label="Submit" />
                </div> </template
        ></Card>
        <Card title="Bordered" bordered
            >Description
            <template #footer>
                <div class="text-end space-x-4">
                    <Button variant="light" label="Cancel" />
                    <Button label="Submit" />
                </div> </template
        ></Card>
        <Card title="Dashed Style" dashed
            >Description
            <template #footer>
                <div class="text-end space-x-4">
                    <Button variant="light" label="Cancel" />
                    <Button label="Submit" />
                </div> </template
        ></Card>
    </div>
</template>
