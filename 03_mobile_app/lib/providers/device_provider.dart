import 'package:flutter/material.dart';
import '../models/device_model.dart';
import '../services/api_service.dart';

class DeviceProvider extends ChangeNotifier {
  final ApiService _apiService = ApiService();
  
  List<DeviceModel> _devices = [];
  bool _isLoading = false;
  bool _isLinking = false;
  String? _errorMessage;
  DeviceModel? _selectedDevice;

  // Getters
  List<DeviceModel> get devices => _devices;
  List<DeviceModel> get onlineDevices => _devices.where((d) => d.isOnline).toList();
  List<DeviceModel> get pendingDevices => _devices.where((d) => d.isPending).toList();
  List<DeviceModel> get offlineDevices => _devices.where((d) => !d.isOnline && !d.isPending).toList();
  
  bool get isLoading => _isLoading;
  bool get isLinking => _isLinking;
  String? get errorMessage => _errorMessage;
  DeviceModel? get selectedDevice => _selectedDevice;
  int get deviceCount => _devices.length;
  int get onlineCount => onlineDevices.length;

  // Load devices from API
  Future<void> loadDevices() async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      // TODO: Call API to get devices
      // final response = await _apiService.getDevices();
      // _devices = (response['data'] as List)
      //     .map((json) => DeviceModel.fromJson(json))
      //     .toList();
      
      // Mock data for testing
      await Future.delayed(const Duration(seconds: 1));
      _devices = [
        DeviceModel(
          id: '1',
          name: 'MacBook Pro',
          type: 'laptop',
          status: 'online',
          lastActive: DateTime.now(),
          ipAddress: '192.168.1.100',
          osVersion: 'macOS 14.0',
          isCurrentDevice: true,
        ),
        DeviceModel(
          id: '2',
          name: 'iPhone 15 Pro',
          type: 'mobile',
          status: 'online',
          lastActive: DateTime.now(),
          ipAddress: '192.168.1.101',
          osVersion: 'iOS 17.0',
        ),
        DeviceModel(
          id: '3',
          name: 'Dell XPS',
          type: 'laptop',
          status: 'offline',
          lastActive: DateTime.now().subtract(const Duration(days: 1)),
          ipAddress: '192.168.1.102',
          osVersion: 'Windows 11',
        ),
        DeviceModel(
          id: '4',
          name: 'iPad Air',
          type: 'tablet',
          status: 'pending',
          lastActive: DateTime.now(),
          osVersion: 'iPadOS 17.0',
        ),
      ];
    } catch (e) {
      _errorMessage = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  // Link new device
  Future<bool> linkDevice(String deviceName, String deviceType) async {
    _isLinking = true;
    _errorMessage = null;
    notifyListeners();

    try {
      // TODO: Call API to link device
      // final response = await _apiService.linkDevice({
      //   'name': deviceName,
      //   'type': deviceType,
      // });
      
      await Future.delayed(const Duration(seconds: 1));
      
      // Add new device to list
      final newDevice = DeviceModel(
        id: DateTime.now().millisecondsSinceEpoch.toString(),
        name: deviceName,
        type: deviceType,
        status: 'pending',
        lastActive: DateTime.now(),
      );
      
      _devices.add(newDevice);
      _isLinking = false;
      notifyListeners();
      return true;
    } catch (e) {
      _errorMessage = e.toString();
      _isLinking = false;
      notifyListeners();
      return false;
    }
  }

  // Unlink device
  Future<bool> unlinkDevice(String deviceId) async {
    _isLoading = true;
    notifyListeners();

    try {
      // TODO: Call API to unlink device
      // await _apiService.unlinkDevice(deviceId);
      
      await Future.delayed(const Duration(seconds: 1));
      
      _devices.removeWhere((device) => device.id == deviceId);
      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      _errorMessage = e.toString();
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  // Update device status
  Future<void> updateDeviceStatus(String deviceId, String status) async {
    final index = _devices.indexWhere((d) => d.id == deviceId);
    if (index != -1) {
      _devices[index] = DeviceModel(
        id: _devices[index].id,
        name: _devices[index].name,
        type: _devices[index].type,
        status: status,
        lastActive: DateTime.now(),
        ipAddress: _devices[index].ipAddress,
        osVersion: _devices[index].osVersion,
        isCurrentDevice: _devices[index].isCurrentDevice,
      );
      notifyListeners();
    }
  }

  // Select device
  void selectDevice(DeviceModel device) {
    _selectedDevice = device;
    notifyListeners();
  }

  void clearSelectedDevice() {
    _selectedDevice = null;
    notifyListeners();
  }

  // Get device by ID
  DeviceModel? getDeviceById(String id) {
    try {
      return _devices.firstWhere((device) => device.id == id);
    } catch (e) {
      return null;
    }
  }

  // Refresh devices
  Future<void> refreshDevices() async {
    await loadDevices();
  }

  // Clear error
  void clearError() {
    _errorMessage = null;
    notifyListeners();
  }

  // Generate QR code data for device linking
  String generateQrCodeData(String deviceName) {
    return 'DEVICE_LINK:${DateTime.now().millisecondsSinceEpoch}:$deviceName';
  }

  // Verify device link
  Future<bool> verifyDeviceLink(String code) async {
    _isLoading = true;
    notifyListeners();

    try {
      await Future.delayed(const Duration(seconds: 1));
      // TODO: Verify code with API
      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      _errorMessage = e.toString();
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }
}