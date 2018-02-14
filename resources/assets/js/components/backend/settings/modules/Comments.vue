<template>
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Comments Settings</h3>
        </div>
        <div class="box-body noselect">                                                                                         
            <h4>Enable Comments</h4>
            <div class="form-group">
                <label>Comments type</label>
                <select class="form-control" v-model="commentsType" @change="changeCommentType">                                       
                    <option id="comments_native" value="native" selected="selected">Native</option>
                    <option id="comments_disqus" value="disqus">Disqus</option>                
                </select>
            </div>
            <div id="native_basic_settings" class="form-group native_settings">
                <h4>Settings</h4>
                <div class="form-group">
                    <label>Logged in to comment</label>
                    <select class="form-control" v-model="loggedInToComment">
                        <option v-bind:value ="true">Yes</option>
                        <option v-bind:value ="false">No</option>                  
                    </select>
                </div>

                <div class="form-group">
                    <label>Allow nested comments</label>
                    <select class="form-control" v-model="allowNestedComments">
                        <option v-bind:value ="true">Yes</option>
                        <option v-bind:value ="false">No</option>                    
                    </select>
                </div>

                <div class="form-group">
                    <label>Nested level depth</label>
                    <select class="form-control" v-model="nestedLevelDepth">
                        <option value="2" selected="selected">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>                  
                    </select>
                </div>

                <div class="form-group">
                    <label>Comments order</label>
                    <select class="form-control" v-model="commentsOrder">
                        <option value="asc" selected="selected">Oldest</option>
                        <option value="desc">Newest</option>                  
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Moderation</label>
                    <select class="form-control" v-model="moderation">
                        <option v-bind:value ="true">Yes</option>
                        <option v-bind:value ="false">No</option>                     
                    </select>
                </div>

                <div class="form-group">
                    <label>Moderation for approved users</label>
                    <select class="form-control" v-model="moderationForApprovedUsers">
                        <option v-bind:value ="true">Yes</option>
                        <option v-bind:value ="false">No</option>                   
                    </select>
                </div>
            </div>
            <div id="native_notifications" class="form-group native_settings">
                <h4>Notfications</h4>
                <div class="form-group">
                    <label>
                       <input class="icheck_input" id="emailAdminOnNewComments" v-model="emailAdminOnNewComments" type="checkbox"> <span style="margin-left: 10px;">Email admin when new comments are posted.</span>
                   </label>
                </div>  

                <div class="form-group">
                    <label>
                       <input class="icheck_input" id="emailOnAdminOnModeration" v-model="emailOnAdminOnModeration" type="checkbox"> <span style="margin-left: 10px;">Email admin when comment needs to be approved.</span>
                   </label>
                </div> 
            </div>

            <div id="disqus_config" class="form-group native_settings">
                <h4>Disqus Settings</h4>
                <div class="form-group">
                    <label>Disqus channel</label>
               
                    <input class="form-control" v-model="disqusChannel"
                            v-validate="'required'"
                            :class="{'form-control':true, 'input': true, 'is-danger': errors.has('title') }"
                            name="title"
                            type="text"
                            placeholder="Enter Title">
                        <span v-show="errors.has('title')" class="help is-danger">{{ errors.first('title') }}</span>
                </div>                
            </div>          
        </div>
        <div class="box-body noselect">
            <button @click="validationFields()" type="button" name="button" class="btn btn-primary">Save Settings</button>
        </div>                                                                                                                                                          
    </div>
</template>

<script>
    import Vue from 'vue'

    export default {
        created: function(){                               
        },
        data: function data() {            
            return {
                commentsType: this.initCommentsType,
                loggedInToComment: this.initLoggedInToComment,
                allowNestedComments: this.initAllowNestedComments,
                nestedLevelDepth: this.initNestedLevelDepth,
                commentsOrder: this.initCommentsOrder,
                moderation: this.initModeration,
                moderationForApprovedUsers: this.initModerationForApprovedUsers,
                emailAdminOnNewComments: this.initEmailAdminOnNewComments,
                emailOnAdminOnModeration: this.initEmailOnAdminOnModeration,
                disqusChannel: this.initDisqusChannel,   
                nativeFlag: true,                          
            }
        },
        props: {
            initCommentsType: {type: String, default: 'native'},
            initLoggedInToComment: {type: Boolean, default: true},
            initAllowNestedComments: {type: Boolean, default: true},
            initNestedLevelDepth: {type: String, default: '2'},
            initCommentsOrder: {type: String, default: 'asc'},
            initModeration: {type: Boolean, default: true},
            initModerationForApprovedUsers: {type: Boolean, default: true},            
            initEmailAdminOnNewComments: {type: Boolean, default: false},
            initEmailOnAdminOnModeration: {type: Boolean, default: false},
            initDisqusChannel: {type: String, default: ''},            
        },
        watch: {
            
        },
        computed: {

        },
        methods: {
            validationFields() {
                if(this.nativeFlag) this.saveSettings();
                else{
                    this.$validator.validateAll().then(result =>{
                    if(result)
                        this.saveSettings();
                    })
                }
            },
            saveSettings() {                
                let settings = {
                    commentsType: {type: 'string', value: this.commentsType},
                    loggedInToComment: {type: 'boolean', value: this.loggedInToComment},
                    allowNestedComments: {type: 'boolean', value: this.allowNestedComments},
                    nestedLevelDepth: {type: 'string', value: this.nestedLevelDepth},
                    commentsOrder: {type: 'string', value: this.commentsOrder},
                    moderation: {type: 'boolean', value: this.moderation},
                    moderationForApprovedUsers: {type: 'boolean', value: this.moderationForApprovedUsers},            
                    emailAdminOnNewComments: {type: 'boolean', value: $('#emailAdminOnNewComments').is(":checked")},
                    emailOnAdminOnModeration: {type: 'boolean', value: $('#emailOnAdminOnModeration').is(":checked")},
                    disqusChannel: {type: 'string', value: this.disqusChannel},
                }

                let formData = {
                    settings: settings,
                }

                return axios.post(route('backend.settings.comments.save'), formData)
                    .then((response) => {

                    })
                    .catch((error) => {

                    })
            },
            changeCommentType(){                
                if(this.commentsType == "native")
                {
                    $('#native_basic_settings').show();
                    $('#native_notifications').show();
                    $('#disqus_config').hide();
                    this.nativeFlag = true;
                }
                else 
                {
                    $('#native_basic_settings').hide();
                    $('#native_notifications').hide();
                    $('#disqus_config').show();
                    this.nativeFlag = false;
                }                          
            },
        },
        mounted: function() {        
            this.changeCommentType();    
        }
    }
</script>

<style lang="less" scoped>

</style>
