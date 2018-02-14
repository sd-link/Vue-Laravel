<template>
    <div @mouseover="mouseHover()" @mouseleave="mouseLeave()" class="slider-block" style="position: relative; overflow: hidden;" v-bind:style="{'height': sliderHeight}">
        <transition-group name="custom-classes-transition" :enter-active-class="'animated ' + enterTransition" :leave-active-class="'animated ' + leaveTransition">
            <component v-for="(slide, index) in slides" :style="{'z-index': zindex(index)}"
              v-bind:is="slide.type"
              v-show="visible(index)"
              :slide-id="slide.id"
              :content="slide.content"
              :device-type="deviceType"
              :subBlocks="slide.subBlocks"
              :content-render-style="contentRenderStyle"
              :key="slide.unique_id">
            </component>
        </transition-group>

        <div v-show="displayButtons()" class="slide-buttons">
            <!-- {{ deviceType }}: {{ windowWidth }} x {{ windowHeight }}
            <div v-if="windowWidth > windowHeight">
                Landscape
            </div>
            <div v-else>
                Portrait
            </div> -->
            <span v-for="(slide, index) in slides" @click="jumpToSlide(index)" class="button" :class="{ 'button-active': visible(index) }"></span>
        </div>
        <div v-if="showProgressBar" class="slide-progress">
            <progress-bar type="line" ref="line" :options="progressBarOptions"></progress-bar>
        </div>
        <span v-show="displayArrows()" @click="prev()" class="arrow arrow-left"><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
        <span v-show="displayArrows()" @click="next()" class="arrow arrow-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { processSettings } from 'utils/helpers.js'
    import ProgressBar from 'components/ui/vue-progress/Progress'

    export default {
        components: {
            ProgressBar
        },
        data: function data() {
            return {
                slider: null,
                duration: 500,
                transitions: {
                    fade: 'Fade',
                    slideHorisontal: 'Slide Horisontal',
                    slideVertical: 'Slide Vertical',
                    zoomOutSlideIn: 'Zoom Out Slide In',
                    rubberBand: 'Rubber Band',
                },
                slideDirection: '',
                currentSlide: 0,
                previousSlide: 0,
                transition: this.initTransition,
                showButtons: this.initButtons,
                showButtonsWhen: this.initShowButtonsWhen,
                showArrows: this.initArrows,
                showArrowsWhen: this.initShowArrowsWhen,
                heightResponsive: this.initHeightResponsive,
                height: this.initHeight,
                heightTabletPortrait: this.initHeightTabletPortrait,
                heightTabletLandscape: this.initHeightTabletLandscape,
                heightMobilePortrait: this.initHeightMobilePortrait,
                heightMobileLandscape: this.initHeightMobileLandscape,
                contentRenderStyle: this.initContentRenderStyle,
                width: this.initWidth,
                autoPlay: this.initAutoplay,
                autoPlayDuration: this.initAutoplayDuration,
                showProgressBar: (this.initShowProgressBar && this.initAutoplay),
                playTimer: null,
                mousehovering: false,
                windowWidth: 0,
                windowHeight: 0,
                progressBarOptions: {
                    color: this.initProgressBarColor,
                    strokeWidth: 0.25,
                    trailColor: 'rgba(0,0,0,0)'
                }
            }
        },
        props: {
            initBlockTitle: {type: String, default: 'Slider'},
            initWidth: {type: String, default: '100%'},
            initContentRenderStyle: {type: String, default: 'boxed'},

            initHeightResponsive: {type: Boolean, default: false},
            initHeight: {type: String, default: '450px'},
            initHeightTabletPortrait: {type: String, default: '450px'},
            initHeightTabletLandscape: {type: String, default: '450px'},
            initHeightMobilePortrait: {type: String, default: '450px'},
            initHeightMobileLandscape: {type: String, default: '450px'},

            initArrows: {type: Boolean, default: true},
            initShowArrowsWhen: {type: String, default: 'always'},
            initButtons: {type: Boolean, default: true},
            initShowButtonsWhen: {type: String, default: 'always'},
            initAutoplay: {type: Boolean, default: true},
            initAutoplayDuration: {type: Number, default: 6000},
            initLoop: {type: Boolean, default: true},
            initTransition: {type: String, default: 'slideHorisontal'},
            slides: {type: Array, default: null},
            deviceType: {type: String, default: 'mobile'},

            initShowProgressBar: {type: Boolean, default: false},
            initProgressBarColor: {type: String, default: '#007AFF'},
        },
        computed: {
            deviceMode: function() {
                if(this.windowWidth > this.windowHeight)
                    return 'landscape'
                else
                    return 'portrait'
            },
            sliderHeight: function() {
                if(this.heightResponsive) {
                    switch (this.deviceType) {
                        case 'desktop':
                            return this.height
                        break;
                        case 'tablet':
                            if(this.deviceMode == 'landscape')
                                return this.heightTabletLandscape
                            else
                                return this.heightTabletPortrait
                        break;
                        case 'mobile':
                            if(this.deviceMode == 'landscape')
                                return this.heightMobileLandscape
                            else
                                return this.heightMobilePortrait
                            break;
                        default:

                    }
                } else {
                    return this.height
                }
            },
            enterTransition: function() {
                var transition
                switch (this.transition) {
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
                switch (this.transition) {
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
        },
        watch: {
            currentSlide(val, old) {
                this.previousSlide = val
            },
        },
        methods: {
            getWindowWidth(event) {
                this.windowWidth = document.documentElement.clientWidth
            },
            getWindowHeight(event) {
                this.windowHeight = document.documentElement.clientHeight
            },
            displayButtons() {
                if(this.showButtons && this.showButtonsWhen=='always')
                    return true
                else if(this.showButtons && this.showButtonsWhen=='hover' && this.mousehovering)
                    return true
                else
                    return false
            },
            displayArrows() {
                if(this.showArrows && this.showArrowsWhen=='always')
                    return true
                else if(this.showArrows && this.showArrowsWhen=='hover' && this.mousehovering)
                    return true
                else
                    return false
            },
            mouseHover() {
                this.stopTimer()
                if(this.showProgressBar) {
                    this.$refs.line.stop()
                    this.$refs.line.set(0)
                }
                this.mousehovering = true
            },
            mouseLeave() {
                this.startTimer()
                if(this.showProgressBar) {
                    this.$refs.line.set(0)
                    this.$refs.line.animate(1, {duration: this.autoPlayDuration})
                }
                this.mousehovering = false
            },
            startTimer() {
                this.playTimer = window.setInterval(this.next, this.autoPlayDuration)
            },
            stopTimer() {
                window.clearInterval(this.playTimer)
                this.playTimer = null
            },
            jumpToSlide(index) {
                this.currentSlide = index
                // console.log('current: ' + this.currentSlide)
                // console.log('current: ' + this.previousSlide)

                if(this.currentSlide > this.previousSlide) {
                    this.slideDirection = 'next'
                    // console.log('next')
                }
                else if(this.currentSlide < this.previousSlide) {
                    this.slideDirection = 'prev'
                    // console.log('prev')
                }
            },
            visible(val) {
                return val == this.currentSlide
            },
            zindex(val, old) {
                if(val == this.currentSlide)
                    return '1'
            },
            next() {
                if(this.showProgressBar) {
                    this.$refs.line.set(0)
                    this.$refs.line.animate(1, {duration: this.autoPlayDuration})
                }

                this.slideDirection = 'next'
                this.currentSlide = this.currentSlide + 1

                if(this.currentSlide > (this.slides.length-1))
                    this.currentSlide = 0
            },
            prev() {
                if(this.showProgressBar) {
                    this.$refs.line.set(0)
                    this.$refs.line.animate(1, {duration: this.autoPlayDuration})
                }

                this.slideDirection = 'prev'
                this.currentSlide = this.currentSlide - 1

                if(this.currentSlide < 0)
                    this.currentSlide = (this.slides.length-1)
            }
        },
        mounted : function() {
            this.$nextTick(function() {
              window.addEventListener('resize', this.getWindowWidth);
              window.addEventListener('resize', this.getWindowHeight);

              //Init
              this.getWindowWidth()
              this.getWindowHeight()
            })

            if(this.autoPlay) {
                this.startTimer()
                if(this.showProgressBar) {
                    this.$refs.line.animate(1, {duration: this.autoPlayDuration})
                }
            }
        }
    }
</script>

<style scoped lang="less">
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
        z-index: 10;
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

    .slide-buttons {
        color: #b8c7ce;
        position: absolute;
        bottom: 10px;
        left: 0;
        right: 0;
        margin-left: auto;
        margin-right: auto;
        z-index: 10;
        text-align: center;
        font-size: 15px;
    }

    .slide-progress {
        position: absolute;
        bottom: 0px;
        left: 0;
        right: 0;
        margin-left: auto;
        margin-right: auto;
        z-index: 10;
    }

    .button {
        cursor: pointer;
        display: inline-grid;
        width:12px;
        height:12px;
        border: 3px solid rgba(156, 195, 234, 0.85);
        border-radius: 50%;
        background: transparent;
        // box-shadow: 0 0 3px gray;
        margin: 2px;

        &:hover {
            background: #262626;
        }
    }

    .button-active {
        border: 3px solid white;
        background-color: transparent;
    }
</style>
