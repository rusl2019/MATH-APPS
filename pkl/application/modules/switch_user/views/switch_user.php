<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SIMATH | Switch User</title>

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <link rel="icon" href="https://mipa.ub.ac.id/wp-content/uploads/2022/12/cropped-ub-01-1-32x32.png" sizes="32x32" />
    <link rel="icon" href="https://mipa.ub.ac.id/wp-content/uploads/2022/12/cropped-ub-01-1-192x192.png" sizes="192x192" />
    <link rel="apple-touch-icon" href="https://mipa.ub.ac.id/wp-content/uploads/2022/12/cropped-ub-01-1-180x180.png" />

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/css/adminlte.min.css" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print" onload="this.media='all'" />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/css/adminlte.min.css" crossorigin="anonymous" />
    <!--end::Required Plugin(AdminLTE)-->

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
</head>
<!--end::Head-->
<!--begin::Body-->

<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <a href="<?php echo base_url(); ?>" class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
                    <h1 class="mb-0"><b>SIMATH</b></h1>
                </a>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">Select <strong>one user</strong> to start your session</p>
                <?php echo form_open('switch_user/change_user', ['id' => 'login_form']); ?>
                <div class="mb-3">
                    <select id="user_type" name="user_type" class="form-select" style="width: 100%;">
                        <option value="">Select User Type</option>
                        <option value="students">Student</option>
                        <option value="lecturers">Lecturer</option>
                        <option value="staffs">Staff</option>
                    </select>
                </div>
                <div class="mb-3" style="display: none;" id="user_id_container">
                    <select id="user_id" name="user_id" class="form-select" style="width: 100%;">
                        <option value="">Select User</option>
                    </select>
                </div>
                <div class="social-auth-links text-center mb-3 d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Switch User
                    </button>
                </div>
                <?php echo form_close(); ?>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->
    <!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <!--end::Required Plugin(Bootstrap 5)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/js/adminlte.min.js" crossorigin="anonymous"></script>
    <!--end::Required Plugin(AdminLTE)-->
    <!--begin::OverlayScrollbars Configure-->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
    <!--end::OverlayScrollbars Configure-->

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js" integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            // Constants
            const BASE_URL = "<?php echo base_url($this->uri->segment(1)); ?>";
            const CONFIGS = {
                userType: {
                    minimumResultsForSearch: Infinity
                },
                userId: {
                    minimumInputLength: 3,
                    delay: 250,
                    cache: true
                }
            };

            // Initialize Select2 for user type
            $("#user_type").select2({
                theme: "bootstrap-5",
                placeholder: "Select User Type",
                ...CONFIGS.userType
            });

            // Initialize Select2 for user selection
            $("#user_id").select2({
                theme: "bootstrap-5",
                placeholder: "Select User",
                ...CONFIGS.userId,
                ajax: {
                    url: `${BASE_URL}/get_users_by_type`,
                    type: "POST",
                    dataType: "json",
                    data: function(params) {
                        return {
                            q: params.term,
                            user_type: $("#user_type").val(),
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.items.map(item => ({
                                id: item.id,
                                text: item.name || item.text
                            })),
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    }
                },
                templateResult: function(user) {
                    if (user.loading) return "Searching...";
                    if (!user.id) return user.text;

                    return $(`
                    <div class="user-result">
                        <div class="user-name">${user.text}</div>
                    </div>
                `);
                }
            });

            // Handle user type change
            $("#user_type").on("change", function() {
                const userIdContainer = $("#user_id_container");
                const userId = $("#user_id");

                if ($(this).val()) {
                    userIdContainer.show();
                    userId.val(null).trigger("change");
                    setTimeout(() => $(".select2-search__field").focus(), 100);
                } else {
                    userIdContainer.hide();
                    userId.val(null).trigger("change");
                }
            });

            // Form validation
            $("#login_form").on("submit", function(e) {
                const userType = $("#user_type").val();
                const userId = $("#user_id").val();

                if (!userType || !userId) {
                    e.preventDefault();
                    alert("Please select both user type and user");
                    return false;
                }
            });
        });
    </script>
</body>
<!--end::Body-->

</html>