<script>
    import { EventBus } from './../../eventbus.js'
    import vein from 'veinjs'
    import ReplaceClass from 'utils/directives/ReplaceClass.js'


    // Listen to the event.

    export default {
        // directives: {
        //     ReplaceClass
        // },
        props: {
            // initDefaultLayout: {type: String},
            // initDefaultColumns: {type: String},
            initStatus: {type: Number},
            initAuthor: {type: Number},
            initFeaturedImage: {type: Number},
            initCreated: {type: Number},
            initUpdated: {type: Number},
            // initContentClass: {type: String},
            initContentColumnClass: {type: String},
            initIsDesktop: {type: Boolean},
        },
        data () {
            return {
                test: 'test',
                // defaultLayout: this.initDefaultLayout,
                // defaultColumns: this.initDefaultColumns,
                status: this.initStatus,
                author: this.initAuthor,
                featuredImage: this.initFeaturedImage,
                created: this.initCreated,
                updated: this.initUpdated,
                windowWidth: 0,
                windowHeight: 0,
                // contentClass: this.initContentClass,
                contentColumnClass: 'row ' + this.initDefaultColumns,
                isDesktop: this.initIsDesktop,
            }
        },
        beforeMount () {
            // document.addEventListener('visibilitychange', this.resizeHandler)
            // window.addEventListener('DOMContentLoaded', this.resizeHandler)
            // window.addEventListener('resize', this.resizeHandler)
            // this.resizeHandler()
        },
        methods: {
            resizeHandler() {
                const { body } = document

                console.log('this.isDesktop: ' + this.isDesktop)
                    // console.log('we are in')
                    if (!document.hidden) {
                        let rect = body.getBoundingClientRect()

                        let isAbove1200 = rect.width > 1200
                        let isBelow1200 = rect.width < 1200
                        let isAbove950 = rect.width > 950
                        let isBelow950 = rect.width < 950

                        let isBelow680 = rect.width < 680

                        this.windowWidth = rect.width

                        if(this.isDesktop) {
                            // this.contentClass = (isBelow1200 ? 'grid' : this.defaultLayout)
                            //
                            // console.log('this.defaultColumns: ' + this.defaultColumns)
                            //
                            // if(this.defaultColumns != 'column-1' && this.defaultColumns != 'column-2') {
                            //     this.contentColumnClass = (isBelow950 ? 'row column-2' : 'row column-3')
                            //
                            //     this.contentColumnClass = (isBelow680 ? 'row column-1' : 'row column-2')
                            // }
                            //
                            // if(isAbove950 && this.defaultColumns != 'column-1' && this.defaultColumns != 'column-2')
                            //     this.contentColumnClass = 'row column-3'
                            //
                            // if(isAbove1200)
                            //     this.contentColumnClass = 'row ' + this.defaultColumns

                        }
                    }
            }
        },
        beforeDestroy() {
            document.removeEventListener('visibilitychange', this.resizeHandler)
            window.removeEventListener('DOMContentLoaded', this.resizeHandler)
            window.removeEventListener('resize', this.resizeHandler)
        },
        watch: {
        },
        computed: {
            styles() {
            }
        }
    }
</script>

<style media="screen">
    .test {
        background: black;
    }
</style>
