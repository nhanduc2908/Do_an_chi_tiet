/**
 * Responsive Engine Module
 * Điều chỉnh layout và UI theo kích thước màn hình
 */

import { ScreenDetector } from './screenDetector.js';

export class ResponsiveEngine {
    constructor() {
        this.screenDetector = new ScreenDetector();
        this.responsiveClasses = [];
        this.init();
    }
    
    init() {
        this.applyResponsive();
        this.screenDetector.on('resize', () => this.applyResponsive());
        this.screenDetector.on('deviceChange', () => this.handleDeviceChange());
    }
    
    applyResponsive() {
        const info = this.screenDetector.detect();
        
        // Remove existing responsive classes
        document.body.classList.remove('is-mobile', 'is-tablet', 'is-desktop', 'is-large');
        document.body.classList.remove('orientation-portrait', 'orientation-landscape');
        
        // Add new classes
        document.body.classList.add(`is-${info.device}`);
        document.body.classList.add(`orientation-${info.orientation}`);
        
        if (info.hasTouch) {
            document.body.classList.add('has-touch');
        }
        
        // Apply data attributes
        document.documentElement.setAttribute('data-device', info.device);
        document.documentElement.setAttribute('data-orientation', info.orientation);
        document.documentElement.setAttribute('data-width', info.width);
        
        // Adjust font size for mobile
        if (info.device === 'mobile') {
            document.documentElement.style.fontSize = '14px';
        } else {
            document.documentElement.style.fontSize = '16px';
        }
        
        this.applyConditionalElements(info);
        this.applyResponsiveImages();
    }
    
    applyConditionalElements(info) {
        // Show/hide conditional elements
        document.querySelectorAll('[data-show-mobile]').forEach(el => {
            el.style.display = info.device === 'mobile' ? '' : 'none';
        });
        
        document.querySelectorAll('[data-hide-mobile]').forEach(el => {
            el.style.display = info.device === 'mobile' ? 'none' : '';
        });
        
        document.querySelectorAll('[data-show-tablet]').forEach(el => {
            el.style.display = info.device === 'tablet' ? '' : 'none';
        });
        
        document.querySelectorAll('[data-hide-tablet]').forEach(el => {
            el.style.display = info.device === 'tablet' ? 'none' : '';
        });
        
        document.querySelectorAll('[data-show-desktop]').forEach(el => {
            el.style.display = info.device === 'desktop' || info.device === 'large' ? '' : 'none';
        });
    }
    
    applyResponsiveImages() {
        const images = document.querySelectorAll('img[data-src-mobile], img[data-src-tablet], img[data-src-desktop]');
        const info = this.screenDetector.detect();
        
        images.forEach(img => {
            let src = img.getAttribute(`data-src-${info.device}`);
            if (src) {
                img.src = src;
            }
        });
    }
    
    handleDeviceChange(info) {
        // Trigger device-specific initialization
        switch(info.device) {
            case 'mobile':
                this.initMobileLayout();
                break;
            case 'tablet':
                this.initTabletLayout();
                break;
            case 'desktop':
            case 'large':
                this.initDesktopLayout();
                break;
        }
    }
    
    initMobileLayout() {
        // Convert tables to cards
        document.querySelectorAll('.responsive-table').forEach(table => {
            table.classList.add('mobile-table');
            this.convertTableToCards(table);
        });
        
        // Setup mobile navigation
        this.setupMobileNav();
    }
    
    initTabletLayout() {
        // Setup split view for tablet
        document.querySelector('.tablet-layout')?.classList.add('active');
    }
    
    initDesktopLayout() {
        // Setup advanced desktop features
        this.setupKeyboardShortcuts();
    }
    
    convertTableToCards(table) {
        const headers = [];
        table.querySelectorAll('thead th').forEach(th => {
            headers.push(th.textContent);
        });
        
        table.querySelectorAll('tbody tr').forEach((row, index) => {
            row.querySelectorAll('td').forEach((td, tdIndex) => {
                td.setAttribute('data-label', headers[tdIndex]);
            });
        });
    }
    
    setupMobileNav() {
        // Create mobile bottom navigation if not exists
        if (!document.querySelector('.mobile-nav')) {
            const nav = document.createElement('nav');
            nav.className = 'mobile-nav';
            nav.innerHTML = `
                <a href="/dashboard" class="mobile-nav-item">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Trang chủ</span>
                </a>
                <a href="/notifications" class="mobile-nav-item">
                    <i class="fas fa-bell"></i>
                    <span>Thông báo</span>
                </a>
                <a href="/profile" class="mobile-nav-item">
                    <i class="fas fa-user"></i>
                    <span>Tôi</span>
                </a>
            `;
            document.body.appendChild(nav);
        }
    }
    
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl + K = Search
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                document.querySelector('[data-search]')?.focus();
            }
            
            // Ctrl + S = Save
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                document.querySelector('[data-save]')?.click();
            }
        });
    }
}