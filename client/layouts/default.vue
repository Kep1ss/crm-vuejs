<template>
  <div id="app">

    <div class="main-wrapper main-wrapper-1">
      <app-navbar></app-navbar>
      <div class="main-sidebar">
        <app-sidebar></app-sidebar>
      </div>
      <div class="main-content">
        <app-section></app-section>
        <Nuxt/>
      </div>
      <vue-confirm-dialog></vue-confirm-dialog>
    </div>

    <portal-target name="modal"></portal-target>
    <portal-target name="modal-filter"></portal-target>
    <portal-target name="modal-bandwidth"></portal-target>
    <portal-target name="child-modal"></portal-target>
    <portal-target name="pdf"></portal-target>
    <portal-target name="modal-detail"></portal-target>

    <button @click="topScroll"
      id="topBtn" title="Go to top">
      <i class="fas fa-caret-up"></i>
    </button>

  </div>
</template>

<script>
import AppSidebar from "@/components/Sidebar.vue";
import AppNavbar from "@/components/Navbar.vue";
import AppSection from "@/components/Section.vue";
import Profil from '../pages/profil.vue';

export default {
  components: {
    AppNavbar,
    AppSidebar,
    AppSection,
    Profil,
  },

  middleware : ["isLogin"],

  created(){
    let myScript = [
      "/js/stisla.js",
      "/js/scripts.js",
    ];

    myScript.forEach((item,index) => {
      let script = document.createElement('script')
      script.setAttribute('src', item)
      document.body.appendChild(script)
    });
  },

  mounted(){
    window.onscroll = () => {
      let btn = document.getElementById("topBtn");

      if(btn){
        btn.style.display = (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) ? "block" : "none";
      }
    };
  },

  methods : {
    topScroll(){
      window.scroll({
        top: 0,
        behavior: 'smooth'
      });
    }
  },

  // watch : {
  //   '$route'(newValue){
  //     if(this.$nuxt.isOffline){
  //       this.$toaster.warning("Anda sedang offline");
  //     }
  //   }
  // }
};
</script>

<style>
.page-item{
  cursor:pointer
}

/* BTN BOTTOM TO TOP STYLE */
#topBtn {
  display: none;
  position: fixed;
  bottom: 20px;
  right: 30px;
  z-index: 99;
  font-size: 18px;
  border: none;
  outline: none;
  background-color:#3abaf4 !important;
  color: white;
  cursor: pointer;
  padding: 10px;
  padding-left:15px;
  padding-right:15px;
  border-radius: 4px;
  scroll-behavior: smooth;
}

/* LIGHT GRAY GLOBAL STYLE */
.light-gray{
  color:lightgray
}

/* CURSOR POINTER GLOBAL STYLE */
.cursor-pointer{
  cursor:pointer
}

/* V-SELECT GLOBAL STYLE */
.vs__selected-options{
  padding: 5px !important;
  background: #fdfdff !important;
}

/* SCROLL BAR GLOBAL STYLE */
::-webkit-scrollbar {
  width: 5px;
  height: 5px;
}

::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey;
  border-radius: 2px;
}

::-webkit-scrollbar-thumb {
  background: #3abaf4;
  border-radius: 10px;
}

tbody > tr > td > .btn-danger,
tbody > tr > td > .btn-success,
tbody > tr > td > .btn-primary,
tbody > tr > td > .btn-warning{
  line-height: 0px !important;
  padding-left: .5rem !important;
  padding-right: .5rem !important;
}

table > tbody > tr > td {
  height: 20px !important;
}
td > .btn-danger > .fa-trash,
td > .btn-success > .fa-pen,
td > .btn-primary > .fa-info-circle,
td > .btn-success > .fa-edit,
td > .btn-warning > .fa-user{
  font-size: 10px !important;
}

.vc-btn.left {
  border-radius: 0;
  color: rgb(212, 4, 4);
  border-right: 1px solid #e0e0e0;
  outline: none !important;
}
.vc-btn {
  border-radius: 0;
  border-right: 1px solid #e0e0e0;
  outline: none !important;
}
.vc-container {
  background: white;
  border-radius: 1rem;
  position: absolute;
  width: 286px;
  height: auto;
  top : 170px;
  display: grid;
  grid-template-rows: 1fr auto;
  box-shadow: rgba(0, 0, 0, 0.29) 0px 3px 8px 0px;
}
</style>
