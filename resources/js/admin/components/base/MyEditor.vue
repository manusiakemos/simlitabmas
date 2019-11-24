<template>
    <div class="my-editor-wrapper">
        <ckeditor :editor="editor" v-model="propModel" :config="editorConfig"></ckeditor>
    </div>
</template>

<script>
    import CKEditor from '@ckeditor/ckeditor5-vue';
    import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
    export default {
        components:{
            ckeditor: CKEditor.component
        },
        props:["value"],
        computed: {
            propModel: {
                get() {
                    return this.value;
                },
                set(value) {
                    this.$emit("input", value);
                }
            }
        },
        data() {
            return {
                editor: ClassicEditor,
                editorConfig: {
                    toolbar: [
                        "insertTable",
                        "heading",
                        "|",
                        "bold",
                        "italic",
                        "link",
                        "bulletedList",
                        "numberedList",
                        "blockQuote",
                    ],
                    table: {
                        contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
                    }
                }
            };
        }
    };
</script>

