/**
 * Screen Mirror Module
 * Chia sẻ và nhân bản màn hình giữa các thiết bị
 */

export class ScreenMirror {
    constructor() {
        this.stream = null;
        this.peerConnection = null;
        this.isMirroring = false;
        this.viewers = new Set();
        this.eventListeners = new Map();
        this.configuration = {
            iceServers: [
                { urls: 'stun:stun.l.google.com:19302' },
                { urls: 'stun:stun1.l.google.com:19302' }
            ]
        };
        
        this.init();
    }
    
    async init() {
        // Check if screen capture is supported
        if (!navigator.mediaDevices || !navigator.mediaDevices.getDisplayMedia) {
            console.warn('Screen capture not supported');
            this.emit('unsupported');
        }
    }
    
    async startMirror() {
        try {
            // Request screen capture
            this.stream = await navigator.mediaDevices.getDisplayMedia({
                video: {
                    cursor: 'always'
                },
                audio: false
            });
            
            this.isMirroring = true;
            this.setupLocalVideo();
            this.setupPeerConnection();
            this.emit('started', this.stream);
            
            // Handle stream end
            this.stream.getVideoTracks()[0].onended = () => {
                this.stopMirror();
            };
            
        } catch (error) {
            console.error('Failed to start mirror:', error);
            this.emit('error', error);
        }
    }
    
    setupLocalVideo() {
        const videoElement = document.getElementById('localScreen');
        if (videoElement) {
            videoElement.srcObject = this.stream;
            videoElement.play();
        }
    }
    
    setupPeerConnection() {
        this.peerConnection = new RTCPeerConnection(this.configuration);
        
        // Add tracks
        this.stream.getTracks().forEach(track => {
            this.peerConnection.addTrack(track, this.stream);
        });
        
        // Handle ICE candidates
        this.peerConnection.onicecandidate = (event) => {
            if (event.candidate) {
                this.broadcastCandidate(event.candidate);
            }
        };
        
        // Handle connection state
        this.peerConnection.onconnectionstatechange = () => {
            console.log('Connection state:', this.peerConnection.connectionState);
            this.emit('connectionStateChange', this.peerConnection.connectionState);
        };
    }
    
    async addViewer(viewerId, viewerConnection) {
        if (!this.peerConnection) {
            return;
        }
        
        try {
            const offer = await this.peerConnection.createOffer();
            await this.peerConnection.setLocalDescription(offer);
            
            viewerConnection.send({
                type: 'mirror_offer',
                sdp: offer,
                from: this.getDeviceId()
            });
            
            this.viewers.add(viewerId);
            this.emit('viewerAdded', viewerId);
        } catch (error) {
            console.error('Failed to add viewer:', error);
        }
    }
    
    removeViewer(viewerId) {
        this.viewers.delete(viewerId);
        this.emit('viewerRemoved', viewerId);
    }
    
    broadcastCandidate(candidate) {
        this.viewers.forEach(viewerId => {
            // Send candidate to each viewer
            this.sendToViewer(viewerId, {
                type: 'ice_candidate',
                candidate: candidate
            });
        });
    }
    
    sendToViewer(viewerId, data) {
        // In real implementation, send via WebSocket or signaling server
        console.log(`Sending to viewer ${viewerId}:`, data);
        this.emit('sendToViewer', { viewerId, data });
    }
    
    async stopMirror() {
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
            this.stream = null;
        }
        
        if (this.peerConnection) {
            this.peerConnection.close();
            this.peerConnection = null;
        }
        
        this.isMirroring = false;
        this.viewers.clear();
        this.emit('stopped');
    }
    
    getDeviceId() {
        return localStorage.getItem('device_id') || 'unknown';
    }
    
    async viewMirror(sourceId, signalingData) {
        try {
            this.peerConnection = new RTCPeerConnection(this.configuration);
            
            // Handle incoming tracks
            this.peerConnection.ontrack = (event) => {
                const videoElement = document.getElementById('remoteScreen');
                if (videoElement) {
                    videoElement.srcObject = event.streams[0];
                    videoElement.play();
                }
                this.emit('viewingStarted');
            };
            
            // Handle ICE candidates
            this.peerConnection.onicecandidate = (event) => {
                if (event.candidate) {
                    signalingData.send({
                        type: 'ice_candidate',
                        candidate: event.candidate
                    });
                }
            };
            
            // Set remote description
            await this.peerConnection.setRemoteDescription(new RTCSessionDescription(signalingData.sdp));
            
            // Create answer
            const answer = await this.peerConnection.createAnswer();
            await this.peerConnection.setLocalDescription(answer);
            
            signalingData.send({
                type: 'mirror_answer',
                sdp: answer
            });
            
        } catch (error) {
            console.error('Failed to view mirror:', error);
            this.emit('error', error);
        }
    }
    
    stopViewing() {
        if (this.peerConnection) {
            this.peerConnection.close();
            this.peerConnection = null;
        }
        
        const videoElement = document.getElementById('remoteScreen');
        if (videoElement) {
            videoElement.srcObject = null;
        }
        
        this.emit('viewingStopped');
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
    
    isSupported() {
        return !!(navigator.mediaDevices && navigator.mediaDevices.getDisplayMedia);
    }
}