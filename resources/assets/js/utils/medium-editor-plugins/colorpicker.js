import MediumEditor from 'medium-editor'
import $ from 'jquery'
import 'spectrum-colorpicker'
import 'spectrum-colorpicker/spectrum.css'

let currentTextSelection

function setColor(color) {
    let finalColor = color ? color.toRgbString() : 'rgba(0,0,0,0)'

    pickerExtension.base.importSelection(currentTextSelection)
    pickerExtension.document.execCommand("styleWithCSS", false, true)
    pickerExtension.document.execCommand("foreColor", false, finalColor)
}

function initPicker(element) {
    $(element).spectrum({
        allowEmpty: true,
        color: "#f00",
        showInput: true,
        showAlpha: true,
        showPalette: true,
        showInitial: true,
        hideAfterPaletteSelect: true,
        preferredFormat: "hex3",
        change: function(color) {
            setColor(color)
        },
        hide: function(color) {
            setColor(color)
        },
        palette: [
            ["#000", "#444", "#666", "#999", "#ccc", "#eee", "#f3f3f3", "#fff"],
            ["#f00", "#f90", "#ff0", "#0f0", "#0ff", "#00f", "#90f", "#f0f"],
            ["#f4cccc", "#fce5cd", "#fff2cc", "#d9ead3", "#d0e0e3", "#cfe2f3", "#d9d2e9", "#ead1dc"],
            ["#ea9999", "#f9cb9c", "#ffe599", "#b6d7a8", "#a2c4c9", "#9fc5e8", "#b4a7d6", "#d5a6bd"],
            ["#e06666", "#f6b26b", "#ffd966", "#93c47d", "#76a5af", "#6fa8dc", "#8e7cc3", "#c27ba0"],
            ["#c00", "#e69138", "#f1c232", "#6aa84f", "#45818e", "#3d85c6", "#674ea7", "#a64d79"],
            ["#900", "#b45f06", "#bf9000", "#38761d", "#134f5c", "#0b5394", "#351c75", "#741b47"],
            ["#600", "#783f04", "#7f6000", "#274e13", "#0c343d", "#073763", "#20124d", "#4c1130"]
        ]
    })
}

/**
 * Custom `color picker` extension
 */
var ColorPickerExtension = MediumEditor.extensions.button.extend({
    name: "colorPicker",
    action: "applyForeColor",
    aria: "color picker",
    contentDefault: "<span class='editor-color-picker'>Text Color<span>",

    init: function() {
        this.button = this.document.createElement('button')
        this.button.classList.add('medium-editor-action')
        this.button.innerHTML = '<b>Text color</b>'
        
        //init spectrum color picker for this button
        initPicker(this.button)
        
        //use our own handleClick instead of the default one
        this.on(this.button, 'click', this.handleClick.bind(this));
    },
    handleClick: function (event) {
        //keeping record of the current text selection
        currentTextSelection = this.base.exportSelection()

        let currentTextColor = $(this.base.getSelectedParentElement()).css('color')

        //sets the color of the current selection on the color picker
        $(this.button).spectrum("set", currentTextColor)

        //from here on, it was taken form the default handleClick
        event.preventDefault()
        event.stopPropagation()

        let action = this.getAction()

        action && this.execAction(action)
    }
})

var pickerExtension = new ColorPickerExtension()

export default pickerExtension
