/**
 * Application Bootstrap
 * Khởi tạo các service và utilities
 */

import axios from 'axios';
import * as _ from 'lodash';
import moment from 'moment';
import 'moment/locale/vi';

// Set locale
moment.locale('vi');

// Lodash mixins
_.mixin({
    'deepClone': (obj) => JSON.parse(JSON.stringify(obj)),
    'isEmptyObject': (obj) => Object.keys(obj).length === 0 && obj.constructor === Object,
});

// Format helpers
window.formatNumber = (num) => {
    return new Intl.NumberFormat('vi-VN').format(num);
};

window.formatCurrency = (amount) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
};

window.formatDate = (date, format = 'DD/MM/YYYY') => {
    return moment(date).format(format);
};

window.formatDateTime = (date) => {
    return moment(date).format('DD/MM/YYYY HH:mm:ss');
};

window.timeAgo = (date) => {
    return moment(date).fromNow();
};

// String helpers
window.truncate = (str, length = 50) => {
    if (str.length <= length) return str;
    return str.substring(0, length) + '...';
};

window.slugify = (str) => {
    return str
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
};

// URL helpers
window.getQueryParams = () => {
    const params = new URLSearchParams(window.location.search);
    const result = {};
    for (const [key, value] of params) {
        result[key] = value;
    }
    return result;
};

window.buildQueryString = (params) => {
    const searchParams = new URLSearchParams();
    Object.keys(params).forEach(key => {
        if (params[key] !== null && params[key] !== undefined && params[key] !== '') {
            searchParams.append(key, params[key]);
        }
    });
    const queryString = searchParams.toString();
    return queryString ? `?${queryString}` : '';
};

// Storage helpers
window.storage = {
    set(key, value) {
        localStorage.setItem(key, JSON.stringify(value));
    },
    get(key, defaultValue = null) {
        const value = localStorage.getItem(key);
        try {
            return value ? JSON.parse(value) : defaultValue;
        } catch {
            return value || defaultValue;
        }
    },
    remove(key) {
        localStorage.removeItem(key);
    },
    clear() {
        localStorage.clear();
    },
};

// Export
export { axios, _ };