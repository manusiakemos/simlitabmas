<template>
    <money-input
            v-model="propModel"
    />
</template>
<script>
    export default {
        props:['value'],
        computed: {
            propModel: {
                get() {
                    return this.value;
                },
                set(value) {
                    this.$emit("input", value);
                }
            }
        }
    }
</script>
