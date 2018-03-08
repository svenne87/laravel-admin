<template>
    <Slideout menu="#menu" panel="#admin-menu-panel" duration="300" :toggleSelectors="['.toggle-button']" @on-open="open">
        <nav id="menu">
            <ul>
                <li v-on:click="toggleMenu"><router-link :to="{ name: 'home' }"><i class="icon-home mr-1"></i> {{ $t('general.home') }}</router-link></li>
                <li v-on:click="toggleMenu"><router-link :to="{ name: 'users' }"><i class="icon-people mr-1"></i> {{ $t('general.users') }}</router-link></li>
                <li v-on:click="toggleMenu"><router-link :to="{ name: 'roles' }"><i class="icon-organization mr-1"></i> {{ $t('general.roles') }}</router-link></li>
                <li v-on:click="toggleMenu"><router-link :to="{ name: 'pages' }"><i class="icon-notebook mr-1"></i> {{ $t('general.pages') }}</router-link></li>
            </ul>
        </nav>
        <button class="slideout-trigger toggle-button">☰</button>
    </Slideout>
</template>
<script>
    import Slideout from 'vue-slideout'

    export default {
        name: 'app',
        components: {
            Slideout
        },
        methods: {
            open: function () {
                // console.log('slideoutOpen')
            },
            toggleMenu: function() {
                this.$children[0].slideout.toggle()
            },
        }
    }
</script>
<style>
    .slideout-menu {
        position: fixed;
        top: 3.1rem;
        bottom: 0;
        width: 256px;
        height: 100vh;
        overflow-y: scroll;
        -webkit-overflow-scrolling: touch;
        z-index: 0;
        display: none;
        background-color: #1D1F20;
        color: white;
    }
    .slideout-menu-left {
        left: 0;
    }
    .slideout-menu-right {
        right: 0;
    }
    .slideout-open,
    .slideout-open body,
    .slideout-open .slideout-panel {
        overflow: hidden;
    }
    .slideout-open .slideout-menu {
        display: block;
    } 
    .slideout-menu ul {
        list-style: none;
        padding: 1rem 0;
    }
    .slideout-menu ul li {
        padding: .5rem 1rem;
    }
    .slideout-menu ul li:hover {
        background-color: #333536;
    }
    .slideout-menu ul li a {
        color: #FFF;
        padding: .5rem 1.5rem;
    }
    .slideout-menu ul li a:hover {
        text-decoration: none;
    }
    .slideout-trigger {
        color: #FFF;
        border: none;
        background-color: transparent;
    }
    .slideout-trigger:hover {
        cursor: pointer;
    }
    .slideout-trigger:focus {
        outline:0;
    }
    @media (min-width: 768px){
        .slideout-menu {
            top: 4.1rem;
        }
    }
</style>