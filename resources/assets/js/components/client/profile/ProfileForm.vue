<template>
    <div>
        <div class="alert alert-success" v-if="saved">
            {{ $t('form.profile_saved') }}
        </div>

        <div class="well well-sm" id="userProfile-form">
            <form class="form-horizontal" method="post" @submit.prevent="onSubmit">
                <fieldset class="col-md-8">
                    <div class="form-group">
                        <label class="control-label" for="name">{{ $t('general.name') }}</label>
                        <div :class="{'has-error': errors.name}">
                            <div class="input-group mb-0">
                                <span class="input-group-addon"><i class="icon-user" aria-hidden="true"></i></span>
                                <input id="name"
                                    v-model="userProfile.name"
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
                                    v-model="userProfile.email"
                                    type="email"
                                    :placeholder="$t('general.email_address')"
                                    class="form-control">
                            </div>
                            <span v-if="errors.email" class="help-block text-danger">{{ errors.email[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class=" control-label" for="current_password">{{ $t('auth.password') }}</label>
                        <div :class="{'has-error': errors.current_password}">
                            <div class="input-group mb-0">
                                <span class="input-group-addon"><i class="icon-lock" aria-hidden="true"></i></span>
                                <input id="current_password"
                                    v-model="userProfile.current_password"
                                    type="password"
                                    class="form-control">
                            </div>
                            <span v-if="errors.current_password" class="help-block text-danger">{{ errors.current_password[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="new_password">{{ $t('auth.new_password') }}</label>
                        <div :class="{'has-error': errors.new_password}">
                            <div class="input-group mb-0">
                                <span class="input-group-addon"><i class="icon-lock" aria-hidden="true"></i></span>
                                <input id="new_password"
                                    v-model="userProfile.new_password"
                                    type="password"
                                    class="form-control">
                            </div>
                            <span v-if="errors.new_password" class="help-block text-danger">{{ errors.new_password[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="new_password_confirmation">{{ $t('auth.confirm_password') }}</label>
                        <div :class="{'has-error': errors.new_password_confirmation}">
                            <div class="input-group mb-0">
                                <span class="input-group-addon"><i class="icon-lock" aria-hidden="true"></i></span>
                                <input id="new_password_confirmation"
                                    v-model="userProfile.new_password_confirmation"
                                    type="password"
                                    class="form-control">
                            </div>
                            <span v-if="errors.new_password_confirmation" class="help-block text-danger">{{ errors.new_password_confirmation[0] }}</span>
                        </div>
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
        props: ['user'],
        data() {
            return {
                errors: [],
                saved: false,
                userProfile : this.user,
            };
        },

        methods: {
            onSubmit() {
                this.saved = false;
                
                axios.put('/api/v1/users/' + this.userProfile.id, this.userProfile)
                    .then(({data}) => this.setSuccessMessage())
                    .catch(({response}) => this.setErrors(response));
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
                this.errors = [];
            }
        }
    }
</script>