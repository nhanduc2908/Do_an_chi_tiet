/**
 * Device Connector Module
 * Quản lý kết nối giữa các thiết bị
 */

export class DeviceConnector {
    constructor() {
        this.devices = [];
        this.currentDevice = null;
        this.connections = new Map();
        this.eventListeners = new Map();
        this.init();
    }
    
    init() {
        this.loadSavedDevices();
        this.setupBroadcastChannel();
        this.startDiscovery();
    }
    
    loadSavedDevices() {
        const saved = localStorage.getItem('connected_devices');
        if (saved) {
            this.devices = JSON.parse(saved);
        }
    }
    
    setupBroadcastChannel() {
        // Use BroadcastChannel for same-origin communication
        if ('BroadcastChannel' in window) {
            this.channel = new BroadcastChannel('device_connector');
            this.channel.onmessage = (event) => {
                this.handleBroadcastMessage(event.data);
            };
        }
    }
    
    startDiscovery() {
        // Discover devices on local network
        this.discoverDevices();
        
        // Periodically scan for devices
        setInterval(() => {
            this.discoverDevices();
        }, 30000);
    }
    
    discoverDevices() {
        // Use WebRTC for device discovery
        if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
            navigator.mediaDevices.enumerateDevices().then(devices => {
                const newDevices = devices.map(device => ({
                    id: device.deviceId,
                    kind: device.kind,
                    label: device.label || `Device ${device.kind}`,
                    groupId: device.groupId
                }));
                
                this.devices = newDevices;
                this.emit('devicesUpdated', this.devices);
            });
        }
        
        // Also check for other browser instances
        if (this.channel) {
            this.channel.postMessage({
                type: 'discover',
                deviceId: this.getDeviceId(),
                timestamp: Date.now()
            });
        }
    }
    
    getDeviceId() {
        let deviceId = localStorage.getItem('device_id');
        if (!deviceId) {
            deviceId = this.generateDeviceId();
            localStorage.setItem('device_id', deviceId);
        }
        return deviceId;
    }
    
    generateDeviceId() {
        return 'device_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }
    
    handleBroadcastMessage(message) {
        switch(message.type) {
            case 'discover':
                this.handleDiscovery(message);
                break;
            case 'connect':
                this.handleConnectRequest(message);
                break;
            case 'message':
                this.handleDeviceMessage(message);
                break;
            case 'disconnect':
                this.handleDisconnect(message);
                break;
        }
    }
    
    handleDiscovery(message) {
        // Respond to discovery
        if (this.channel && message.deviceId !== this.getDeviceId()) {
            this.channel.postMessage({
                type: 'discover_response',
                deviceId: this.getDeviceId(),
                deviceName: this.getDeviceName(),
                timestamp: Date.now()
            });
        }
    }
    
    handleConnectRequest(message) {
        // Handle connection request
        const confirmed = confirm(`Device "${message.deviceName}" wants to connect. Allow?`);
        
        if (confirmed) {
            this.acceptConnection(message.deviceId);
        }
    }
    
    acceptConnection(deviceId) {
        this.connections.set(deviceId, {
            status: 'connected',
            connectedAt: Date.now()
        });
        
        if (this.channel) {
            this.channel.postMessage({
                type: 'connect_accept',
                deviceId: this.getDeviceId(),
                targetId: deviceId
            });
        }
        
        this.emit('deviceConnected', { deviceId });
    }
    
    handleDeviceMessage(message) {
        this.emit('message', message);
    }
    
    handleDisconnect(message) {
        this.connections.delete(message.deviceId);
        this.emit('deviceDisconnected', { deviceId: message.deviceId });
    }
    
    getDeviceName() {
        return localStorage.getItem('device_name') || navigator.userAgent.split(' ').slice(-2).join(' ');
    }
    
    sendToDevice(deviceId, data) {
        if (this.channel) {
            this.channel.postMessage({
                type: 'message',
                from: this.getDeviceId(),
                to: deviceId,
                data: data,
                timestamp: Date.now()
            });
        }
    }
    
    getConnectedDevices() {
        return Array.from(this.connections.keys()).map(id => ({
            id: id,
            name: this.getDeviceNameById(id),
            connectedAt: this.connections.get(id).connectedAt
        }));
    }
    
    getDeviceNameById(deviceId) {
        // In real implementation, fetch from stored device info
        return `Device ${deviceId.substr(-4)}`;
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
    
    disconnect(deviceId) {
        if (this.connections.has(deviceId)) {
            this.connections.delete(deviceId);
            
            if (this.channel) {
                this.channel.postMessage({
                    type: 'disconnect',
                    deviceId: this.getDeviceId(),
                    targetId: deviceId
                });
            }
            
            this.emit('deviceDisconnected', { deviceId });
        }
    }
    
    disconnectAll() {
        this.connections.forEach((_, deviceId) => {
            this.disconnect(deviceId);
        });
    }
}