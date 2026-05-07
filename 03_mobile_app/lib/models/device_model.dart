class DeviceModel {
  final String id;
  final String name;
  final String type;
  final String status;
  final DateTime lastActive;
  final String? ipAddress;
  final String? osVersion;
  final bool isCurrentDevice;

  DeviceModel({
    required this.id,
    required this.name,
    required this.type,
    required this.status,
    required this.lastActive,
    this.ipAddress,
    this.osVersion,
    this.isCurrentDevice = false,
  });

  factory DeviceModel.fromJson(Map<String, dynamic> json) {
    return DeviceModel(
      id: json['id'].toString(),
      name: json['name'] ?? 'Unknown Device',
      type: json['type'] ?? 'unknown',
      status: json['status'] ?? 'offline',
      lastActive: DateTime.parse(json['last_active'] ?? DateTime.now().toIso8601String()),
      ipAddress: json['ip_address'],
      osVersion: json['os_version'],
      isCurrentDevice: json['is_current_device'] ?? false,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'type': type,
      'status': status,
      'last_active': lastActive.toIso8601String(),
      'ip_address': ipAddress,
      'os_version': osVersion,
      'is_current_device': isCurrentDevice,
    };
  }

  bool get isOnline => status == 'online';
  bool get isActive => status == 'active' || status == 'online';
  bool get isPending => status == 'pending';
  
  String get statusText {
    switch (status) {
      case 'online': return 'Đang hoạt động';
      case 'offline': return 'Không hoạt động';
      case 'pending': return 'Chờ duyệt';
      default: return status;
    }
  }
  
  Color get statusColor {
    switch (status) {
      case 'online': return Colors.green;
      case 'offline': return Colors.grey;
      case 'pending': return Colors.orange;
      default: return Colors.grey;
    }
  }
  
  String get typeIcon {
    switch (type) {
      case 'mobile': return '📱';
      case 'laptop': return '💻';
      case 'tablet': return '📟';
      default: return '🖥️';
    }
  }
  
  String get formattedLastActive {
    final now = DateTime.now();
    final diff = now.difference(lastActive);
    
    if (diff.inDays > 0) {
      return '${diff.inDays} ngày trước';
    } else if (diff.inHours > 0) {
      return '${diff.inHours} giờ trước';
    } else if (diff.inMinutes > 0) {
      return '${diff.inMinutes} phút trước';
    } else {
      return 'Vừa xong';
    }
  }
}