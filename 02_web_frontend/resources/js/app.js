/**
 * Security Evaluation System - Main Application Entry
 * Version: 2.0.0
 */

import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import persist from '@alpinejs/persist';
import trap from '@alpinejs/trap';

// Third-party libraries
import axios from 'axios';
import * as _ from 'lodash';
import moment from 'moment';
import Cookies from 'js-cookie';
import DOMPurify from 'dompurify';
import NProgress from 'nprogress';
import 'nprogress/nprogress.css';

// Custom modules
import { router } from './modules/router';
import { auth } from './modules/auth';
import { store } from './modules/store';
import { websocket } from './modules/websocket';
import { eventBus } from './modules/eventBus';
import { notification } from './modules/notification';

// Components
import './components/datepicker';
import './components/charts';
import './components/modals';
import './components/dropdowns';
import './components/tooltips';

// Styles
import 'nprogress/nprogress.css';

// -------------------- Alpine.js Configuration --------------------
window.Alpine = Alpine;

// Register plugins
Alpine.plugin(intersect);
Alpine.plugin(persist);
Alpine.plugin(trap);

// Global Alpine stores
Alpine.store('app', {
    darkMode: localStorage.getItem('darkMode') === 'true' || false,
    sidebarOpen: true,
    notifications: [],
    
    toggleDarkMode() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        document.documentElement.classList.toggle('dark', this.darkMode);
    },
    
    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
    },
    
    addNotification(notification) {
        this.notifications.unshift({
            id: Date.now(),
            ...notification,
            createdAt: new Date(),
        });
        
        setTimeout(() => {
            this.removeNotification(notification.id);
        }, 5000);
    },
    
    removeNotification(id) {
        this.notifications = this.notifications.filter(n => n.id !== id);
    },
});

Alpine.store('user', {
    data: null,
    permissions: [],
    roles: [],
    
    async fetch() {
        try {
            const response = await axios.get('/api/user');
            this.data = response.data;
            this.permissions = response.data.permissions || [];
            this.roles = response.data.roles || [];
            return response.data;
        } catch (error) {
            console.error('Failed to fetch user:', error);
            return null;
        }
    },
    
    hasPermission(permission) {
        return this.permissions.includes(permission);
    },
    
    hasRole(role) {
        return this.roles.includes(role);
    },
    
    isAdmin() {
        return this.hasRole('admin') || this.hasRole('administrator');
    },
});

// -------------------- Axios Configuration --------------------
axios.defaults.baseURL = import.meta.env.VITE_API_URL || '/api';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.content;

// Request interceptor
axios.interceptors.request.use(config => {
    NProgress.start();
    
    // Add auth token
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    
    return config;
}, error => {
    NProgress.done();
    return Promise.reject(error);
});

// Response interceptor
axios.interceptors.response.use(response => {
    NProgress.done();
    return response;
}, error => {
    NProgress.done();
    
    if (error.response?.status === 401) {
        auth.logout();
        window.location.href = '/login';
    }
    
    if (error.response?.status === 403) {
        notification.error('Bạn không có quyền thực hiện hành động này');
    }
    
    if (error.response?.status === 500) {
        notification.error('Có lỗi xảy ra, vui lòng thử lại sau');
    }
    
    return Promise.reject(error);
});

// -------------------- Global Utilities --------------------
window._ = _;
window.moment = moment;
window.dompurify = DOMPurify;
window.eventBus = eventBus;

// -------------------- DOM Ready --------------------
document.addEventListener('DOMContentLoaded', () => {
    // Initialize tooltips
    initTooltips();
    
    // Initialize modals
    initModals();
    
    // Initialize dropdowns
    initDropdowns();
    
    // Start Alpine
    Alpine.start();
    
    // Initialize WebSocket if enabled
    if (import.meta.env.VITE_ENABLE_WEBSOCKET === 'true') {
        websocket.connect();
    }
    
    // Check authentication
    if (window.location.pathname !== '/login' && window.location.pathname !== '/register') {
        auth.check();
    }
    
    console.log('Application initialized', {
        version: import.meta.env.VITE_APP_VERSION,
        environment: import.meta.env.MODE,
    });
});

// -------------------- Helper Functions --------------------
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    
    tooltips.forEach(el => {
        el.addEventListener('mouseenter', () => {
            const tip = document.createElement('div');
            tip.className = 'fixed z-50 px-2 py-1 text-xs font-medium text-white bg-gray-900 rounded shadow-lg whitespace-nowrap';
            tip.textContent = el.dataset.tooltip;
            document.body.appendChild(tip);
            
            const rect = el.getBoundingClientRect();
            tip.style.top = rect.top - tip.offsetHeight - 5 + 'px';
            tip.style.left = rect.left + (rect.width - tip.offsetWidth) / 2 + 'px';
            
            el.addEventListener('mouseleave', () => tip.remove(), { once: true });
        });
    });
}

function initModals() {
    const modalTriggers = document.querySelectorAll('[data-modal-target]');
    
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
            const target = trigger.dataset.modalTarget;
            const modal = document.getElementById(target);
            if (modal) modal.classList.add('open');
        });
    });
    
    document.querySelectorAll('.modal-close, [data-modal-close]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('.modal');
            if (modal) modal.classList.remove('open');
        });
    });
}

function initDropdowns() {
    const dropdowns = document.querySelectorAll('[data-dropdown]');
    
    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('[data-dropdown-toggle]');
        const menu = dropdown.querySelector('[data-dropdown-menu]');
        
        if (toggle && menu) {
            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                menu.classList.toggle('show');
            });
            
            document.addEventListener('click', (e) => {
                if (!dropdown.contains(e.target)) {
                    menu.classList.remove('show');
                }
            });
        }
    });
}

// -------------------- Export --------------------
export { Alpine, axios, auth, router, store, websocket, eventBus, notification };