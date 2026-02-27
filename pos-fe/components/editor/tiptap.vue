<template>
    <div class="space-y-st-md">
        <div v-if="editor" class="space-x-st-xs space-y-st-xs">
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().toggleBold().run()"
                :disabled="!editor.can().chain().focus().toggleBold().run()"
                :class="{ '!bg-purple-700': editor.isActive('bold') }"
            >
                Bold
            </Button>
            <Button
                type="button"
                :textColor="{
                    color: 'dark:white',
                    active: 'light',
                }"
                variant="dark"
                light
                class="btn-sm"
                @click="editor.chain().focus().toggleItalic().run()"
                :disabled="!editor.can().chain().focus().toggleItalic().run()"
                :class="{ '!bg-purple-700': editor.isActive('italic') }"
            >
                Italic
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().toggleStrike().run()"
                :disabled="!editor.can().chain().focus().toggleStrike().run()"
                :class="{ '!bg-purple-700': editor.isActive('strike') }"
            >
                Strike
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().toggleCode().run()"
                :disabled="!editor.can().chain().focus().toggleCode().run()"
                :class="{ '!bg-purple-700': editor.isActive('code') }"
            >
                Code
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().unsetAllMarks().run()"
            >
                Clear marks
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().clearNodes().run()"
            >
                Clear nodes
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().setParagraph().run()"
                :class="{ '!bg-purple-700': editor.isActive('paragraph') }"
            >
                Paragraph
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="
                    editor.chain().focus().toggleHeading({ level: 1 }).run()
                "
                :class="{
                    '!bg-purple-700': editor.isActive('heading', { level: 1 }),
                }"
            >
                H1
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="
                    editor.chain().focus().toggleHeading({ level: 2 }).run()
                "
                :class="{
                    '!bg-purple-700': editor.isActive('heading', { level: 2 }),
                }"
            >
                H2
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="
                    editor.chain().focus().toggleHeading({ level: 3 }).run()
                "
                :class="{
                    '!bg-purple-700': editor.isActive('heading', { level: 3 }),
                }"
            >
                H3
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="
                    editor.chain().focus().toggleHeading({ level: 4 }).run()
                "
                :class="{
                    '!bg-purple-700': editor.isActive('heading', { level: 4 }),
                }"
            >
                H4
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="
                    editor.chain().focus().toggleHeading({ level: 5 }).run()
                "
                :class="{
                    '!bg-purple-700': editor.isActive('heading', { level: 5 }),
                }"
            >
                H5
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="
                    editor.chain().focus().toggleHeading({ level: 6 }).run()
                "
                :class="{
                    '!bg-purple-700': editor.isActive('heading', { level: 6 }),
                }"
            >
                H6
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().toggleBulletList().run()"
                :class="{ '!bg-purple-700': editor.isActive('bulletList') }"
            >
                Bullet list
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().toggleOrderedList().run()"
                :class="{ '!bg-purple-700': editor.isActive('orderedList') }"
            >
                Ordered list
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().toggleCodeBlock().run()"
                :class="{ '!bg-purple-700': editor.isActive('codeBlock') }"
            >
                Code block
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().toggleBlockquote().run()"
                :class="{ '!bg-purple-700': editor.isActive('blockquote') }"
            >
                Blockquote
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().setHorizontalRule().run()"
            >
                Horizontal rule
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().setHardBreak().run()"
            >
                Hard break
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().undo().run()"
                :disabled="!editor.can().chain().focus().undo().run()"
            >
                Undo
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().redo().run()"
                :disabled="!editor.can().chain().focus().redo().run()"
            >
                Redo
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().setColor('#958DF1').run()"
                :class="{
                    '!bg-purple-700': editor.isActive('textStyle', {
                        color: '#958DF1',
                    }),
                }"
            >
                Purple
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().toggleHighlight().run()"
                :class="{ '!bg-purple-700': editor.isActive('highlight') }"
            >
                Highlight
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().setTextAlign('left').run()"
                :class="{
                    '!bg-purple-700': editor.isActive({ textAlign: 'left' }),
                }"
            >
                Left
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().setTextAlign('center').run()"
                :class="{
                    '!bg-purple-700': editor.isActive({ textAlign: 'center' }),
                }"
            >
                Center
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().setTextAlign('right').run()"
                :class="{
                    '!bg-purple-700': editor.isActive({ textAlign: 'right' }),
                }"
            >
                Right
            </Button>
            <Button
                type="button"
                variant="dark"
                :textColor="{
                    color: 'dark:!white',
                    active: 'primary',
                }"
                light
                class="btn-sm"
                @click="editor.chain().focus().setTextAlign('justify').run()"
                :class="{
                    '!bg-purple-700': editor.isActive({ textAlign: 'justify' }),
                }"
            >
                Justify
            </Button>
        </div>
        <TiptapEditorContent
            :editor="editor"
            :class="{
                'border border-danger': isValid != null ? !isValid : null,
                'border border-success': isValid != null ? isValid : null,
            }"
        />
    </div>
