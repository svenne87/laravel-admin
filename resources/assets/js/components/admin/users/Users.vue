<template>
    <div class="ui container table-responsive">
        <b-breadcrumb :items="items"/>
        <div class="alert" v-bind:class="alertClass" v-if="operationComplete">
            {{ feedback }}
        </div>
        <b-modal id="modal-center" class="danger" v-model="show" centered title="">
            <div slot="modal-header" class="w-100"></div>
            <b-container fluid>
                <b-row class="mb-1 text-center">
                    <b-col>{{ $t('general.confirm_text') }}</b-col>
                </b-row>
            </b-container>
            <div slot="modal-footer" class="w-100">
                <b-btn size="sm" class="float-right" variant="danger" @click="deleteRow(this.currentRowData)">{{ $t('general.confirm') }}</b-btn>
                <b-btn size="sm" class="float-right mr-2" variant="primary" @click="show=false">{{ $t('general.close') }}</b-btn>
         </div>
        </b-modal>
        <button class="btn btn-primary float-left" @click="createUser()">{{ $t('general.create_user') }}</button>
        <filter-bar></filter-bar>
        <div class="clearfix"></div>
        <vuetable ref="vuetable"
            api-url="/api/v1/users"
            :http-fetch="fetchData"
            :fields="fields"
            :css="css"
            :per-page="20"
            :sort-order="sortOrder"
            :append-params="moreParams"
            pagination-path="meta"
            :http-options="httpOptions"
            @vuetable:pagination-data="onPaginationData"
        >
            <template slot="avatar" slot-scope="props">
                <div class="img-circle img-circle--size" v-bind:style="{backgroundImage: `url(/storage/uploads/avatars/${props.rowData.avatar})`, backgroundSize: 'cover', backgroundPosition: 'center', width: '35px', height: '35px', borderRadius: '17.5px', margin: '0 auto'}"></div>
            </template>
            <template slot="actions" slot-scope="props">
                <div class="table-button-container">
                    <button class="btn btn-sm btn-primary" @click="editRow(props.rowData)"><i class="fa fa-edit"></i> {{ $t('general.edit') }}</button>&nbsp;&nbsp;
                    <button class="btn btn-sm btn-danger" @click="show=true; this.currentRowData = props.rowData;"><i class="fa fa-trash"></i> {{ $t('general.delete') }}</button>&nbsp;&nbsp;
                </div>
            </template>
        </vuetable>
        <div class="vuetable-pagination ui basic segment grid">
            <vuetable-pagination-info ref="paginationInfo"
                :css="css.pagination"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="pagination"
                :css="css.pagination"
                @vuetable-pagination:change-page="onChangePage"
            ></vuetable-pagination>
        </div>
    </div>
</template>
<script>
    import Vuetable from 'vuetable-2/src/components/Vuetable'
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
    import BootstrapStyle from '../../assets/bootstrap-css.js'
    import FilterBar from '../../shared/FilterBar'
    import VueEvents from 'vue-events'

    Vue.component("vuetable", Vuetable)
    Vue.component("vuetable-pagination", VuetablePagination)
    Vue.component("vuetable-pagination-info", VuetablePaginationInfo)
    Vue.use(VueEvents)
    Vue.use(Vuetable)

    export default {
        components: {
            FilterBar
        },
        data () {    
            return { 
                css: BootstrapStyle,
                httpOptions: {headers: {'X-Requested-With': 'XMLHttpRequest', 'Content-Type':'application/json;charset=UTF-8'} },
                fields: [
                    '__slot:avatar', 
                    {
                        name: 'name',
                        title: this.$t('general.name')
                    }, 
                    {
                        name: 'email',
                        title: this.$t('general.email_address')
                    }, 
                    {
                        name: 'joined',
                        title: this.$t('general.joined')
                    }, 
                    '__slot:actions'],
                sortOrder: [
                    {
                        field: 'email',
                        sortField: 'email',
                        direction: 'asc'
                    }
                ],
                moreParams: {},
                show: false,
                currentRowData : {},
                operationComplete: false,
                feedback: '',
                alertClass: 'alert-success',
                items: [{
                    text: this.$t('general.home'),
                    to: { name: 'home' },
                }, {
                    text: this.$t('general.users'),
                    active: true
                }]
            }
        },
        mounted () {
            this.$events.listen('filter-set', filterText => this.onFilterSet(filterText))
            this.$events.listen('filter-reset', () => this.onFilterReset())
        },
        methods: {
            fetchData(apiUrl, httpOptions) {
                return axios.get(apiUrl, httpOptions)
            },
            onPaginationData(paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
            onChangePage(page) {
                this.$refs.vuetable.changePage(page)
            },
            createUser() {
                this.$router.push({ path: `/users/create`})
            },
            editRow(rowData){
                this.$router.push({ path: `/users/${rowData.id}`})
            },
            deleteRow(rowData){
                this.show = false;
                this.currentRowData = {};
                this.operationComplete = false;
                this.feedback = '';

                axios.delete('/api/v1/users/' + rowData.id)
                    .then(({data}) => this.setSuccessMessage());
            },
            setErrors() {
                this.operationComplete = true;
                this.feedback = '';
                this.alertClass = 'alert-danger';
                setTimeout(function () { this.operationComplete = false }.bind(this), 3000)
            },
            setSuccessMessage() {
                this.operationComplete = true;
                this.feedback = this.$t('form.deleted');
                this.alertClass = 'alert-success';
                this.$refs.vuetable.reload();
                setTimeout(function () { this.operationComplete = false }.bind(this), 3000)
            },
            onFilterSet (filterText) {
                this.moreParams = {
                    'filter': filterText
                }
                Vue.nextTick( () => this.$refs.vuetable.refresh())
            },
            onFilterReset () {
                this.moreParams = {}
                this.$refs.vuetable.refresh()
                Vue.nextTick( () => this.$refs.vuetable.refresh())
            }
        },
        events: {
            'filter-set' (filterText) {
                this.moreParams = {
                    filter: filterText
                }
                Vue.nextTick( () => this.$refs.vuetable.refresh() )
            },
            'filter-reset' () {
                this.moreParams = {}
                Vue.nextTick( () => this.$refs.vuetable.refresh() )
            }
        }
    }
</script>