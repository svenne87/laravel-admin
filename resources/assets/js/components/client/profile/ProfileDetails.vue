<template>
    <div>
        <div class="alert alert-success" v-if="updated">
            <strong>{{ $t('general.success') }}!</strong> {{ $t('form.image_saved') }}
        </div>
        <div class="alert alert-danger" v-if="error">
            <strong>{{ $t('errors.error') }}!</strong> {{ errorText }}
        </div>
        <div class="row" id="userProfile-details">
            <div class="col-md-8">
                <h2>{{ userProfile.name }}</h2>
                <p class="card-text"><strong>{{ $t('general.email_address') }}:</strong> {{ userProfile.email }}</p>
                <p class="card-text"><strong>{{ $t('general.joined') }}:</strong> {{ userProfile.joined }}</p>
            </div>
            <div class="col-md-4 text-center">
                <img class="img-avatar mb-2" v-bind:src="'/storage/uploads/avatars/' + userProfile.avatar" v-bind:alt="userProfile.email" />
                <div class="form-group">
                    <div class="clearfix"></div>
                    <b-form-file accept="image/*" v-on:change="onImageChange" ref="fileinput"></b-form-file>
                     <div class="clearfix mb-1"></div>
                    <button class="btn btn-primary btn-block" @click="uploadImage">{{ $t('general.upload') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        data() {
            return {
                userProfile : this.user,
                image: '',
                updated: false,
                error: false,
                errorText: '',
            };
        },
         methods: {
            onImageChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            uploadImage() {
                this.updated = false;
                this.error = false;
                this.errorText = '';

                axios.post('api/v1/user_avatar', {image: this.image})
                    .then(({data}) => this.uploadSuccess(data))
                    .catch(({response}) => this.uploadError(response));
            },
            uploadSuccess(data) {
                this.userProfile.avatar = data.image; 
                this.updated = true;
                this.$refs.fileinput.reset();
                document.getElementById('small-profile-image').src = "/storage/uploads/avatars/" + data.image; // TMP cause this is not a vue template
                setTimeout(function () { this.updated = false }.bind(this), 3000)
            },
            uploadError(response) {
                this.error = true;

                if (response.data) {
                    this.errorText = response.data.error;
                } 
                
                setTimeout(function () { this.error = false }.bind(this), 3000)
            }
        }
    }
</script>