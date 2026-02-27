<template>
    <div class="space-x-4">
        <Button data-bs-toggle="modal" data-bs-target="#modal-basic"
            >Show Modal</Button
        >
        <Button data-bs-toggle="modal" data-bs-target="#modal-scrollable"
            >Show Scrollable Modal</Button
        >
    </div>

    <Modal id="modal-basic" title="Add new" centered>
        <template #body>
            <Input placeholder="Name" />
        </template>
        <template #footer>
            <Button type="light" data-bs-dismiss="modal">Cancel</Button>
            <Button>Save</Button>
        </template>
    </Modal>

    <Modal id="modal-scrollable" title="Add new" centered scrollable>
        <template #body>
            This is some placeholder content to show the scrolling behavior for
            modals. Instead of repeating the text the modal, we use an inline
            style set a minimum height, thereby extending the length of the
            overall modal and demonstrating the overflow scrolling. When content
            becomes longer than the height of the viewport, scrolling will move
            the modal as needed. This is some placeholder content to show the
            scrolling behavior for modals. Instead of repeating the text the
            modal, we use an inline style set a minimum height, thereby
            extending the length of the overall modal and demonstrating the
            overflow scrolling. When content becomes longer than the height of
            the viewport, scrolling will move the modal as needed. This is some
            placeholder content to show the scrolling behavior for modals.
            Instead of repeating the text the modal, we use an inline style set
            a minimum height, thereby extending the length of the overall modal
            and demonstrating the overflow scrolling. When content becomes
            longer than the height of the viewport, scrolling will move the
            modal as needed. This is some placeholder content to show the
            scrolling behavior for modals. Instead of repeating the text the
            modal, we use an inline style set a minimum height, thereby
            extending the length of the overall modal and demonstrating the
            overflow scrolling. When content becomes longer than the height of
            the viewport, scrolling will move the modal as needed. This is some
            placeholder content to show the scrolling behavior for modals.
            Instead of repeating the text the modal, we use an inline style set
            a minimum height, thereby extending the length of the overall modal
            and demonstrating the overflow scrolling. When content becomes
            longer than the height of the viewport, scrolling will move the
            modal as needed. This is some placeholder content to show the
            scrolling behavior for modals. Instead of repeating the text the
            modal, we use an inline style set a minimum height, thereby
            extending the length of the overall modal and demonstrating the
            overflow scrolling. When content becomes longer than the height of
            the viewport, scrolling will move the modal as needed. This is some
            placeholder content to show the scrolling behavior for modals.
            Instead of repeating the text the modal, we use an inline style set
            a minimum height, thereby extending the length of the overall modal
            and demonstrating the overflow scrolling. When content becomes
            longer than the height of the viewport, scrolling will move the
            modal as needed. This is some placeholder content to show the
            scrolling behavior for modals. Instead of repeating the text the
            modal, we use an inline style set a minimum height, thereby
            extending the length of the overall modal and demonstrating the
            overflow scrolling. When content becomes longer than the height of
            the viewport, scrolling will move the modal as needed. This is some
            placeholder content to show the scrolling behavior for modals.
            Instead of repeating the text the modal, we use an inline style set
            a minimum height, thereby extending the length of the overall modal
            and demonstrating the overflow scrolling. When content becomes
            longer than the height of the viewport, scrolling will move the
            modal as needed. This is some placeholder content to show the
            scrolling behavior for modals. Instead of repeating the text the
            modal, we use an inline style set a minimum height, thereby
            extending the length of the overall modal and demonstrating the
            overflow scrolling. When content becomes longer than the height of
            the viewport, scrolling will move the modal as needed.
        </template>
        <template #footer>
            <Button type="light" data-bs-dismiss="modal">Cancel</Button>
            <Button>Save</Button>
        </template>
    </Modal>
</template>
