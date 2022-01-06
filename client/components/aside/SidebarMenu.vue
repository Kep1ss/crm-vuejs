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
        <nuxt-link class="nav-link" to="/master/school">
          <i class="fas fa-user-circle"></i>
          <span>Data Admin Nasional</span>
        </nuxt-link>   
          
        <nuxt-link class="nav-link" to="/master/school">
          <i class="fas fa-user"></i>
          <span>Data Manager Area</span>
        </nuxt-link>   
          
        <nuxt-link class="nav-link" to="/master/school">
          <i class="fas fa-user-circle"></i>
          <span>Data Admin Area</span>
        </nuxt-link>  
        
        <nuxt-link class="nav-link" to="/master/school">
          <i class="fas fa-user-friends"></i>      
          <span>Data Kaper</span>
        </nuxt-link>  

        <nuxt-link class="nav-link" to="/master/school">
          <i class="fas fa-user-circle"></i>      
          <span>Data Admin Kaper</span>
        </nuxt-link>  

        <nuxt-link class="nav-link" to="/master/school">
          <i class="fas fa-user-tie"></i>
          <span>Data Spv</span>
        </nuxt-link>  

        <nuxt-link class="nav-link" to="/master/school">
          <i class="fas fa-users"></i>
          <span>Data Sales</span>
        </nuxt-link>  
        
        <nuxt-link class="nav-link" to="/master/school">
          <i class="fas fa-phone"></i>
          <span>Data Kotele</span>
        </nuxt-link>  

        <nuxt-link class="nav-link" to="/master/school">
          <i class="far fa-id-card"></i>          
          <span>Data Telemarketing</span>
        </nuxt-link>  

        <nuxt-link class="nav-link" to="/master/school">
          <i class="fas fa-map"></i>
          <span>Data Provinsi</span>
        </nuxt-link>      

        <nuxt-link class="nav-link" to="/master/school">
          <i class="fas fa-route"></i>
          <span>Data Kaputen/Kota</span>
        </nuxt-link>      

        <nuxt-link class="nav-link" to="/master/school">
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
        <nuxt-link class="nav-link" to="/setting/user">
          <i class="fas fa-chart-area"></i>
          <span> Set Area Sales </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/user">
          <i class="fas fa-chart-pie"></i>
          <span> Set Target Pelanggan </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/user">
          <i class="far fa-chart-bar"></i>
          <span> Set Target Eksemplar</span>
        </nuxt-link>  
        <nuxt-link class="nav-link" to="/setting/user">
          <i class="fas fa-chart-line"></i>
          <span> Set Target Telemarketing </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/user">
          <i class="fas fa-keyboard"></i>
          <span> Input Visit</span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/user">
          <i class="far fa-keyboard"></i>
          <!-- Input Aktivitas Telemarketing -->
          <span> Input Aktivitas </span>
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
          v-if="$auth.user.role === 0">
          <i class="fas fa-users-cog"></i>
          <span> Akun </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting"
          v-if="$auth.user.role === 0">
          <i class="fas fa-cogs"></i>
          <span> Aplikasi</span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/activity"
          v-if="$auth.user.role === 0">
          <i class="fas fa-users-cog"></i>
          <span> Aktifitas </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/change-password">
          <i class="fas fa-users-cog"></i>
          <span> Ganti Password </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/announcement">
          <i class="fas fa-scroll"></i>
          <span> Pengumuman </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/download-catalog" 
          v-if="$auth.user.role === 0">
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

}
</script>

<style scoped>
.sidebar-menu > .nav-item > a > span {
  font-size: 12px;
}
</style>
