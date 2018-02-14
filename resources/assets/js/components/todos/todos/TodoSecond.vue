<template>
    <div class="todo">
        <i class="fa fa-arrows" aria-hidden="true"></i>
        <div style="backgound-color: whitesmoke;">
            <h3>2TYPE COMPONENT: {{uniqueId}}</h3>
            <b>setting1: {{ settings.setting1 }}</b> <br>
            <b>setting2: {{ settings.setting2 }}</b>
        </div>
        <button @click="changeSetting1()" type="button" name="button" class="btn btn-primary btn-sm">Change Setting 1</button>
        <button @click="changeSetting2()" type="button" name="button" class="btn btn-primary btn-sm">Change Setting 2</button>
    </div>
</template>

<script>
    import GeneralMixin from '../GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        mixins: [GeneralMixin],
        customSettings: {
            setting1: {type: String, default: 'value 1'},
            setting2: {type: String, default: 'value 2'}
        },
        data () {
            return {
                //
            }
        },
        props: {
            //
        },
        computed: {
            settings () {
                return this.$store.getters.itemSettings(this.uniqueId)
            },
            subItems () {
                return this.$store.getters.items(this.uniqueId)
            },
        },
        watch: {
            settings: {
                handler(newVal) {
                    console.log('CHANGE')
                    this.updateItemSettings({settings: newVal, uniqueId: this.uniqueId})
                },
                deep: true
            }
        },
        methods: {
            ...mapActions([
                'addItem',
                'updateItemSettings'
            ]),
            changeSetting1() {
                this.settings.setting1 = this.settings.setting1 + _.uniqueId()
            },
            changeSetting2() {
                this.settings.setting2 = this.settings.setting2 + _.uniqueId()
            },
        }
    }
</script>

<style>
    .todo {
        border: 1px solid black; padding: 30px;
        margin-bottom: 5px;
        font-size: 18px;
        padding: 20px;
        width: 90%;
    }
</style>
