<template>
    <div>
      <div class="container">
          <div class="row text-center">
            <button @click="addTodo('TodoFirst')" type="button" name="button" class="btn btn-primary btn-sm" style="margin-bottom: 5px;">Add First TODO</button>
            <input type="text" class="form-control" name="" v-model="population" style="margin-bottom: 5px;">
            <button @click="populate()" type="button" name="button" class="btn btn-primary btn-sm" style="margin-bottom: 5px;">Add {{ population }} Todos</button>
            <button @click="save()" type="button" name="button" class="btn btn-primary btn-sm" style="margin-bottom: 5px;">Save Everything</button>
            <draggable
                v-model="todos"
                :options="{group:'root', handle:'.fa-arrows', chosenClass:'dragging1'}"
                style="min-height: 30px;"
            >
                <component v-for="todo in todos"
                    v-bind:is="todo.type"
                    :uniqueId="todo.uniqueId"
                    v-bind="todo.settings"
                    :key="todo.uniqueId">
                </component>
            </draggable>
          </div>
        </div>
    </div>
</template>

<script>
import TodoFirst from './todos/TodoFirst'
import { processSettings } from 'utils/helpers.js'
import { mapGetters, mapActions } from 'vuex'
import draggable from 'vuedraggable'

export default {
    components: {
        TodoFirst,
        draggable
    },
    data: function data() {
        return {
            shouldSave: false,
            population: 1,
        }
    },
    props: {
        projectId: {type: Number, default: 1},
        mode: {type: String, default: 'create'},
    },
    computed: {
        todos: {
            get() {
                return this.$store.getters.rootItems
            },
            set(object) {
                console.log('set order for TODOS', object)
                this.updateTodosList({list: _.map(object, 'uniqueId')})
            }
        }
    },
    methods: {
        ...mapActions([
            'addItem',
            'updateTodosList',
            'fetchAll'
        ]),
        onEnd(evt) {
            // for (var i = 0; i < this.todos.length; i++) {
            //     this.todos[i].order = (i+1)
            // }
        },
        addTodo(compName = undefined) {
            let comp = this.$options.components[compName]
            if (!comp) {
                alert('Wrong component\'s name')
                return false
            }

            this.addItem({
                type: compName,
                settingsConfig: comp.customSettings,
                settings: _.mapValues(comp.customSettings, object => (object.default === undefined)? null : object.default)
            })
        },
        populate() {
            for (var i = 0; i < this.population; i++) {
                this.addTodo('TodoFirst')
            }
        },
        save() {
            this.$store.dispatch('saveAll')
        }
    },
    mounted: function() {
        // do something after mounting vue instance
        if (this.mode == 'edit') {
            this.fetchAll({projectId: this.projectId})
        }
    }
}
</script>

<style>
.row {
    display: flex;
    flex-direction: column;
    justify-content: center;
    justify-items: center;
    align-content: center;
    justify-content: center;
    margin: auto;
    width: 40%;
}

</style>
