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
          <i class="fas fa-address-book"></i>
          <span>Data Sekolah</span>
        </nuxt-link>      
      </li>
      <!-- END MASTER DATA -->


      <!-- PENGATURAN -->
      <li class="menu-header">PENGATURAN</li>
      <li class="nav-item dropdown">
        <nuxt-link class="nav-link" to="/setting/change-password">
          <i class="fas fa-users-cog"></i>
          <span> Akun </span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting">
          <i class="fas fa-cogs"></i>
          <span> Aplikasi</span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/change-password">
          <i class="fas fa-users-cog"></i>
          <span> Aktifitas </span>
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
