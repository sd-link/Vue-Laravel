<script>
    import { EventBus } from './../../eventbus.js'
    import vein from 'veinjs'

    // Listen to the event.

    export default {
        data () {
          return {
            test: 'test',
            fontColor: this.initSidebarFontColor,
            fontSize: this.initSidebarFontSize,
            fontHoverColor: this.initSidebarFontHoverColor,
            mainBackgroundColor: this.initSidebarMainBackgroundColor,
            subBackgroundColor: this.initSidebarSubBackgroundColor,
            menuHoverColor: this.initSidebarMenuHoverColor,
            }
        },
        mounted: function() {
            EventBus.$on('sidebar-change', input => {
                // console.log('injecting: ' + input.id)
                switch (input.id) {
                    case 'main_menu_font_size':
                        vein.inject('.sidebar', {'font-size': input.value+'px'})
                    break;

                    case 'main_menu_font_color':
                        vein.inject('.skin-blue .sidebar a', {'color': input.value})
                    break;

                    case 'main_menu_font_hover_color':
                        vein.inject('.skin-blue .sidebar-menu>li:hover>a, .skin-blue .sidebar-menu>li.active>a', {'color': input.value})
                    break;

                    case 'main_menu_main_background_color':
                        vein.inject('.skin-blue .wrapper, .skin-blue .main-sidebar, .skin-blue .left-side', {'background-color': input.value})
                    break;

                    case 'main_menu_sub_background_color':
                        vein.inject('.skin-blue .sidebar-menu>li>.treeview-menu', {'background-color': input.value})
                    break;

                    case 'main_menu_menu_hover_color':
                        vein.inject('.skin-blue .sidebar-menu>li:hover>a, .skin-blue .sidebar-menu>li.active>a', {'background-color': input.value})
                    break;
                }
            })
        },
        props: {
            initSidebarFontSize: {type: String},
            initSidebarFontColor: {type: String},
            initSidebarFontHoverColor: {type: String},
            initSidebarMainBackgroundColor: {type: String},
            initSidebarSubBackgroundColor: {type: String},
            initSidebarMenuHoverColor: {type: String},
        },
        vuex: {
          getters: {
            sidebarFontSize: '18px'
          },
          actions: {
          }
        },
        computed: {
            testing() {
                return this.$store.state.testing;
            },
            styles() {
                // return {
                //     'font-size': sidebarFontSize
                // }
            }
        }
    }
</script>

<style media="screen">
    .test {
        background: black;
    }
</style>
