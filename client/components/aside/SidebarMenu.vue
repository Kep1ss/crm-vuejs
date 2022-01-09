<template>
  <div>
    <ul class="sidebar-menu">
      <!-- DASHBOARD -->
      <li class="nav-item dropdown">
        <nuxt-link to="/" class="nav-link">
          <i class="fas fa-columns"></i> <span>Dashboard</span>
        </nuxt-link>
      </li>
      <!-- END DASHBOARD -->

      <!-- MASTER DATA -->
      <li class="menu-header">MASTER DATA</li>

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

        <nuxt-link class="nav-link" to="/master/school"
         v-if="menu_district">
          <i class="fas fa-location-arrow"></i>
          <span>Data Kecamatan</span>
        </nuxt-link>      

        <nuxt-link class="nav-link" to="/master/school">
          <i class="fas fa-school"></i>
          <span>Data Sekolah</span>
        </nuxt-link>      
      </li>
      <!-- END MASTER DATA -->

      <!-- ACIVITY -->
      <li class="menu-header">Aktifitas</li>
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
      <li class="menu-header">Analisa Data</li>
      <li class="nav-item dropdown">
        <nuxt-link class="nav-link" to="/setting/user">
          <i class="fas fa-list-alt"></i>
          <span> Riwayat Aktifitas </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/user">
          <i class="fas fa-list"></i>
          <span> Belum Dikunjungi </span>
        </nuxt-link>      
      </li>
      <!-- REPORT -->

      <!-- PENGATURAN -->
      <li class="menu-header">PENGATURAN</li>
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
          <i class="fas fa-users-cog"></i>
          <span> Aktifitas </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/change-password">
          <i class="fas fa-users-cog"></i>
          <span> Ganti Password </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/announcement"
          v-if="menu_announcement">
          <i class="fas fa-scroll"></i>
          <span> Pengumuman </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/download-catalog" 
          v-if="$auth.user.role === roles.superadmin">
          <i class="fas fa-download"></i>
          <span> Download Catalog</span>
        </nuxt-link>      
      </li>
      <!-- END PENGATURAN -->
    </ul>
  </div>
</template>

<script>
export default {
  computed: {
    roles(){
      return this.$store.state.setting.roles
    },

    /* MODULE SETTING */
      menu_announcement(){
        let roles = this.$store.state.setting.roles;

        return [
          roles.manager_nasional,
          roles.manager_area,
          roles.admin_nasional,
          roles.admin_area,

          roles.superadmin
        ].includes(this.$auth.user.role)
      },
    /* MODULE SETTING */

    /* MODULE MASTER DATA */
      menu_account(){
        let roles = this.$store.state.setting.roles;

        return ![
          roles.sales,
          roles.tele_marketing,
        ].includes(this.$auth.user.role)
      },

      menu_province(){
        let roles = this.$store.state.setting.roles;

        return [    
          roles.kotele,
          roles.manager_nasional,
          roles.admin_nasional,
          
          roles.superadmin
        ].includes(this.$auth.user.role)
      },

      menu_city(){
        let roles = this.$store.state.setting.roles;
    
        return ![    
          roles.sales,
          roles.manager_nasional,
          roles.admin_nasional,
          roles.kotele,
          roles.tele_marketing,
        ].includes(this.$auth.user.role)
      },

      menu_district(){
        let roles = this.$store.state.setting.roles;
    
        return ![    
          roles.sales,
          roles.manager_nasional,
          roles.admin_nasional,
          roles.kotele,
          roles.tele_marketing,
        ].includes(this.$auth.user.role)
      },
    /* MODULE MASTER DATA */

    /* MODULE ACTIVITY */
      menu_set_area_sales(){
        let roles = this.$store.state.setting.roles;
    
        return [    
          roles.spv,
          roles.superadmin
        ].includes(this.$auth.user.role)
      },

      menu_set_target_customer(){
        let roles = this.$store.state.setting.roles;
    
        return [    
          roles.spv,
          roles.superadmin
        ].includes(this.$auth.user.role)
      },

      menu_set_target_eksemplar(){
        let roles = this.$store.state.setting.roles;
    
        return ![                
          roles.sales,
          roles.kotele,
          roles.tele_marketing,
        ].includes(this.$auth.user.role)
      },

      menu_set_target_telemaraketing(){
        let roles = this.$store.state.setting.roles;
    
        return [    
          roles.kotele,
          roles.superadmin
        ].includes(this.$auth.user.role)
      },

      menu_input_visit(){
        let roles = this.$store.state.setting.roles;
    
        return ![            
          roles.manager_nasional,
          roles.admin_nasional,
          roles.kotele,
          roles.tele_marketing,          
        ].includes(this.$auth.user.role)
      },

      menu_input_activity_tele_marketing(){
        let roles = this.$store.state.setting.roles;
    
        return [    
          roles.tele_marketing,
          roles.superadmin
        ].includes(this.$auth.user.role)
      }
    /* MODULE ACTIVITY */
  }
}
</script>

<style scoped>
.sidebar-menu > .nav-item > a > span {
  font-size: 12px;
}
</style>
