<template>
    <div id="app-sidepanel" :class="['app-sidepanel', mobileToggler ? 'sidepanel-visible' : '']">
        <div id="sidepanel-drop" class="sidepanel-drop"></div>
        <div class="sidepanel-inner d-flex flex-column">
            <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none" @click="sidepanelClose()">&times;</a>

            <Branding/>

            <nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
                <ul class="app-menu list-unstyled accordion" id="menu-accordion">

                    <li class="nav-item has-submenu">
                      <router-link class="nav-link submenu-toggle" to="/users" >
                            <span class="nav-icon">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-columns-gap" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 1H1v3h5V1zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H1zm14 12h-5v3h5v-3zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5zM6 8H1v7h5V8zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H1zm14-6h-5v7h5V1zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1h-5z"/>
                                </svg>
                            </span>
                            <span class="nav-link-text">Пользователи</span>
                      </router-link>
                    </li>
                    <li class="nav-item has-submenu">
                        <router-link class="nav-link submenu-toggle" :to="`/status/` + statusEnum.STATUS" >
                            <span class="nav-icon">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-columns-gap" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 1H1v3h5V1zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H1zm14 12h-5v3h5v-3zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5zM6 8H1v7h5V8zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H1zm14-6h-5v7h5V1zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1h-5z"/>
                                </svg>
                            </span>
                            <span class="nav-link-text">Статуси замовлення</span>
                        </router-link>
                    </li>
                    <li class="nav-item has-submenu">
                        <router-link class="nav-link submenu-toggle" :to="`/status/` + statusEnum.DEFECT" >
                            <span class="nav-icon">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-columns-gap" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 1H1v3h5V1zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H1zm14 12h-5v3h5v-3zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5zM6 8H1v7h5V8zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H1zm14-6h-5v7h5V1zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1h-5z"/>
                                </svg>
                            </span>
                            <span class="nav-link-text">Статуси повернень</span>
                        </router-link>
                    </li>
                    <li class="nav-item has-submenu">
                        <router-link class="nav-link submenu-toggle" to="/providers" >
                            <span class="nav-icon">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-columns-gap" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 1H1v3h5V1zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H1zm14 12h-5v3h5v-3zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5zM6 8H1v7h5V8zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H1zm14-6h-5v7h5V1zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1h-5z"/>
                                </svg>
                            </span>
                            <span class="nav-link-text">Постачальники</span>
                        </router-link>
                    </li>
                </ul><!--//app-menu-->
            </nav><!--//app-nav-->

            <FooterSidepanel/>
        </div><!--//sidepanel-inner-->
    </div><!--//app-sidepanel-->
</template>

<script lang="ts">

import {StatusEnum} from "../../../../modules/Admin/pages/Status/enum/StatusEnum";

const Branding = defineAsyncComponent(() => import('@/js/src/common/components/Header/Branding.vue'));
const FooterSidepanel = defineAsyncComponent(() => import('@/js/src/common/components/Header/Sidepanel/FooterSidepanel.vue'));

import {defineAsyncComponent, defineComponent, PropType} from "vue";

export default defineComponent({
    name: "AdminSidepanel",
    components: {
        Branding,
        FooterSidepanel
    },
    data() {
        return {
            statusEnum: StatusEnum,
        }
    },
    props: {
        mobileToggler: {
            type: Boolean,
            required: true,
        }
    },
    methods: {
        sidepanelClose(): void {
            this.$emit('sidepanelClose');
        }
    }
});

</script>

<style scoped>

</style>
