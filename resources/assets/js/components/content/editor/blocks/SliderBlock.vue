<template>
    <div class="content-block">
        <div v-show="showHeaders" class="content-block-header">
            <i class="fa fa-arrows" aria-hidden="true"></i>
            <i class="fa fa-cog" @click="blockSettings()" aria-hidden="true"></i>
            {{ settings.blockTitle }}
            <button v-on:click="removeBlock" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>

        <div v-show="showContent" class="content-block-body">
            <div class="slider" style="height: auto; border: 1px dashed lightblue; min-height: 200px; position: relative; overflow: hidden;" v-bind:style="{'height': settings.height}">
                <transition-group name="custom-classes-transition" :enter-active-class="'animated ' + enterTransition" :leave-active-class="'animated ' + leaveTransition">
                    <component v-for="(slide, index) in slides"
                      v-bind:is="slide.type"
                      v-show="visible(index)"
                      :uniqueId="slide.uniqueId"
                      :editing-slide="editingSlide"
                      :content-render-style="settings.contentRenderStyle"
                      v-bind="slide.settings"
                      v-on:edit-slide="slideMode"
                      v-on:remove="removeBlock(slide.uniqueId)"
                      :key="slide.uniqueId">
                    </component>
                </transition-group>
                <div v-if="settings.buttons && !editingSlide" class="slide-buttons">
                    <span v-for="(slide, index) in slides" @click="jumpToSlide(index)" class="button" :class="{ 'button-active': visible(index) }">.</span>
                </div>
                <span class="slide-controls">
                    <span @click="addSlide()" class="add-slide" title="Add Slide"><i class="fa fa-plus" aria-hidden="true" ></i></span>
                    <span @click="openMediaModal()" class="control set-image" title="Slide Image"><i class="fa fa-image" aria-hidden="true"></i></span>
                    <span @click="layersModal()" class="control settings" title="Slide Layers"><i class="icon-layers icons" ></i></span>
                    <span @click="layersModal()" class="control settings" title="Slide Settings"><i class="fa fa-gear"></i></span>
                    <!-- <span @click="addSlide()" class="control edit"><i class="fa fa-plus" aria-hidden="true" title="Add Slide"></i></span> -->
                </span>
                <span v-show="settings.arrows" @click="prev()" class="arrow arrow-left"><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
                <span v-show="settings.arrows" @click="next()" class="arrow arrow-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
                <span  class="add-slide-container"> </span>
            </div>
        </div>

        <modal ref="settingsModal" :title="modalTitle"
          v-model="showModal"
          v-bind="state.modalData"
          :draggable="true"
          :defaultWidth="modalDefaults.width"
          :defaultHeight="modalDefaults.height">
            <div class="modal-body" slot="modal-body">

                <div class="form-group">
                    <label>Block Title</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle">
                </div>

                <div class="form-group">
                    <label>Content</label>
                    <select class="form-control" v-model="settings.contentRenderStyle">
                        <option value='fluid'>Fluid</option>
                        <option value='boxed'>Boxed</option>
                    </select>
                    <small>Should content inside each slide be fluid or boxed.</small>
                </div>

                <div v-if="!settings.heightResponsive" class="form-group">
                    <label>Height</label> <i @click="settings.heightResponsive = !settings.heightResponsive" class="fa fa-desktop" title="Responsive" aria-hidden="true"></i>
                    <px v-model="settings.height"></px>
                </div>

                <div v-if="settings.heightResponsive" class="form-group">
                    <label>Height</label> <i @click="settings.heightResponsive = !settings.heightResponsive" class="fa fa-desktop" title="Responsive" aria-hidden="true"></i>
                    <div v-if="settings.heightResponsive" class="form-sub-group">
                        <div class="form-group">
                            <label>Desktop</label>
                            <px v-model="settings.height"></px>
                        </div>
                        <div class="form-group">
                            <label>Tablet Portrait</label>
                            <px v-model="settings.heightTabletPortrait"></px>
                        </div>
                        <div class="form-group">
                            <label>Tablet Landscape</label>
                            <px v-model="settings.heightTabletLandscape"></px>
                        </div>
                        <div class="form-group">
                            <label>Mobile Portrait</label>
                            <px v-model="settings.heightMobilePortrait"></px>
                        </div>
                        <div class="form-group">
                            <label>Mobile Landscape</label>
                            <px v-model="settings.heightMobileLandscape"></px>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Transition Effect</label>
                    <select class="form-control" v-model="settings.transition">
                        <option v-for="(transition, key) in transitions" :value='key'>{{ transition }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Loop</label>
                    <select class="form-control" v-model="settings.loop">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Autoplay</label>
                    <select class="form-control" v-model="settings.autoplay">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div>

                <div v-if="settings.autoplay" class="form-group">
                    <label>Autoplay Duration</label>
                    <input type="text" class="form-control" v-model="settings.autoplayDuration">
                    <small>Duration between slides in milliseconds.</small>
                </div>

                <div v-if="settings.autoplay" class="form-group">
                    <label>Show progressbar</label>
                    <select class="form-control" v-model="settings.showProgressBar">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div>

                <div v-if="settings.showProgressBar" class="form-group">
                    <label>Progressbar Color</label>
                    <color-picker type="text" v-model="settings.progressBarColor"></color-picker>
                </div>

                <div class="form-group">
                    <label>Arrows</label>
                    <select class="form-control" v-model="settings.arrows">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div>

                <div v-if="settings.arrows" class="form-group">
                    <label>Show Arrows</label>
                    <select class="form-control" v-model="settings.showArrowsWhen">
                        <option value='always'>Always</option>
                        <option value='hover'>On Hover</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Buttons</label>
                    <select class="form-control" v-model="settings.buttons">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div>

                <div v-if="settings.buttons" class="form-group">
                    <label>Show Buttons</label>
                    <select class="form-control" v-model="settings.showButtonsWhen">
                        <option value='always'>Always</option>
                        <option value='hover'>On Hover</option>
                    </select>
                </div>

            </div>
            <div class="modal-footer" slot="modal-footer">
                <button type="button" class="btn btn-primary" @click="closeModalAction">Close</button>
            </div>
        </modal>
    </div>
</template>

<script>
    import GeneralMixin from '../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'
    import Modal from 'components/ui/Modal.vue'
    import { getComponentByName, processSettingsConfig } from 'utils/helpers.js'

    import Slick from 'vue-slick'
    import 'slick-carousel/slick/slick.css'

    // Import this component
    // import SliderPro from 'slider-pro'

    // Import editor css
    // import 'slider-pro/dist/css/slider-pro.min.css'

    // Import this component
    // import SliderPro from 'components/ui/ProSlider.vue'

    export default {
        components: {
            Modal,
            Slick,
        },
        mixins: [GeneralMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Slider'},
            contentRenderStyle: {type: String, default: 'boxed'},
            position: {type: String, default: 'absolute'},
            width: {type: String, default: '100%'},

            height: {type: String, default: '450px'},
            heightResponsive: {type: Boolean, default: false},
            height: {type: String, default: '450px'},
            heightTabletPortrait: {type: String, default: '450px'},
            heightTabletLandscape: {type: String, default: '450px'},
            heightMobilePortrait: {type: String, default: '450px'},
            heightMobileLandscape: {type: String, default: '450px'},

            arrows: {type: Boolean, default: true},
            showArrowsWhen: {type: String, default: 'always'},
            buttons: {type: Boolean, default: true},
            showButtonsWhen: {type: String, default: 'always'},
            autoplay: {type: Boolean, default: false},
            autoplayDuration: {type: Number, default: 6000},
            loop: {type: Boolean, default: true},
            transition: {type: String, default: 'slideHorisontal'},

            showProgressBar: {type: Boolean, default: false},
            progressBarColor: {type: String, default: '#007AFF'},
        },
        data: function data() {
            return {
                modalDefaults: {
                    width: 600,
                    height: 700
                },
                state: {
                  modalData: {
                    top: 0,
                    left: 0,
                    width: null,
                    height: null
                  }
                },
                showModal: false,
                slider: null,
                duration: 500,
                transitions: {
                    fade: 'Fade',
                    slideHorisontal: 'Slide Horisontal',
                    slideVertical: 'Slide Vertical',
                    zoomOutSlideIn: 'Zoom Out Slide In',
                    // rubberBand: 'Rubber Band',
                },
                slideDirection: '',
                currentSlide: 0,
                previousSlide: 0,
                editingSlide: false,
                layersModalOpen: false,
                heightResponsive: false,
            }
        },
        props: {
            transparentInputBackground: {type: Boolean, default: false},
        },
        computed: {
            enterTransition: function() {
                var transition
                switch (this.settings.transition) {
                    case 'fade':
                        transition = 'fadeIn'
                    break;

                    case 'slideHorisontal':
                        if(this.slideDirection == 'next')
                            transition = 'slideInRight'
                        else if(this.slideDirection == 'prev')
                            transition = 'slideInLeft'
                    break;

                    case 'slideVertical':
                        if(this.slideDirection == 'next')
                            transition = 'slideInUp'
                        else if(this.slideDirection == 'prev')
                            transition = 'slideInDown'
                    break;

                    case 'zoomOutSlideIn':
                        if(this.slideDirection == 'next')
                            transition = 'slideInRight'
                        else if(this.slideDirection == 'prev')
                            transition = 'slideInLeft'
                    break;

                    case 'rubberBand':
                        transition = 'fadeIn'
                    break;
                    default:
                }

                return transition
            },
            leaveTransition: function() {
                var transition
                switch (this.settings.transition) {
                    case 'fade':
                        transition = 'fadeOut'
                    break;

                    case 'slideHorisontal':
                        if(this.slideDirection == 'next') {
                            transition = 'slideOutLeft'
                        } else if(this.slideDirection == 'prev') {
                            transition = 'slideOutRight'
                        }
                    break;

                    case 'slideVertical':
                        if(this.slideDirection == 'next')
                            transition = 'slideOutUp'
                        else if(this.slideDirection == 'prev')
                            transition = 'slideOutDown'
                    break;

                    case 'zoomOutSlideIn':
                        transition = 'zoomOut'
                    break;

                    case 'rubberBand':
                        transition = 'rubberBand'
                    break;
                    default:
                }

                return transition
            },
            modalTitle: function () {
                return this.settings.blockTitle + ' Settings'
            },
            settings: {
                get() {
                    return this.$store.getters.itemSettings(this.uniqueId)
                }
            },
            slides: {
                get() {
                    return this.$store.getters.items(this.uniqueId)
                },
                set(object) {
                    this.updateItemsList({list: _.map(object, 'uniqueId'), id: this.uniqueId})
                }
            },
        },
        watch: {
            currentSlide(val, old) {
                // console.log('settingPreviousSlideTo:' + old)
                this.previousSlide = val
            },
            slideDirection(val, old) {
                // console.log('direction: ' + val)
            },
            settings: {
                handler(newVal) {
                    this.updateItemSettings({settings: newVal, uniqueId: this.uniqueId})
                },
                deep: true
            },
        },
        methods: {
            responsiveSwitch(val) {
                this.settings[val] = !this.settings[val]
            },
            zindex(val, old) {
                if(val == this.currentSlide && !this.layersModalOpen)
                    return '1'
            },
            slideMode(val) {
                // console.log('slideMode: ' + val)
                this.editingSlide = val
            },
            jumpToSlide(index) {
                this.currentSlide = index

                if(this.currentSlide > this.previousSlide)
                    this.slideDirection = 'next'
                else if(this.currentSlide < this.previousSlide)
                    this.slideDirection = 'prev'
            },
            editSlide(slide) {
                slide.mode = 'edit'
                this.editingSlide = !this.editingSlide
            },
            visible(val) {
                return val == this.currentSlide
            },
            openMediaModal() {
                this.$children[0].$children[this.currentSlide].openMediaModal()
            },
            layersModal() {
                this.layersModalOpen = true
                this.$children[0].$children[this.currentSlide].layersModal()
            },
            next() {
                this.slideDirection = 'next'
                this.currentSlide = this.currentSlide + 1

                if(this.currentSlide > (this.slides.length-1))
                    this.currentSlide = 0
            },
            prev() {
                this.slideDirection = 'prev'
                this.currentSlide = this.currentSlide - 1

                if(this.currentSlide < 0)
                    this.currentSlide = (this.slides.length-1)
            },
            ...mapActions([
                'addItem',
                'removeItem',
                'updateItemsList',
                'updateItemSettings',
            ]),
            addSlide() {
                if(this.slides.length < 10) {
                    let compName = 'SlideBlock'
                    let comp = getComponentByName(compName)
                    let customSettings = processSettingsConfig(compName)

                    this.addItem({
                        type: compName,
                        title: 'Slide',
                        parentId: this.uniqueId,
                        settingsConfig: customSettings,
                        settings: _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                    })

                    this.currentSlide = this.slides.length - 1
                }
            },
            removeBlock() {
                this.$emit('remove', this.uniqueId)
            },
            closeModalAction() {
                // this.saveData()
                this.showModal = false
            },
            cancelAction() {
                this.showModal = false
            },
            blockSettings() {
                this.showModal = true
            }
        },
        mounted : function() {
            if(this.slides.length == 0)
                this.addSlide()
        }
    }
</script>

<style scoped lang="less">

    .add-slide-container {
        top: 20px;
        left: 0;
        right: 0;
        margin-left: auto;
        margin-right: auto;
        position: absolute;
        text-align: center;
    }

    .add-slide {
        border: 1px solid rgba(173, 216, 230, 0.5);
        padding: 5px 12px;
        margin: 0;
        cursor: pointer;
        border-radius: 50%;
        background-color: rgba(43, 92, 147, 0.65);
        color: #b8c7ce;
        font-size: 20px;
        left: 48.5%;
        top: 0;
        position: absolute;
        pointer-events: all;

        &:hover {
            background-color: rgba(43, 92, 147, 0.85);
            border: 1px solid rgba(173, 216, 230, 0.8);
        }
    }

    .arrow {
        border: 1px solid rgba(173, 216, 230, 0.21);
        padding: 10px 15px;
        margin: 0;
        cursor: pointer;
        border-radius: 50%;
        background-color: rgba(43, 92, 147, 0.21);
        color: #b8c7ce;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        text-align: center;
        font-size: 12px;

        &:hover {
            background-color: rgba(43, 92, 147, 0.8);
            border: 1px solid rgba(86, 132, 219, 0.18);
        }
    }

    .arrow-left {
        left: 16px;
        padding: 10px 16px 10px 14px;
    }

    .arrow-right {
        right: 16px;
        padding: 10px 14px 10px 16px;
    }

    .slide-controls {
        margin: 0;
        position: absolute;
        top: 5px;
        width: 100%;
        text-align: right;
        padding: 10px;
        pointer-events: none;
    }

    .control {
        border: 1px solid rgba(173, 216, 230, 0.5);
        padding: 10px 12px;
        margin: 1px;
        cursor: pointer;
        border-radius: 50%;
        background-color: rgba(43, 92, 147, 0.65);
        color: #b8c7ce;
        text-align: center;
        font-size: 15px;
        pointer-events: all;

        &:hover {
            background-color: rgba(43, 92, 147, 0.85);
            border: 1px solid rgba(173, 216, 230, 0.8);
        }
    }

    .slide-buttons {
        color: #b8c7ce;
        position: absolute;
        bottom: 5px;
        left: 0;
        right: 0;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
        font-size: 15px;
    }

    .button {
        padding: 1px 10px;
        background-color: transparent;
        border-radius: 50%;
        zoom: 0.45;
        border: 4px solid rgba(156, 195, 234, 0.85);
        color: transparent;
        cursor: pointer;
        margin-right: 2px;
        margin-left: 2px;
    }

    .button-active {
        border: 6px solid white;
        background-color: transparent;
    }

    .header {
        opacity: 1
    }
</style>
