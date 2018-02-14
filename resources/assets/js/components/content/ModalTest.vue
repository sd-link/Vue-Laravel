<template>
    <div>
        <button type="button" class="btn btn-default" @click="showTestModal = !showTestModal">Modal test</button>
        <modal ref="testModal"
            v-model="showTestModal"
            :backdrop="true"
            title="Title"
            v-on:opened="openedAction"
            v-on:closed="closedAction"
        >
            <div class="modal-body" slot="modal-body">
                <div>
                    <button type="button" class="btn btn-default" @click="activeTab = 0">Set 1 tab</button>
                    <button type="button" class="btn btn-default" @click="activeTab = 1">Set 2 tab</button>
                    <br>
                    <br>
                    <tabs v-model="activeTab" @active="handleTabChange" nav-style="tabs">
                        <tab header="Tab 1">
                            Lorem 1<br>
                            Lorem 1<br>
                            Lorem 1<br>
                        </tab>
                        <tab header="Tab 2">
                            Lorem 2<br>
                            Lorem 2<br>
                            Lorem 2<br>
                        </tab>
                    </tabs>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
import modal from 'components/ui/Modal'
import tabs from 'components/ui/Tabs'
import tab from 'components/ui/Tab'

export default {
    components: {
        modal,
        tabs,
        tab
    },
    data () {
        return {
            showTestModal: false,
            activeTab: 0
        }
    },
    methods: {
        openedAction () {
            console.log('Opened Action')
        },
        closedAction () {
            console.log('Closed Action')
        },
        handleTabChange (tabIndex) {
            console.log('Handle Tab Change', tabIndex+1)
        }
    }
}
</script>
