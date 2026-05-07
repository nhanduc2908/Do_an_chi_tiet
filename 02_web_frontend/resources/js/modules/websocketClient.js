/**
 * WebSocket Client Module
 * Quản lý kết nối WebSocket real-time
 */

export class WebSocketClient {
    constructor(url, options = {}) {
        this.url = url;
        this.options = {
            reconnectInterval: 3000,
            maxReconnectAttempts: 10,
            heartbeatInterval: 30000,
            ...options
        };
        
        this.ws = null;
        this.isConnected = false;
        this.reconnectAttempts = 0;
        this.heartbeatTimer = null;
        this.messageHandlers = new Map();
        this.eventListeners = new Map();
        
        this.init();
    }
    
    init() {
        this.connect();
    }
    
    connect() {
        try {
            this.ws = new WebSocket(this.url);
            this.setupEventHandlers();
        } catch (error) {
            console.error('WebSocket connection error:', error);
            this.handleReconnect();
        }
    }
    
    setupEventHandlers() {
        this.ws.onopen = () => {
            console.log('WebSocket connected');
            this.isConnected = true;
            this.reconnectAttempts = 0;
            this.startHeartbeat();
            this.emit('connected');
        };
        
        this.ws.onclose = () => {
            console.log('WebSocket disconnected');
            this.isConnected = false;
            this.stopHeartbeat();
            this.handleReconnect();
            this.emit('disconnected');
        };
        
        this.ws.onerror = (error) => {
            console.error('WebSocket error:', error);
            this.emit('error', error);
        };
        
        this.ws.onmessage = (event) => {
            this.handleMessage(event.data);
        };
    }
    
    handleMessage(data) {
        try {
            const message = JSON.parse(data);
            const { type, payload } = message;
            
            // Handle specific message types
            switch(type) {
                case 'ping':
                    this.sendPong();
                    break;
                case 'notification':
                    this.emit('notification', payload);
                    break;
                default:
                    // Call registered handlers
                    if (this.messageHandlers.has(type)) {
                        this.messageHandlers.get(type).forEach(handler => handler(payload));
                    }
                    this.emit('message', message);
            }
        } catch (error) {
            console.error('Failed to parse message:', error);
        }
    }
    
    send(type, payload = {}) {
        if (!this.isConnected) {
            console.warn('WebSocket not connected');
            return false;
        }
        
        const message = JSON.stringify({
            type: type,
            payload: payload,
            timestamp: Date.now()
        });
        
        this.ws.send(message);
        return true;
    }
    
    sendPong() {
        this.send('pong');
    }
    
    startHeartbeat() {
        this.heartbeatTimer = setInterval(() => {
            if (this.isConnected) {
                this.send('ping');
            }
        }, this.options.heartbeatInterval);
    }
    
    stopHeartbeat() {
        if (this.heartbeatTimer) {
            clearInterval(this.heartbeatTimer);
            this.heartbeatTimer = null;
        }
    }
    
    handleReconnect() {
        if (this.reconnectAttempts >= this.options.maxReconnectAttempts) {
            console.error('Max reconnection attempts reached');
            this.emit('reconnect_failed');
            return;
        }
        
        this.reconnectAttempts++;
        console.log(`Reconnecting in ${this.options.reconnectInterval}ms (attempt ${this.reconnectAttempts})`);
        
        setTimeout(() => {
            this.connect();
        }, this.options.reconnectInterval);
    }
    
    on(event, callback) {
        if (!this.eventListeners.has(event)) {
            this.eventListeners.set(event, []);
        }
        this.eventListeners.get(event).push(callback);
    }
    
    emit(event, data) {
        if (this.eventListeners.has(event)) {
            this.eventListeners.get(event).forEach(callback => callback(data));
        }
    }
    
    onMessage(type, handler) {
        if (!this.messageHandlers.has(type)) {
            this.messageHandlers.set(type, []);
        }
        this.messageHandlers.get(type).push(handler);
    }
    
    offMessage(type, handler) {
        if (this.messageHandlers.has(type)) {
            const handlers = this.messageHandlers.get(type);
            const index = handlers.indexOf(handler);
            if (index !== -1) {
                handlers.splice(index, 1);
            }
        }
    }
    
    disconnect() {
        if (this.ws) {
            this.stopHeartbeat();
            this.ws.close();
            this.ws = null;
            this.isConnected = false;
        }
    }
    
    reconnect() {
        this.disconnect();
        this.reconnectAttempts = 0;
        this.connect();
    }
}