</template>

<script lang="ts" setup>
import { Color } from "@tiptap/extension-color";
import ListItem from "@tiptap/extension-list-item";
import TextStyle from "@tiptap/extension-text-style";
import StarterKit from "@tiptap/starter-kit";
import Highlight from "@tiptap/extension-highlight";
import TextAlign from "@tiptap/extension-text-align";
import Placeholder from "@tiptap/extension-placeholder";

import type { Content } from "@tiptap/core";

interface Placeholder {
    heading: string;
    text: string;
}

interface Props {
    placeholder: Partial<Placeholder>;
    onUpdate: Function;
    isValid: Boolean;
}

const props = withDefaults(defineProps<Partial<Props>>(), {
    placeholder: {
        heading: "What’s the title?",
        text: "Can you add some further context?",
    },
    isValid: null,
});

const content = defineModel<Content>();

const editor = useEditor({
    content: content.value,
    extensions: [
        StarterKit,
        Color.configure({ types: [TextStyle.name, ListItem.name] }),
        TextStyle.configure({ types: [ListItem.name] }),
        Highlight,
        TextAlign.configure({
            types: ["heading", "paragraph"],
        }),
        Placeholder.configure({
            placeholder: ({ node }) => {
                if (node.type.name === "heading") {
                    return props.placeholder.heading;
                }

                return props.placeholder.text;
            },
        }),
    ],
    editorProps: {
        attributes: {
            class: `p-st`,
        },
    },
    onUpdate: ({ editor }) => props?.onUpdate(editor),
});

defineExpose({
    destroy: () => editor.value?.destroy(),
    clearContent: (status: boolean = false) => {
        editor.value?.commands.clearContent(status);
    },
});
</script>

<style lang="scss">
/* Basic editor styles */
.tiptap {
    :first-child {
        margin-top: 0;
    }

    /* List styles */
    ul,
    ol {
        padding: 0 1rem;
        margin: 1.25rem 1rem 1.25rem 0.4rem;

        li p {
            margin-top: 0.25em;
            margin-bottom: 0.25em;
        }
    }

    /* Heading styles */
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        line-height: 1.1;
        margin-top: 2.5rem;
        text-wrap: pretty;
    }

    h1,
    h2 {
        margin-top: 3.5rem;
        margin-bottom: 1.5rem;
    }

    h1 {
        font-size: 1.4rem;
    }

    h2 {
        font-size: 1.2rem;
    }

    h3 {
        font-size: 1.1rem;
    }

    h4,
    h5,
    h6 {
        font-size: 1rem;
    }

    /* Code and preformatted text styles */
    code {
        background-color: var(--purple-light);
        border-radius: 0.4rem;
        color: var(--black);
        font-size: 0.85rem;
        padding: 0.25em 0.3em;
    }

    pre {
        background: var(--black);
        border-radius: 0.5rem;
        color: var(--white);
        font-family: "JetBrainsMono", monospace;
        margin: 1.5rem 0;
        padding: 0.75rem 1rem;

        code {
            background: none;
            color: inherit;
            font-size: 0.8rem;
            padding: 0;
        }
    }

    blockquote {
        border-left: 3px solid var(--bs-gray-300);
        margin: 1.5rem 0;
        padding-left: 1rem;
    }

    hr {
        border: none;
        border-top: 1px solid var(--bs-gray-200);
        margin: 2rem 0;
    }

    /* Placeholder (on every new line) */
    .is-empty::before {
        color: var(--bs-gray-400);
        content: attr(data-placeholder);
        float: left;
        height: 0;
        pointer-events: none;
    }
}
</style>
