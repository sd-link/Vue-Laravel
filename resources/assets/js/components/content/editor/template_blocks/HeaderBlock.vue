<template>
    <div :id="settings.uniqueId" class="content-block">
        <div class="content-block-body">
            <label>{{ settings.blockTitle }}</label>
            <!-- <div class="required" v-if="!showHeaders && settings.required"></div> -->
            <input v-model="content" v-bind:style="inputStyles" type="text" class="form-control" :class="settings.blockClass" :placeholder="'Enter '+settings.blockTitle">
        </div>
    </div>
</template>

<script>
    import GeneralMixin from '../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        mixins: [GeneralMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Header'},
            placeholder: {type: String, default: 'Enter Header'},
            required: {type: Boolean, default: false},
            blockClass: {type: String, default: 'h2'},
            minCharactersRequired: {type: Number, default: 1},
            maxCharactersAllowed: {type: Number, default: 120},
            showLabel: {type: Boolean, default: true}
        },
        data: function data() {
            return {
                showModal: false,
                content: '',
            }
        },
        props: {
            transparentInputBackground: {type: Boolean, default: false}
        },
        computed: {
            inputStyles: function() {
                if (this.transparentInputBackground) {
                    return 'background-color: rgba(0, 0, 0, 0.11);'
                }
            },
            modalTitle: function () {
                return this.settings.blockTitle + ' Settings'
            },
            settings () {
                return this.$store.getters.itemSettings(this.uniqueId)
            }
        },
        watch: {
            transparentInputBackground(val, old) {
                console.log('transparentInputBackground changed inside header block')
            },
            settings: {
                handler(newVal) {
                    this.updateItemSettings({settings: newVal, uniqueId: this.uniqueId})
                },
                deep: true
            }
        },
        methods: {
            ...mapActions([
                'updateItemSettings'
            ]),
        },
        beforeDestroy() {
          //do something before destroying vue instance
        },
        mounted : function() {
            // if(this.initNewBlock)
            // document.getElementById(this.uniqueId).scrollIntoView()
        }
    }
</script>

<style scoped lang="less">
    .h1, h1, .h2, h2, .h3, h3, .h4, h4 {
        margin-top: 0px;
        height: 50px;
    }

    .h1, h1 {
        font-size: 36px;
    }

    .h2, h2 {
        font-size: 30px;
    }

    .h3, h3 {
        font-size: 24px;
    }

    .h4, h4 {
        font-size: 18px;
    }

</style>
