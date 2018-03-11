<template>
    <div>
        <b-breadcrumb :items="items"/>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="alert alert-success" v-if="saved">
                    {{ $t('form.role_saved') }}
                </div>
            </div>
        </div>
        <div class="well well-sm" id="role-form">
            <form class="form-horizontal" method="post" @submit.prevent="onSubmit">
                <fieldset class="col-md-8 offset-md-2 text-left">
                    <div class="form-group">
                        <label class="control-label" for="name">{{ $t('general.name') }}</label>
                        <div :class="{'has-error': errors.name}">
                            <div class="input-group mb-0">
                                <span class="input-group-addon"><i class="icon-organization" aria-hidden="true"></i></span>
                                <input id="name"
                                    v-model="role.name"
                                    type="text"
                                    :placeholder="$t('general.name')"
                                    class="form-control">
                            </div>
                            <span v-if="errors.name" class="help-block text-danger">{{ errors.name[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">{{ $t('general.guard') }}</label>
                        <div class="input-group mb-0">
                            <span class="input-group-addon"><i class="icon-lock" aria-hidden="true"></i></span>
                            <b-form-select v-model="role.guard" :options="guards" class="mb-0" />
                        </div>
                    </div>
                    <b-form-group :label="$t('general.permissions')">
                        <b-form-checkbox-group v-model="permission.checked" v-for="(permission, index) in permissions" :key="index">
                            <p class="text-muted mt-2 mb-0" v-if="index > 0 && getLastPartOfString(permissions[index-1].name) !== getLastPartOfString(permission.name)">{{ capitalize(getLastPartOfString(permission.name)) }}</p>
                            <b-form-checkbox :v-value="permission.id">{{ permission.display }} ({{ permission.guard }})</b-form-checkbox>
                        </b-form-checkbox-group>
                    </b-form-group>
                    <div class="form-group">
                        <div class="text-left">
                            <button type="submit" class="btn btn-primary btn-md">{{ $t('general.save') }}</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                errors: [],
                saved: false,
                role : {guard: 'api'},
                permissions: [],
                action : '',
                guards: [
                    { value: 'api', text: 'api'},
                    { value: 'web', text: 'web'},
                ],
                items: [{
                    text: this.$t('general.home'),
                    to: { name: 'home' },
                }, {
                    text: this.$t('general.roles'),
                    to: { name: 'roles' },
                }, {
                    text: ((this.$route.params.id - 0) ? this.$t('general.edit') : this.$t('general.create')),
                    active: true
                }]
            };
        },
        created () {
            this.action = ((this.$route.params.id - 0) ? 'update' : 'create');
              
            // Fetch the data when the view is created and the data is already being observed
            this.fetchData();
        },
        watch: {
            // Call again the method if the route changes
            '$route': 'fetchData'
        },
        methods: {
            fetchData () {
                if ((this.$route.params.id - 0)) {
                    // We only need this if role id is set in params
                    axios.get('/api/v1/roles/' + this.$route.params.id)
                        .then(({data}) => this.role = data.data)
                        .catch(({response}) => console.log(response));
                }

                axios.get('/api/v1/permissions')
                    .then(({data}) => {
                        let permissions = [];
                        
                        for (var i = 0; i < data.data.length; i++) {
                            data.data[i].checked = false;
                            if ((this.$route.params.id - 0) && this.role.permissions) {
                                for (var x = 0; x < this.role.permissions.length; x++) {
                                    if (data.data[i].id == this.role.permissions[x].id) {
                                        data.data[i].checked = true;
                                    }
                                }
                            }
                            permissions.push(data.data[i]);
                        }
                        
                        this.permissions = permissions;
                    })
                    .catch(({response}) => console.log(response));
            }, 
            onSubmit() {
                this.saved = false;
                this.role.permissions = [];
                
                this.role.permissions = this.permissions.filter(function (item) {
                    let obj = JSON.parse(JSON.stringify(item));
                    return obj.checked != false;
                });

                if (this.action == 'create') {
                    axios.post('/api/v1/roles', this.role)
                        .then(({data}) => this.setSuccessMessage())
                        .catch(({response}) => this.setErrors(response));
                } else if (this.action == 'update') {
                    axios.put('/api/v1/roles/' + this.role.id, this.role)
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
                    this.role = {guard: 'api'};
                    for (let permission in this.permission) {
                        this.permissions[permission].checked = false;
                    }
                } 
                this.errors = [];
            },
            getLastPartOfString(string) {
                return string.split(' ').pop();
            },
            capitalize(string) {
                return string && string[0].toUpperCase() + string.slice(1);
            }
        },    
    }
</script>