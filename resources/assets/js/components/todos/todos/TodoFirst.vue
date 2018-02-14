<template>
    <div class="todo">
        <i class="fa fa-arrows" aria-hidden="true"></i>
        <div style="text-align: left;">
            <h3>1TYPE COMPONENT: {{uniqueId}}</h3>
            <b>setting1: {{ settings.setting1 }}</b> <br>
            <b>setting2: {{ settings.setting2 }}</b>

            <draggable
                v-model="subItems"
                :options="{group:'sub', handle:'.fa-arrows', chosenClass:'dragging1'}"
                style="min-height: 30px;"
            >
                <component v-for="todo in subItems"
                    v-bind:is="todo.type"
                    :uniqueId="todo.uniqueId"
                    v-bind="todo.settings"
                    :key="todo.uniqueId">
                </component>
            </draggable>
        </div>
        <button @click="addSubTodo('TodoSecond')" type="button" name="button" class="btn btn-primary btn-sm">Add Sub Todo</button>
        <button @click="changeSetting1()" type="button" name="button" class="btn btn-primary btn-sm">Change Setting 1</button>
        <button @click="changeSetting2()" type="button" name="button" class="btn btn-primary btn-sm">Change Setting 2</button>
    </div>
</template>

<script>
    import GeneralMixin from '../GeneralMixin'
    import TodoSecond from './TodoSecond'
    import { mapGetters, mapActions } from 'vuex'
    import draggable from 'vuedraggable'

    export default {
        mixins: [GeneralMixin],
        components: {
            TodoSecond,
            draggable
        },
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
            settings: {
                get() {
                    return this.$store.getters.itemSettings(this.uniqueId)
                }
            },
            subItems: {
                get() {
                    return this.$store.getters.items(this.uniqueId)
                },
                set(object) {
                    console.log('set order for SUB TODOS', object)
                    this.updateTodosList({list: _.map(object, 'uniqueId'), id: this.uniqueId})
                }
            }
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
                'updateItemSettings',
                'updateTodosList'
            ]),
            changeSetting1() {
                this.settings.setting1 = this.settings.setting1 + _.uniqueId()
            },
            changeSetting2() {
                this.settings.setting2 = this.settings.setting2 + _.uniqueId()
            },
            addSubTodo(compName = undefined) {
                let comp = this.$options.components[compName]
                if (!comp) {
                    alert('Wrong component\'s name')
                    return false
                }

                this.addItem({
                    type: compName,
                    parentId: this.uniqueId,
                    settingsConfig: comp.customSettings,
                    settings: _.mapValues(comp.customSettings, object => (object.default === undefined) ? null : object.default)
                })
            }
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
    .fa-arrows {
        cursor: move;
        font-size: 16px;
        margin-right: 8px;
    }
</style>
