const webpack = require("webpack");
const fs = require('fs') ;

require('dotenv').config();

export default {
  // Disable server-side rendering: https://go.nuxtjs.dev/ssr-mode
  ssr: false,

  server: {
    host: process.env.HOST,
    port : process.env.PORT
  },

  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'SiPayRoll',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [
      // {hid: 'icon',rel: 'icon',type: 'image/x-icon',href: '/favicon.ico' },
      {rel: 'icon',type: 'image/x-icon',href: (process.env.LOGO_URL || '/img/') + 'default.jpeg'}
    ],
    script: [
      { src: "/js/vendor/jquery/jquery-3.3.1.min.js" },
      { src: "/js/vendor/popper/popper.min.js" },
      { src: "/js/vendor/bootstrap/bootstrap.min.js" },
      { src: "/js/vendor/jquery-nicescroll/jquery.nicescroll.min.js" },
      { src: "/js/vendor/moments/moment.min.js" },
    ],
  },

  loading: {
    color: 'blue',
    height: '2px',
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    '~/assets/css/bootstrap/css/bootstrap.min.css',
    '~/assets/css/fontawesome/css/all.min.css',
    '~/assets/css/style.css',
    '~/assets/css/components.css',
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    "~/plugins/axios",
    "~/plugins/vee-validate",
    '~/plugins/toaster',
    '~/plugins/swal',
    '~/plugins/vue-select',
    '~/plugins/date-picker',
    '~/plugins/pagination',
    '~/plugins/global-components',
    '~/plugins/v-money',
    '~/plugins/vue-confirm-dialog',
    '~/plugins/vue-loading-overlay'
    // '~/plugins/vue-htmlpdf'
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    // https://go.nuxtjs.dev/eslint
    // '@nuxtjs/eslint-module'
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    '@nuxtjs/pwa',
    // https://go.nuxtjs.dev/axios
    ['@nuxtjs/dotenv',{ systemvars: false }],
    'portal-vue/nuxt',
    '@nuxtjs/axios',
    '@nuxtjs/auth',
    ['cookie-universal-nuxt', { alias: 'cookiz' }],
  ],

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
  },

  pwa: {
    meta: {
      title: 'SiPayRoll',
      author: 'Anaba Software',
    },
    manifest: {
      name: 'SiPayRoll Anaba Software',
      short_name: '',
      lang: 'en',
    },
    icon : false
  },

  // Axios module configuration: https://go.nuxtjs.dev/config-axios
  axios: {
    baseURL: process.env.API_URL
  },

  auth: {
    watchLoggedIn: true,
    redirect : false,
    strategies: {
      local: {
        endpoints: {
          login: {
            url: 'login',
            method: 'post',
            propertyName: 'access_token'
          },
          logout: {
            url: 'logout',
            method: 'post'
          },
          user: {
            url: 'me',
            method: 'get',
            propertyName: false
          }
        },
        tokenRequired: true,
        tokenType: 'Bearer'
      }
    }
  }
}
