<template>
    <div>
        <b-breadcrumb :items="items"/>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="alert alert-success" v-if="saved">
                    {{ $t('form.page_saved') }}
                </div>
            </div>
        </div>
        <div id="role-form">
            <form class="form-horizontal row" method="post" @submit.prevent="onSubmit">
                <fieldset class="col-md-8 text-left">
                    <div class="form-group">
                        <label class="control-label" for="title">{{ $t('general.title') }}</label>
                        <div :class="{'has-error': errors.title}">
                            <div class="input-group mb-0">
                                <span class="input-group-addon"><i class="icon-notebook" aria-hidden="true"></i></span>
                                <input id="title"
                                    v-model="page.title"
                                    @blur="setPageSlug"
                                    type="text"
                                    :placeholder="$t('general.title')"
                                    class="form-control">
                            </div>
                            <span v-if="errors.title" class="help-block text-danger">{{ errors.title[0] }}</span>
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="control-label" for="slug">{{ $t('general.slug') }}</label>
                        <div :class="{'has-error': errors.slug}">
                            <div class="input-group mb-0">
                                <span class="input-group-addon"><i class="icon-direction" aria-hidden="true"></i></span><span class="value-addon">{{ baseUrl }}/</span>
                                <input id="slug"
                                    v-model="page.slug"
                                    type="text"
                                    ref="slugInput"
                                    :readonly="disableSlug"
                                    @click="disableSlug = !disableSlug"
                                    @blur="disableSlug = true"
                                    :placeholder="$t('general.slug')"
                                    class="form-control no-border-left">
                            </div>
                            <span v-if="errors.slug" class="help-block text-danger">{{ errors.slug[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="description">{{ $t('general.description') }}</label>
                        <b-form-textarea id="description"
                            v-model="page.description"
                            :placeholder="$t('general.description')"
                            :rows="3"
                            :max-rows="3">
                        </b-form-textarea>
                        <span v-if="errors.description" class="help-block text-danger">{{ errors.description[0] }}</span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="content">{{ $t('general.content') }}</label>
                        <div class="editor">
                            <tinymce id="content" 
                                :other_options="tinyOptions" 
                                v-model="page.content"
                                :plugins="['advlist autolink lists link image charmap print preview hr anchor pagebreak', 'code', 'media', 'imagetools']"
                            ></tinymce>
                            <span v-if="errors.content" class="help-block text-danger">{{ errors.content[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="text-left">
                            <button type="submit" class="btn btn-primary btn-md">{{ $t('general.save') }}</button>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="col-md-4 text-left">    
                    <div class="form-group">
                        <label class="control-label">{{ $t('general.status') }}</label>
                        <div class="input-group mb-0">
                            <span class="input-group-addon"><i class="icon-info" aria-hidden="true"></i></span>
                            <b-form-select v-model="page.status" :options="status" class="mb-0" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">{{ $t('general.template') }}</label>
                        <div class="input-group mb-0">
                            <span class="input-group-addon"><i class="icon-info" aria-hidden="true"></i></span>
                            <b-form-select v-model="page.template" :options="templateOptions" class="mb-0" />
                        </div>
                    </div>
                    <div v-if="action == 'update'" class="form-group">
                        <label class="control-label">{{ $t('general.preview') }}</label>
                        <div class="clearfix"></div>
                        <a :href="baseUrl + '/' + page.slug" class="btn btn-primary" target="_blank">{{ $t('general.preview') }}</a>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</template>

<script>
    import tinymce from 'vue-tinymce-editor'

    Vue.component('tinymce', tinymce)

    export default {
        data() {
            return {
                errors: [],
                saved: false,
                disableSlug: true,
                page : {status: 'inactive', post_type: 'page', template: 'default-page'},
                templateOptions: [
                     {value: 'default-page', text: 'Default'},
                ],
                action : '',
                status: [
                    {value: 'inactive', text: 'Inactive'},
                    {value: 'active', text: 'Active'},
                ],
                items: [{
                    text: this.$t('general.home'),
                    to: { name: 'home' },
                }, {
                    text: this.$t('general.pages'),
                    to: { name: 'pages' },
                }, {
                    text: ((this.$route.params.id - 0) ? this.$t('general.edit') : this.$t('general.create')),
                    active: true
                }],
                tinyOptions: {
                    'height': 300
                }
            };
        },
        created () {
            this.action = ((this.$route.params.id - 0) ? 'update' : 'create');
              
            // Fetch the data when the view is created and the data is already being observed
            this.fetchData();
        },
        computed: {
            baseUrl: function () { return window.baseUrl; },
        },
        watch: {
            // Call again the method if the route changes
            '$route': 'fetchData'
        },
        methods: {
            fetchData () {
                if ((this.$route.params.id - 0)) {
                    // We only need this if post id is set in params
                    axios.get('/api/v1/posts/' + this.$route.params.id)
                        .then(({data}) => this.page = data.data)
                        .catch(({response}) => console.log(response));
                }
            }, 
            onSubmit() {
                this.saved = false;

                if (this.action == 'create') {
                    axios.post('/api/v1/posts', this.page)
                        .then(({data}) => this.setSuccessMessage())
                        .catch(({response}) => this.setErrors(response));
                } else if (this.action == 'update') {
                    axios.put('/api/v1/posts/' + this.page.id, this.page)
                        .then(({data}) => this.setSuccessMessage())
                        .catch(({response}) => this.setErrors(response));
                }
            },   
            setErrors(response) {
                this.errors = response.data.errors;
            },
            setSuccessMessage() {
                this.reset();
                this.saved = true;
                setTimeout(function () { this.saved = false }.bind(this), 3000)
            },
            reset() {
                if (this.action == 'create') {
                    this.page = {status: 'inactive', post_type: 'page', template: 'default-page'};
                } 
                this.errors = [];
            },
            getUrlSafeString(string) {
                return string.replace(/[^a-z0-9]/gi, '-').toLowerCase();
            },
            setPageSlug() {

                if ((this.$route.params.id - 0)) {
                     this.page.slug = this.getUrlSafeString(this.page.slug);
                } else {
                    this.page.slug = this.getUrlSafeString(this.page.title);
                }

                this.$forceUpdate();
            }
        },    
    }
</script>
<style>
    span.value-addon {
        line-height: 2.2em;
        padding: 0 .3rem 0;
        border: 1px solid #ced4da;
        border-right: none;
    }
    .no-border-left {
        border-left: none;
    }
</style>