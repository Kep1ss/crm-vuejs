<template>
  <div>
    <ul class="sidebar-menu">

      <!-- DASHBOARD -->
      <li class="nav-item dropdown"
        v-if="menu_dashboard">
        <nuxt-link to="/" 
          class="nav-link">
          <i class="fas fa-columns"></i> 
          <span>
            Dashboard
          </span>
        </nuxt-link>
      </li>
      <!-- END DASHBOARD -->

      <!-- MASTER DATA -->
      <li class="menu-header"
        v-if="menu_master_data">
        MASTER DATA
      </li>

      <li class="nav-item dropdown">
        <nuxt-link class="nav-link" to="/master/account"
          v-if="menu_account">
          <i class="fas fa-user-circle"></i>
          <span>Data Akun</span>
        </nuxt-link>   
          
        <nuxt-link class="nav-link" to="/master/province"
          v-if="menu_province">
          <i class="fas fa-map"></i>
          <span>Data Provinsi</span>
        </nuxt-link>      

        <nuxt-link class="nav-link" to="/master/city"
          v-if="menu_city">
          <i class="fas fa-route"></i>
          <span>Data Kaputen/Kota</span>
        </nuxt-link>      

        <nuxt-link class="nav-link" to="/master/district"
         v-if="menu_district">
          <i class="fas fa-location-arrow"></i>
          <span>Data Kecamatan</span>
        </nuxt-link>      

        <nuxt-link class="nav-link" to="/master/school"
          v-if="menu_school">
          <i class="fas fa-school"></i>
          <span>Data Sekolah</span>
        </nuxt-link>      

        <nuxt-link class="nav-link" to="/master/customer-sales"
          v-if="menu_customer_sales">
          <i class="fas fa-money-bill-wave-alt"></i>
          <span>Data Pelanggan</span>
        </nuxt-link>
      </li>
      <!-- END MASTER DATA -->

      <!-- ACIVITY -->
      <li class="menu-header"
        v-if="menu_activity">
        Aktifitas
      </li>

      <li class="nav-item dropdown">
        <nuxt-link class="nav-link" to="/setting/user"
          v-if="menu_set_area_sales">
          <i class="fas fa-chart-area"></i>
          <span> Set Area Sales </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/user"
          v-if="menu_set_target_customer">
          <i class="fas fa-chart-pie"></i>
          <span> Set Target Pelanggan </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/user"
          v-if="menu_set_target_eksemplar">
          <i class="far fa-chart-bar"></i>
          <span> Set Target Eksemplar</span>
        </nuxt-link>  
        <nuxt-link class="nav-link" to="/setting/user"
          v-if="menu_set_target_telemaraketing">
          <i class="fas fa-chart-line"></i>
          <span> Set Target Tele </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/user"
          v-if="menu_input_visit">
          <i class="fas fa-keyboard"></i>
          <span> Input Visit</span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/user"
          v-if="menu_input_activity_tele_marketing">
          <i class="far fa-keyboard"></i>
          <!-- Input Aktivitas tele_marketing -->
          <span> Input Aktivitas Tele</span>
        </nuxt-link>
      </li> 
      <!-- ACIVITY -->

      <!-- REPORT -->
      <li class="menu-header"
        v-if="menu_report">
        Analisa Data
      </li>

      <li class="nav-item dropdown">
        <nuxt-link class="nav-link" 
          to="/setting/user"
          v-if="menu_activity_history">
          <i class="fas fa-list-alt"></i>
          <span> Riwayat Aktifitas </span>
        </nuxt-link>
        <nuxt-link class="nav-link" 
          to="/setting/user"
          v-if="menu_yet_visted">
          <i class="fas fa-list"></i>
          <span> Belum Dikunjungi </span>
        </nuxt-link>      
      </li>
      <!-- REPORT -->

      <!-- PENGATURAN -->
      <li class="menu-header">
        PENGATURAN
      </li>

      <li class="nav-item dropdown">
        <nuxt-link class="nav-link" to="/setting/user"
          v-if="$auth.user.role === roles.superadmin">
          <i class="fas fa-users-cog"></i>
          <span> Akun </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting"
          v-if="$auth.user.role === roles.superadmin">
          <i class="fas fa-cogs"></i>
          <span> Aplikasi</span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/activity"
          v-if="$auth.user.role === roles.superadmin">
          <i class="fas fa-sun"></i>
          <span> Aktifitas </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/change-password">
          <i class="fas fa-key"></i>
          <span> Ganti Password </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/download-catalog" 
          v-if="$auth.user.role === roles.superadmin">
          <i class="fas fa-download"></i>
          <span> Download Catalog</span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/announcement"
          v-if="menu_announcement">
          <i class="fas fa-scroll"></i>
          <span> Pengumuman </span>
        </nuxt-link>      
      </li>
      <!-- END PENGATURAN -->
    </ul>
  </div>
