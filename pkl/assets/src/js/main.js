// Import Stylesheets
import "admin-lte/dist/css/adminlte.min.css";
import "overlayscrollbars/overlayscrollbars.css";
import 'select2/dist/css/select2.min.css';
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css';
import 'toastr/build/toastr.min.css';
import "../css/datatables.local.css";

// Import Core Libraries
import * as bootstrap from "bootstrap";
import * as adminlte from "admin-lte";

// Expose Bootstrap to the global window object.
// This is needed for inline scripts in some views that use Bootstrap's JavaScript APIs directly,
// such as creating modal instances (e.g., new bootstrap.Modal(...)).
window.bootstrap = bootstrap;

// Import fungsionalitas modular yang sudah ada
import { initSidebarScrollbars } from "./sidebar.js";
import "./color-mode.js";

// Import sistem master data modular yang baru
import MasterDataHandler from './MasterDataHandler.js';
import masterDataConfigs from './master-data-configs.js';

// Inisialisasi semua komponen setelah halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    initSidebarScrollbars();

    const pageElement = document.querySelector('[data-page]');
    if (pageElement) {
        const pageName = pageElement.dataset.page;
        const config = masterDataConfigs[pageName];

        if (config) {
            new MasterDataHandler(config);
        }
    }
});