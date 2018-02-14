<template>
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Website Settings</h3>
        </div>
        <div class="box-body noselect">
            <div class="form-group">
                <label>Title</label>
                <input type="text" class="form-control" v-model="websiteTitle">
            </div>

            <div class="form-group">
                <label>Tagline</label>
                <input type="text" class="form-control" v-model="tagline">
            </div>

            <div class="form-group">
                <label>Site Url</label>
                <input type="text" class="form-control" v-model="siteUrl">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" v-model="email">
            </div>

            <div class="form-group">
                <label>Admin Language</label>
                <select class="form-control" v-model="adminLanguage">
                    <option value="english">English</option>                                        
                    <option value="german">German</option>
                    <option value="french">French</option>
                    <option value="italian">Italian</option>
                    <option value="spanish">Spanish</option>
                    <option value="swedish">Swedish</option>
                    <option value="danish">Danish</option>
                </select>
            </div>
        </div>
        <div class="box-body noselect">
            <button @click="saveSettings()" type="button" name="button" class="btn btn-primary">Save</button>
        </div>                                                                                                                                                          
    </div>
</template>

<script>
    import Vue from 'vue'

    export default {
        data: function data() {
            return {
                websiteTitle: this.initWebsiteTitle,
                tagline: this.initTagline,
                siteUrl: this.initSiteUrl,
                email: this.initEmail,
                adminLanguage: this.initAdminLanguage,
            }
        },
        props: {
            initWebsiteTitle: {type: String, default: ''},
            initTagline: {type: String, default: ''},
            initSiteUrl: {type: String, default: ''},
            initEmail: {type: String, default: ''},
            initAdminLanguage: {type: String, default: 'english'},
        },
        watch: {

        },
        computed: {

        },
        methods: {
            saveSettings() {
                let settings = {
                    websiteTitle: {type: 'string', value: this.websiteTitle},
                    tagline: {type: 'string', value: this.tagline},
                    siteUrl: {type: 'string', value: this.siteUrl},
                    email: {type: 'string', value: this.email},
                    adminLanguage: {type: 'string', value: this.adminLanguage},
                }

                let formData = {
                    settings: settings,
                }

                return axios.post(route('backend.settings.website.save'), formData)
                    .then((response) => {

                    })
                    .catch((error) => {

                    })
            },
        },
        mounted: function() {

        }
    }
</script>

<style lang="less" scoped>

</style>
