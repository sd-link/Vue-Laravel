<template>
    <div>
        <input type="text" class="form-control"
            ref="input"
            v-bind:value="value"
            v-on:blur="onBlur"
            v-on:keydown="onKeydown"
            v-on:keyup="onKeyup"
        />
    </div>
</template>

<script>
    export default {
        props: {
            value: {type: String},
            maxAllowed: {type: Number, default: 4}
        },
        data() {
            return {
                tempValue: null
            }
        },
        methods: {
            processValue(value, final = false) {
                if (!value) return ''
                value = value.trim().replace(/\s+/g, ' ')
                let arr = value.split(' ')
                let newArr = []

                arr = arr.splice(0, this.maxAllowed)

                for (const [i, value] of arr.entries()) {
                    let val
                    let allowedStrings = 'auto|inherit|initial'
                    let allowedUnits = 'px|\%'
                    let re1 = new RegExp('^([0-9]+)(' + allowedUnits + ')$', 'gim')
                    let re2 = new RegExp('^(' + allowedStrings + ')$', 'gim')

                    let test1 = re1.test(value)
                    let test2 = re2.test(value)

                    if ( test1 || test2 ) {
                        val = value
                    } else if (!test1 && !/^[a-zA-Z]/.test(value)) {
                        let digits = value.replace(/[^0-9]/g, '')
                        val = (digits.length ? digits : 0) + 'px'
                    } else if (!test2) {
                        val = 'auto'
                    }

                    newArr.push(val)
                }
                return newArr.join(' ') + ((final || newArr.length == this.maxAllowed) ? '' : ' ')
            },
            getDisabling(value, final = false) {
                if (!value) return ''
                value = value.trimLeft().replace(/\s+/g, ' ');
                let arr = value.split(' ')
                let output = false

                arr = arr.splice(0, this.maxAllowed)

                for (const [i, value] of arr.entries()) {
                    let val
                    let re = new RegExp('^[0-9]*[a-zA-Z%]*$')

                    if ( !re.test(value) ) {
                        output = true
                    }
                }
                return output
            },
            onKeydown(e) {
                // https://stackoverflow.com/questions/3923089/can-i-conditionally-change-the-character-entered-into-an-input-on-keypress/3923320#3923320
                let expectedValue = e.target.value
                let currentValue = e.target.value
                let charCode = typeof e.which == "number" ? e.which : e.keyCode
                charCode = (96 <= charCode && charCode <= 105)? charCode-48 : charCode
                let keyChar = String.fromCharCode(charCode)

                // console.log('TEST FOR KEY CODE PROPAGATION', {
                //     charCode,
                //     keyChar
                // })

                if (typeof e.target.selectionStart == "number" && typeof e.target.selectionEnd == "number") {
                    let start = e.target.selectionStart
                    let end = e.target.selectionEnd
                    expectedValue = currentValue.slice(0, start) + keyChar + currentValue.slice(end)
                }

                let processedVal = this.processValue(expectedValue)
                let disabled = this.getDisabling(expectedValue)

                // console.log('TEST FOR EXPECTED VALUE', {
                //     currentValue,
                //     expectedValue,
                //     processedVal,
                //     disabled
                // })

                let allowedBool = ( // Allow: backspace, delete, tab, escape, enter and .
                    [46, 8, 9, 27, 13, 110, 190].indexOf(e.keyCode) >= 0 ||
                    // Allow: Ctrl+A,Ctrl+C,Ctrl+V, Command+A
                    ((e.keyCode == 65 || e.keyCode == 86 || e.keyCode == 67) && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: home, end, left, right, down, up
                    (e.keyCode >= 35 && e.keyCode <= 40)
                )

                if ( !allowedBool && [32].indexOf(e.keyCode) < 0 ) {
                    if (disabled) {
                        e.stopPropagation()
                        e.preventDefault()
                    }
                } else if ( allowedBool ) {
                    return true
                } else {
                    this.tempValue = this.processValue(expectedValue)
                }
            },
            onKeyup(e) {
                let val = this.tempValue ? this.tempValue : e.target.value
                this.tempValue = null

                this.$refs.input.value = val
                this.$emit('input', val)
            },
            onBlur(e) {
                let processedVal = this.processValue(e.target.value, true)

                this.$refs.input.value = processedVal
                this.$emit('input', processedVal)
            }
        }
    }
</script>
