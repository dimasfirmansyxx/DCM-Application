<nav class="main-header navbar navbar-expand navbar-white navbar-light">
	<ul class="navbar-nav">
		<li class="nav-item">
		  <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
		</li>
		<li class="nav-item d-none d-sm-inline-block">
		  <span class="navbar-text"><?= $this->Clsglobal->site_info("nama_sekolah") ?></span>
		</li>
	</ul>

	<ul class="navbar-nav ml-auto">
		<li class="nav-item">
			<a class="nav-link" href="<?= base_url() ?>myprofile">
				<i class="fas fa-user"></i> Pengaturan Akun
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="<?= base_url() ?>auth/logout">
				<i class="fas fa-sign-out-alt"></i> Keluar
			</a>
		</li>
	</ul>
</nav>