import { OverlayScrollbars } from 'overlayscrollbars';

const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
const SCROLLBAR_CONFIG = {
    scrollbars: {
        theme: 'os-theme-light',
        autoHide: 'leave',
        clickScroll: true,
    },
};

export function initSidebarScrollbars() {
    const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
    if (sidebarWrapper) {
        OverlayScrollbars(sidebarWrapper, SCROLLBAR_CONFIG);
    }
}