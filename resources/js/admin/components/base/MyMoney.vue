<template>
    <money v-model="propModel" v-bind="money" class="form-control"></money>
</template>
<script>
    import {Money} from 'v-money'
    export default {
        components: {Money},
        props:['value'],
        data () {
            return {
                money: {
                    decimal: ',',
                    thousands: '.',
                    prefix: 'Rp ',
                    suffix: '',
                    precision: 0,
                    masked: false
                }
            };
        },
        computed: {
            propModel: {
                get() {
                    return this.value.toString();
                },
                set(value) {
                    this.$emit("input", value);
                }
            }
        }
    }
</script>