</template>

<script>
import NuxtLogo from '../NuxtLogo.vue';
export default {
  computed: {
    roles(){
      return this.$store.state.setting.roles
    },

    /* MODULE DASHBOARD */
      menu_dashboard(){
        return this.roles.superadmin !== this.$auth.user.role 
      },
    /* MODULE DASHBOARD */

    /* MODULE SETTING */
      menu_announcement(){      
        return [
          this.roles.manager_nasional,
          this.roles.manager_area,
          this.roles.admin_nasional,
          this.roles.admin_area,
        ].includes(this.$auth.user.role)
      },
    /* MODULE SETTING */

    /* MODULE MASTER DATA */
      menu_account(){
        return [          
          this.roles.manager_nasional,
          this.roles.manager_area,
          this.roles.kaper,
          this.roles.spa,
          this.roles.kotele
        ].includes(this.$auth.user.role)
      },

      menu_province(){      
        return [          
          this.roles.manager_area    
        ].includes(this.$auth.user.role)
      },

      menu_city(){    
        return [    
          this.roles.manager_area,            
        ].includes(this.$auth.user.role)
      },

      menu_district(){        
        return [    
          this.roles.manager_area
        ].includes(this.$auth.user.role)
      },

      menu_school(){
        return ![
          this.roles.superadmin
        ].includes(this.$auth.user.role);
      },

      menu_customer_sales(){
        return false;        
        // return [
        //   roles.sales,
        //   roles.superadmin
        // ].includes(this.$auth.user.role);
      },

      menu_master_data(){
        return this.menu_account || 
          this.menu_province || 
          this.menu_city || 
          this.menu_district || 
          this.menu_school || 
          this.menu_customer_sales
      },
    /* MODULE MASTER DATA */

    /* MODULE ACTIVITY */
      menu_set_area_sales(){    
        return [    
          this.roles.spv,        
        ].includes(this.$auth.user.role)
      },

      menu_set_target_customer(){    
        return [    
          this.roles.spv,        
        ].includes(this.$auth.user.role)
      },

      menu_set_target_eksemplar(){    
        return ![                
          this.roles.sales,
          this.roles.kotele,
          this.roles.tele_marketing,
          this.roles.superadmin
        ].includes(this.$auth.user.role)
      },

      menu_set_target_telemaraketing(){    
        return [    
          this.roles.kotele,    
        ].includes(this.$auth.user.role)
      },

      menu_input_visit(){    
        return ![            
          this.roles.manager_nasional,
          this.roles.admin_nasional,
          this.roles.kotele,
          this.roles.tele_marketing,          
          this.roles.superadmin
        ].includes(this.$auth.user.role)
      },

      menu_input_activity_tele_marketing(){    
        return [    
          this.roles.tele_marketing,      
        ].includes(this.$auth.user.role)
      },

      menu_activity(){
        return this.menu_set_area_sales || 
          this.menu_set_target_customer || 
          this.menu_set_target_eksemplar || 
          this.menu_set_target_telemaraketing || 
          this.menu_input_visit || 
          this.menu_input_activity_tele_marketing
      },
    /* MODULE ACTIVITY */

    /* MODULE REPORT */
      menu_activity_history(){
        return this.roles.superadmin !== this.$auth.user.role 
      },

      menu_yet_visted(){
        return this.roles.superadmin !== this.$auth.user.role 
      },

      menu_report(){
        return this.menu_activity_history || 
          this.menu_yet_visted 
      },
    /* MODULE REPORT */
  }
}
</script>

<style scoped>
.sidebar-menu > .nav-item > a > span {
  font-size: 12px;
}
</style>
