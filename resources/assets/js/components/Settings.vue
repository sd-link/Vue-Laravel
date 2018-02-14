<template>
    <div class="col-md-2" style="position: absolute; left: 0; border: 1px solid #ddd; width: 15.7%;">
        <div class="">
            <label for="">Background Image</label>
            <input type="text" name="" value="">
        </div>
        <div class="">
            <label for="">Some Option</label>
            <input type="text" name="" value="">
        </div>
        <div class="">
            <label for="">Some Option</label>
            <input type="text" name="" value="">
        </div>
    </div>
</template>

<script>

    export default {
        data () {
            return {
                visible: false,
                test: true,

            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
