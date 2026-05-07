/**
 * Authentication Module
 * Quản lý xác thực và phiên đăng nhập
 */

import axios from 'axios';
import Cookies from 'js-cookie';
import { eventBus } from './eventBus';

class Auth {
    constructor() {
        this.user = null;
        this.token = localStorage.getItem('auth_token');
        this.init();
    }
    
    init() {
        // Check for existing token
        if (this.token) {
            this.setupAxios();
            this.fetchUser();
        }
        
        // Listen for token expiration
        setInterval(() => this.checkTokenExpiration(), 60000);
    }
    
    setupAxios() {
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
    }
    
    async login(email, password, remember = false) {
        try {
            const response = await axios.post('/login', { email, password });
            const { token, user } = response.data;
            
            this.token = token;
            this.user = user;
            
            localStorage.setItem('auth_token', token);
            if (remember) {
                localStorage.setItem('remember_token', token);
            }
            
            this.setupAxios();
            eventBus.emit('auth:login', user);
            
            return { success: true, user };
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || 'Đăng nhập thất bại',
            };
        }
    }
    
    async logout() {
        try {
            await axios.post('/logout');
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            this.token = null;
            this.user = null;
            localStorage.removeItem('auth_token');
            localStorage.removeItem('remember_token');
            delete axios.defaults.headers.common['Authorization'];
            eventBus.emit('auth:logout');
            window.location.href = '/login';
        }
    }
    
    async fetchUser() {
        try {
            const response = await axios.get('/user');
            this.user = response.data;
            eventBus.emit('auth:user-updated', this.user);
            return this.user;
        } catch (error) {
            if (error.response?.status === 401) {
                this.logout();
            }
            return null;
        }
    }
    
    async register(data) {
        try {
            const response = await axios.post('/register', data);
            return { success: true, data: response.data };
        } catch (error) {
            return {
                success: false,
                errors: error.response?.data?.errors || { general: ['Đăng ký thất bại'] },
            };
        }
    }
    
    async forgotPassword(email) {
        try {
            await axios.post('/forgot-password', { email });
            return { success: true, message: 'Email khôi phục đã được gửi' };
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || 'Gửi yêu cầu thất bại',
            };
        }
    }
    
    async resetPassword(token, email, password, passwordConfirmation) {
        try {
            await axios.post('/reset-password', {
                token,
                email,
                password,
                password_confirmation: passwordConfirmation,
            });
            return { success: true, message: 'Mật khẩu đã được cập nhật' };
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || 'Đặt lại mật khẩu thất bại',
            };
        }
    }
    
    async verify2FA(code) {
        try {
            const response = await axios.post('/verify-2fa', { code });
            return { success: true, data: response.data };
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || 'Mã xác thực không đúng',
            };
        }
    }
    
    async enable2FA() {
        try {
            const response = await axios.post('/2fa/enable');
            return { success: true, data: response.data };
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || 'Bật 2FA thất bại',
            };
        }
    }
    
    async disable2FA() {
        try {
            await axios.post('/2fa/disable');
            return { success: true };
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || 'Tắt 2FA thất bại',
            };
        }
    }
    
    checkTokenExpiration() {
        if (!this.token) return;
        
        // Decode JWT token to check expiration
        try {
            const payload = JSON.parse(atob(this.token.split('.')[1]));
            const exp = payload.exp * 1000;
            const now = Date.now();
            
            // Refresh token if expires in less than 5 minutes
            if (exp - now < 300000) {
                this.refreshToken();
            }
        } catch (error) {
            console.error('Token decode error:', error);
        }
    }
    
    async refreshToken() {
        try {
            const response = await axios.post('/refresh-token');
            const { token } = response.data;
            this.token = token;
            localStorage.setItem('auth_token', token);
            this.setupAxios();
            eventBus.emit('auth:token-refreshed');
        } catch (error) {
            console.error('Token refresh failed:', error);
            this.logout();
        }
    }
    
    isAuthenticated() {
        return !!this.token && !!this.user;
    }
    
    hasPermission(permission) {
        return this.user?.permissions?.includes(permission) || false;
    }
    
    hasRole(role) {
        return this.user?.roles?.includes(role) || false;
    }
    
    isAdmin() {
        return this.hasRole('admin') || this.hasRole('administrator');
    }
}

export const auth = new Auth();