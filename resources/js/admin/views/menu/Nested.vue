<template>
    <draggable
        v-bind="dragOptions"
        tag="div"
        class="item-container"
        :list="list"
        :value="value"
        @input="emitter"
        @change="log"
        @move="move"
    >

        <div class="item-group text-white" :key="el.id" v-for="el in realValue" :data-id="el.id">
            <div class="item d-flex">
                <strong>{{ el.name }}</strong>
                <i class="ml-3">{{ el.type }}</i>
                <div class="ml-auto">
                    <b-button size="md" @click="edit(el)" variant="success">
                        <span class="fa fa-edit"></span>
                    </b-button>
                    <b-button @click="destroy(el)" size="md" variant="danger">
                        <span class="fa fa-trash-o"></span>
                    </b-button>
                </div>
            </div>
            <nested-test class="item-sub" :list="el.children"/>
        </div>
    </draggable>
</template>

<script>
    import draggable from "vuedraggable";

    export default {
        name: "nested-test",
        computed: {
            user() {
                return this.$store.state.auth.data;
            },
            dragOptions() {
                return {
                    animation: 0,
                    group: "description",
                    disabled: false,
                    ghostClass: "ghost"
                };
            },
            // this.value when input = v-model
            // this.list  when input != v-model
            realValue() {
                return this.value ? this.value : this.list;
            }
        },
        props: {
            value: {
                required: false,
                type: Array,
                default: null
            },
            list: {
                required: false,
                type: Array,
                default: null
            }
        },
        data() {
            return {
                data: ''
            }
        },
        components: {
            draggable
        },
        methods: {
            update() {
                this.axios.put('/api/menu', this.data).then(res => {
                    if (res.data.status) {
                        this.$message({
                            message: res.data.message,
                            type: 'success'
                        });
                        this.refresh();
                    }
                });
            },
            emitter(value) {
                this.$emit("input", value);
            },
            log(evt) {
                var add = evt.added;
                if (add && add.element) {
                    var id = add.element.id;
                    var el = $(`[data-id="${id}"]`).parents('.item-group');
                    var parent = el.data('id') ? el.data('id') : null ;

                    this.axios.put(`/api/menu/order/${id}`, {parent: parent}).then(res => {
                        this.$message({
                            message: res.data.message,
                            type: res.data.status
                        })
                    })
                }

                if(evt.moved){
                    var moved = evt.moved;
                    var id = moved.element.id;
                    var order = moved.newIndex;
                    this.axios.put(`/api/menu/order/${id}`, {order: order}).then(res => {
                        this.$message({
                            message: res.data.message,
                            type: res.data.status
                        });
                        // this.$parent.getData();
                    })
                }
            },
            edit(element) {
                this.$store.commit("setDataModal", element);
                this.$store.commit("setShowModal", true);
                this.$store.commit("setAction", 'update');
            },
            destroy(element) {
                this.$confirm('Apakah Kamu Yakin?', 'Warning', {
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    type: 'warning'
                }).then(() => {
                    this.axios.delete(`/api/menu/${element.id}`).then(res => {
                        if (res.data.message) {
                            this.$message({
                                message: res.data.message,
                                type: res.data.text
                            })
                        }
                    });
                    this.$store.commit("setRefresh");
                }).catch(() => {
                    this.$message({
                        message: "batal",
                        type: "menu batal dihapus"
                    })
                });

            },
            move(evt) {
                console.log(evt)
            },
        },
    };
</script>
