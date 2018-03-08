<template>
    <div>
        <b-breadcrumb :items="items"/>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="alert alert-success" v-if="saved">
                    {{ $t('form.user_saved') }}
                </div>
            </div>
        </div>
        <div class="well well-sm" id="userProfile-form">
            <form class="form-horizontal" method="post" @submit.prevent="onSubmit">
                <fieldset class="col-md-8 offset-md-2 text-left">
                    <div class="form-group">
                        <label class="control-label" for="name">{{ $t('general.name') }}</label>
                        <div :class="{'has-error': errors.name}">
                            <div class="input-group mb-0">
                                <span class="input-group-addon"><i class="icon-user" aria-hidden="true"></i></span>
                                <input id="name"
                                    v-model="user.name"
                                    type="text"
                                    :placeholder="$t('general.name')"
                                    class="form-control">
                            </div>
                            <span v-if="errors.name" class="help-block text-danger">{{ errors.name[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="email">{{ $t('general.email_address') }}</label>
                        <div :class="{'has-error': errors.email}">
                            <div class="input-group mb-0">
                                <span class="input-group-addon"><i class="icon-envelope" aria-hidden="true"></i></span>
                                <input id="email"
                                    v-model="user.email"
                                    type="email"
                                    :placeholder="$t('general.email_address')"
                                    class="form-control">
                            </div>
                            <span v-if="errors.email" class="help-block text-danger">{{ errors.email[0] }}</span>
                        </div>
                    </div>                    
                    <div v-if="action == 'update'" class="form-group">
                        <label class="control-label" for="new_password">{{ $t('auth.new_password') }}</label>
                        <div :class="{'has-error': errors.new_password}">
                            <div class="input-group mb-0">
                                <span class="input-group-addon"><i class="icon-lock" aria-hidden="true"></i></span>
                                <input id="new_password"
                                    v-model="user.new_password"
                                    type="password"
                                    class="form-control">
                            </div>
                            <span v-if="errors.new_password" class="help-block text-danger">{{ errors.new_password[0] }}</span>
                        </div>
                    </div>
                    <div v-if="action == 'create'" class="form-group">
                        <label class="control-label" for="password">{{ $t('auth.password') }}</label>
                        <div :class="{'has-error': errors.password}">
                            <div class="input-group mb-0">
                                <span class="input-group-addon"><i class="icon-lock" aria-hidden="true"></i></span>
                                <input id="password"
                                    v-model="user.password"
                                    type="password"
                                    class="form-control">
                            </div>
                            <span v-if="errors.password" class="help-block text-danger">{{ errors.password[0] }}</span>
                        </div>
                    </div>
                    <div v-if="action == 'update'" class="form-group">
                        <label class="control-label" for="new_password_confirmation">{{ $t('auth.confirm_password') }}</label>
                        <div :class="{'has-error': errors.new_password_confirmation}">
                            <div class="input-group mb-0">
                                <span class="input-group-addon"><i class="icon-lock" aria-hidden="true"></i></span>
                                <input id="new_password_confirmation"
                                    v-model="user.new_password_confirmation"
                                    type="password"
                                    class="form-control">
                            </div>
                            <span v-if="errors.new_password_confirmation" class="help-block text-danger">{{ errors.new_password_confirmation[0] }}</span>
                        </div>
                    </div>
                    <div v-if="action == 'create'" class="form-group">
                        <label class="control-label" for="password_confirmation">{{ $t('auth.confirm_password') }}</label>
                        <div :class="{'has-error': errors.password_confirmation}">
                            <div class="input-group mb-0">
                                <span class="input-group-addon"><i class="icon-lock" aria-hidden="true"></i></span>
                                <input id="password_confirmation"
                                    v-model="user.password_confirmation"
                                    type="password"
                                    class="form-control">
                            </div>
                            <span v-if="errors.password_confirmation" class="help-block text-danger">{{ errors.password_confirmation[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <p class="mb-1">{{ $t('general.roles') }}</p>
                        <b-form-checkbox-group v-model="role.checked" v-for="(role, index) in roles" :key="index">
                            <b-form-checkbox :v-value="role.id">{{ role.display }} ({{ role.guard }})</b-form-checkbox>
                        </b-form-checkbox-group>
                    </div>
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
                user : {},
                roles: [],
                action : '',
                items: [{
                    text: this.$t('general.home'),
                    to: { name: 'home' },
                }, {
                    text: this.$t('general.users'),
                    to: { name: 'users' },
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
                    // We only need this if user id is set in params
                    axios.get('/api/v1/users/' + this.$route.params.id)
                        .then(({data}) => this.user = data.data)
                        .catch(({response}) => console.log(response));
                }

                axios.get('/api/v1/roles')
                    .then(({data}) => {
                        let roles = [];
                        
                        for (var i = 0; i < data.data.length; i++) {
                            data.data[i].checked = false;

                            if ((this.$route.params.id - 0) && this.user.roles) {
                                for (var x = 0; x < this.user.roles.length; x++) {
                                    if (data.data[i].id == this.user.roles[x].id) {
                                        data.data[i].checked = true;
                                    }
                                }
                            }                            

                            roles.push(data.data[i]);
                        }
                        this.roles = roles;
                    })
                    .catch(({response}) => console.log(response));
            }, 
            onSubmit() {
                this.saved = false;
                
                this.user.roles = this.roles.filter(function (item) {
                    return item.checked;
                });

                if (this.action == 'create') {
                    axios.post('/api/v1/users', this.user)
                        .then(({data}) => this.setSuccessMessage())
                        .catch(({response}) => this.setErrors(response));
                } else if (this.action == 'update') {
                    axios.put('/api/v1/users/' + this.user.id, this.user)
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
                    this.user = {};
                    for (let role in this.roles) {
                        this.roles[role].checked = false;
                    }
                } else if (this.action == 'update') {
                    Vue.delete(this.user, 'new_password');
                    Vue.delete(this.user, 'new_password_confirmation');
                }
                this.errors = [];
            }
        },    
    }
</script>