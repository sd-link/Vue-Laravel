<template>
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Website Settings</h3>
        </div>
        <div class="box-body noselect">
            <h4>Registrations</h4>
            <div class="form-group">
                <label>Who can register?</label>
                <select class="form-control" v-model="whoCanRegister">
                    <option value="everyone">Everyone</option>                                        
                    <option value="noone" selected="selected">No-One</option>                    
                </select>
            </div>

            <h4>Basic Settings</h4>
            <div class="form-group">
                <label>Default New User Role</label>
                <select class="form-control" v-model="defaultUserRole">                   
                    <option value="member" selected="selected">Member</option>
                    <option value="admin">Admin</option>                 
                </select>
            </div>

            <div class="form-group">
                <label>User Display Name</label>
                <select class="form-control" v-model="userDisplayName">
                    <option value="fullname">First Name & Last Name</option>
                    <option value="fullname">Last Name & First Name</option>
                    <option value="username" selected="selected">Username</option>                   
                </select>
            </div>

             <h4>Registration Options</h4>
            <div class="form-group">
                <label>Use Recaptcha on registration</label>
                <select class="form-control" v-model="useRecaptcha">
                    <option v-bind:value ="true">Yes</option>
                    <option v-bind:value ="false">No</option>                 
                </select>
            </div>

            <div class="form-group">
                <label>Registration Status</label>
                <select class="form-control" v-model="registrationStatus">
                    <option value="member">Auto Approve</option>
                    <option value="email">Activate by Email</option>
                    <option value="admin">Require Admin Review</option>                   
                </select>
            </div>

            <div class="form-group">
                <label>Require First and Last Name?</label>
                <select class="form-control" v-model="requireFirstLastName">
                    <option v-bind:value ="true">Yes</option>
                    <option v-bind:value ="false">No</option>                 
                </select>
            </div>

            <div class="form-group">
                <label>Require a strong password?</label>
                <select class="form-control" v-model="requireStrongPassword">
                    <option v-bind:value ="true">Yes</option>
                    <option v-bind:value ="false">No</option>                 
                </select>
            </div>
           
            <div class="form-group">
                <label>Blacklist Username Words</label>
                <textarea  v-model="blacklistUserNameWords" rows="6" cols="27" class="form-control">
admin
administrator
webmaster
support
staff
                </textarea>
            </div>

            <h4>Notfications</h4>
            <div class="form-group">
                <label>New User Notification</label>
                <select class="form-control" v-model="newUserNotification">
                    <option v-bind:value ="true">Yes</option>
                    <option v-bind:value ="false">No</option>                  
                </select>
            </div>            
        </div>
        <div class="box-body noselect">
            <button @click="saveSettings()" type="button" name="button" class="btn btn-primary">Save Settings</button>
        </div>                                                                                                                                                          
    </div>
</template>

<script>
    import Vue from 'vue'

    export default {
        data: function data() {
            return {
            whoCanRegister: this.initWhoCanRegister,
            defaultUserRole: this.initDefaultUserRole,
            userDisplayName: this.initUserDisplayName,
            useRecaptcha: this.initUseRecaptcha,
            registrationStatus: this.initRegistrationStatus,

            requireFirstLastName: this.initRequireFirstLastName,
            requireStrongPassword: this.initRequireStrongPassword,
            newUserNotification: this.initNewUserNotification,
            blacklistUserNameWords: this.initBlacklistUserNameWords,
            }
        },
        props: {
            initWhoCanRegister: {type: String, default: 'noone'},
            initDefaultUserRole: {type: String, default: 'member'},
            initUserDisplayName: {type: String, default: 'username'},
            initUseRecaptcha: {type: Boolean, default: true},
            initRegistrationStatus: {type: String, default: 'admin'},

            initRequireFirstLastName: {type: Boolean, default: true},
            initRequireStrongPassword: {type: Boolean, default: true},
            initNewUserNotification: {type: Boolean, default: true},
            initBlacklistUserNameWords: {type: String, default: ''},
        },
        watch: {

        },
        computed: {

        },
        methods: {
             saveSettings() {
                let settings = {
                    whoCanRegister: {type: 'string', value: this.whoCanRegister},
                    defaultUserRole: {type: 'string', value: this.defaultUserRole},
                    userDisplayName: {type: 'string', value: this.userDisplayName},
                    useRecaptcha: {type: 'boolean', value: this.useRecaptcha},
                    registrationStatus: {type: 'string', value: this.registrationStatus},
                    requireFirstLastName: {type: 'boolean', value: this.requireFirstLastName},
                    requireStrongPassword: {type: 'boolean', value: this.requireStrongPassword},
                    newUserNotification: {type: 'boolean', value: this.newUserNotification},
                    blacklistUserNameWords: {type: 'string', value: this.blacklistUserNameWords},
                }

                let formData = {
                    settings: settings,
                }

                return axios.post(route('backend.settings.members.save'), formData)
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
