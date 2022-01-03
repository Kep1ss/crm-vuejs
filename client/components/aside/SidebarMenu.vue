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

        <nuxt-link class="nav-link" to="/master/division">
          <i class="fas fa-address-book"></i>
          <span>Data Divisi</span>
        </nuxt-link>

        <nuxt-link class="nav-link" to="/master/position">
          <i class="fas fa-map"></i>
          <span>Data Jabatan</span>
        </nuxt-link>

        <nuxt-link class="nav-link" to="/master/salary-parameter">
          <i class="fas fa-handshake"></i>
          <span>Data Parameter Gaji</span>
        </nuxt-link>

        <nuxt-link class="nav-link" to="/master/employe">
          <i class="fas fa-map"></i>
          <span>Data Karyawan</span>
        </nuxt-link>
      </li>

      <!-- KONFIGURASI GAJI -->
      <li class="menu-header">Konfigurasi Gaji</li>
      <li class="nav-item dropdown">
        <li class="nav-item dropdown">
         <nuxt-link class="nav-link" to="/salary-configuration/overtime-category">
          <i class="fas fa-bell"></i>
          <span>Kategori Lembur</span>
        </nuxt-link>
        <li class="nav-item dropdown">
         <nuxt-link class="nav-link" to="/salary-configuration/overtime-parameters">
          <i class="fas fa-bell"></i>
          <span>Parameter Lembur</span>
        </nuxt-link>
        <li class="nav-item dropdown">
         <nuxt-link class="nav-link" to="/salary-configuration/formula-overtime">
          <i class="fas fa-bell"></i>
          <span>Formula Lembur</span>
        </nuxt-link>
           <nuxt-link class="nav-link" to="/salary-configuration/salary-parameters">
          <i class="fas fa-book"></i>
          <span>Parameter Gaji</span>
        </nuxt-link>
      </li>
      <!-- KONFIGURASI GAJI -->

      <!-- END MASTER DATA -->

      <!-- BILLING RESELLER -->
      <li class="menu-header">Absensi</li>
      <li class="nav-item dropdown">
         <nuxt-link class="nav-link" to="/bluk/resellers">
          <i class="fas fa-bell"></i>
          <span>Mesin Abensi</span>
        </nuxt-link>

        <nuxt-link class="nav-link" to="/payment/resellers">
          <i class="fas fa-map"></i>
          <span>Jenis Ijin</span>
        </nuxt-link>

        <nuxt-link class="nav-link" to="/payment/resellers">
          <i class="fas fa-map"></i>
          <span>Hari Libur</span>
        </nuxt-link>

        <nuxt-link class="nav-link" to="/payment/resellers">
          <i class="fas fa-map"></i>
          <span>Ijin Karyawan</span>
        </nuxt-link>

        <nuxt-link class="nav-link" to="/billing/resellers">
          <i class="fas fa-credit-card"></i>
          <span>Absensi</span>
        </nuxt-link>

        <nuxt-link class="nav-link" to="/bluk/resellers">
          <i class="fas fa-bell"></i>
          <span>Upload Absensi</span>
        </nuxt-link>
      </li>

      <!-- PERHITUNGAN GAJI -->
      <li class="menu-header">PERHITUNGAN GAJI</li>
      <li class="nav-item dropdown">
        <nuxt-link class="nav-link" to="/setting/change-password">
          <i class="fas fa-users-cog"></i>
          <span>Hitung Gaji</span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/change-password">
          <i class="fas fa-users-cog"></i>
          <span>Cetak Gaji</span>
        </nuxt-link>
      </li>
      <!-- PERHITUNGAN GAJI -->

      <!-- LAPORAN -->
      <li class="menu-header">LAPORAN</li>
      <li class="nav-item dropdown">
        <nuxt-link class="nav-link" to="/setting/change-password">
          <i class="fas fa-users-cog"></i>
          <span>Data Karyawan</span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/change-password">
          <i class="fas fa-users-cog"></i>
          <span>Data Absensi</span>
        </nuxt-link>
        <nuxt-link class="nav-link" to="/setting/change-password">
          <i class="fas fa-users-cog"></i>
          <span>Perhitungan Gaji</span>
        </nuxt-link>
      </li>
      <!-- LAPORAN -->

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
