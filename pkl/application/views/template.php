<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $_ENV['APP_NAME']; ?> | <?php echo $title; ?></title>

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <link rel="icon" href="https://mipa.ub.ac.id/wp-content/uploads/2022/12/cropped-ub-01-1-32x32.png" sizes="32x32" />
    <link rel="icon" href="https://mipa.ub.ac.id/wp-content/uploads/2022/12/cropped-ub-01-1-192x192.png" sizes="192x192" />
    <link rel="apple-touch-icon" href="https://mipa.ub.ac.id/wp-content/uploads/2022/12/cropped-ub-01-1-180x180.png" />

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="<?php echo $_ENV['APP_NAME']; ?> | <?php echo $title; ?>" />
    <meta name="author" content="Math FMIPA UB" />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="<?php echo base_url('assets/dist/bundle.css'); ?>" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print" onload="this.media='all'" />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <link rel="stylesheet" href="<?php echo base_url('assets/dist/bundle.css'); ?>" />
</head>
<!--end::Head-->

<!--begin::Body-->

<body data-page="<?php echo $page ?? ''; ?>" class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Start Navbar Links-->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                </ul>
                <!--end::Start Navbar Links-->
                <!--begin::End Navbar Links-->
                <ul class="navbar-nav ms-auto">
                    <?php if ($this->session->userdata('is_admin')) : ?>
                        <li class="nav-item d-none d-md-block">
                            <a href="<?php echo base_url('switch_user'); ?>" class="nav-link">
                                <i class="bi bi-person-up"></i> Switch User
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item dropdown">
                        <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static">
                            <span class="theme-icon-active">
                                <i class="my-1"></i>
                            </span>
                            <span class="d-lg-none ms-2" id="bd-theme-text">Toggle theme</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text" style="--bs-dropdown-min-width: 8rem;">
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light" aria-pressed="false">
                                    <i class="bi bi-sun-fill me-2"></i>
                                    Light
                                    <i class="bi bi-check-lg ms-auto d-none"></i>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                                    <i class="bi bi-moon-fill me-2"></i>
                                    Dark
                                    <i class="bi bi-check-lg ms-auto d-none"></i>
                                </button>
                            </li>
                        </ul>
                    </li>
                    <!--begin::User Menu Dropdown-->
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="user-image rounded-circle shadow" src="https://placehold.co/32x32/1e429f/ffffff?text=U" alt="user photo">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <!--begin::User Image-->
                            <li class="user-header text-bg-primary">
                                <img src="https://placehold.co/32x32/1e429f/ffffff?text=U" class="rounded-circle shadow" alt="User Image" />
                                <p>
                                    <?php echo $this->session->userdata('name'); ?>
                                    <?php foreach ($this->session->userdata('role_names') as $role) : ?>
                                        <small><?php echo ucwords(strtolower($role)); ?></small>
                                    <?php endforeach; ?>
                                </p>
                            </li>
                            <!--end::User Image-->
                            <!--begin::Menu Footer-->
                            <li class="user-footer">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                                <a href="<?php echo base_url('auth/logout'); ?>" class="btn btn-default btn-flat float-end">Sign out</a>
                            </li>
                            <!--end::Menu Footer-->
                        </ul>
                    </li>
                    <!--end::User Menu Dropdown-->
                </ul>
                <!--end::End Navbar Links-->
            </div>
            <!--end::Container-->
        </nav>
        <!--end::Header-->
        <!--begin::Sidebar-->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!--begin::Sidebar Brand-->
            <div class="sidebar-brand">
                <!--begin::Brand Link-->
                <a href="<?php echo base_url(); ?>" class="brand-link">
                    <!--begin::Brand Image-->
                    <img src="<?php echo base_url('assets/img/AdminLTELogo.png'); ?>" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
                    <!--end::Brand Image-->
                    <!--begin::Brand Text-->
                    <span class="brand-text fw-light"><?php echo $_ENV['APP_NAME']; ?></span>
                    <!--end::Brand Text-->
                </a>
                <!--end::Brand Link-->
            </div>
            <!--end::Sidebar Brand-->
            <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <!--begin::Sidebar Menu-->
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">
                        <!-- Dashboard (Accessible to all roles) -->
                        <li class="nav-item">
                            <a href="<?php echo base_url('dashboard'); ?>" class="nav-link <?php echo is_active('dashboard'); ?>">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <?php
                        $roles = $this->session->userdata('role_names') ?? [];
                        ?>

                        <!-- PKL Management for Mahasiswa -->
                        <?php if (in_array('student', $roles)) : ?>
                            <li class="nav-item <?php echo is_active('internship', 1, 'menu-open'); ?>">
                                <a href="#" class="nav-link <?php echo is_active('internship'); ?>">
                                    <i class="nav-icon bi bi-briefcase"></i>
                                    <p>
                                        PKL Management
                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('internship/applications'); ?>" class="nav-link <?php echo is_active('applications', 2); ?>">
                                            <i class="nav-icon bi bi-list-check"></i>
                                            <p>My Applications</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <!-- PKL Management for Dosen, KPS, Kadep -->
                        <?php if (in_array('lecturer', $roles) || in_array('head study program', $roles) || in_array('head department', $roles)) : ?>
                            <li class="nav-item <?php echo is_active('internship', 1, 'menu-open'); ?>">
                                <a href="#" class="nav-link <?php echo is_active('internship'); ?>">
                                    <i class="nav-icon bi bi-briefcase"></i>
                                    <p>
                                        PKL Management
                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('internship/applications/approvals'); ?>" class="nav-link <?php echo is_active('approvals', 3); ?>">
                                            <i class="nav-icon bi bi-check-circle"></i>
                                            <p>Approvals</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('internship/applications/supervised_internships'); ?>" class="nav-link <?php echo is_active('supervised_internships', 3); ?>">
                                            <i class="nav-icon bi bi-person-check"></i>
                                            <p>Bimbingan PKL</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('internship/applications/all_applications'); ?>" class="nav-link <?php echo is_active('all_applications', 3); ?>">
                                            <i class="nav-icon bi bi-list"></i>
                                            <p>All Applications</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>


                        <!-- Master Data for Admin (assumed role) -->
                        <?php if ($this->session->userdata('is_admin')) : ?>
                            <li class="nav-item <?php echo is_active('master_data', 1, 'menu-open'); ?>">
                                <a href="#" class="nav-link <?php echo is_active('master_data'); ?>">
                                    <i class="nav-icon bi bi-database"></i>
                                    <p>
                                        Master Data
                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('master_data/student'); ?>" class="nav-link <?php echo is_active('student', 2); ?>">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Mahasiswa</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('master_data/lecturer'); ?>" class="nav-link <?php echo is_active('lecturer', 2); ?>">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Dosen</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('master_data/staff'); ?>" class="nav-link <?php echo is_active('staff', 2); ?>">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Staff</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('master_data/study_program'); ?>" class="nav-link <?php echo is_active('study_program', 2); ?>">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Program Studi</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('master_data/module'); ?>" class="nav-link <?php echo is_active('module', 2); ?>">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Modul</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('master_data/role'); ?>" class="nav-link <?php echo is_active('role', 2); ?>">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Peran Pengguna</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <!--end::Sidebar Menu-->
                </nav>
            </div>
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->
        <!--begin::App Main-->
        <main class="app-main">
            <?php echo $contents; ?>
        </main>
        <!--end::App Main-->
        <!--begin::Footer-->
        <footer class="app-footer">
            <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">Version <?php echo $_ENV['APP_VERSION']; ?></div>
            <!--end::To the end-->
            <!--begin::Copyright-->
            <strong>
                Copyright &copy; <?php echo date('Y'); ?>&nbsp;
                <a href="https://math.ub.ac.id" class="text-decoration-none">Math Department FMIPA UB</a>.
            </strong>
            All rights reserved.
            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <script src="<?php echo base_url('assets/dist/bundle.js'); ?>"></script>
    <!--end::Script-->
</body>
<!--end::Body-->

</html>