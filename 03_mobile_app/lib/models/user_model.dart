class UserModel {
  final String id;
  final String name;
  final String email;
  final String? phone;
  final String? avatar;
  final List<String> roles;
  final List<String> permissions;
  final DateTime createdAt;
  final DateTime? lastLoginAt;

  UserModel({
    required this.id,
    required this.name,
    required this.email,
    this.phone,
    this.avatar,
    this.roles = const [],
    this.permissions = const [],
    required this.createdAt,
    this.lastLoginAt,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'].toString(),
      name: json['name'] ?? '',
      email: json['email'] ?? '',
      phone: json['phone'],
      avatar: json['avatar'],
      roles: List<String>.from(json['roles'] ?? []),
      permissions: List<String>.from(json['permissions'] ?? []),
      createdAt: DateTime.parse(json['created_at'] ?? DateTime.now().toIso8601String()),
      lastLoginAt: json['last_login_at'] != null 
          ? DateTime.parse(json['last_login_at']) 
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'phone': phone,
      'avatar': avatar,
      'roles': roles,
      'permissions': permissions,
      'created_at': createdAt.toIso8601String(),
      'last_login_at': lastLoginAt?.toIso8601String(),
    };
  }

  bool hasPermission(String permission) {
    return permissions.contains(permission);
  }

  bool hasRole(String role) {
    return roles.contains(role);
  }

  bool get isAdmin => hasRole('admin') || hasRole('administrator');
  bool get isManager => hasRole('manager');
  bool get isCiso => hasRole('ciso');
  
  String get displayName {
    if (name.isNotEmpty) return name;
    return email.split('@').first;
  }
  
  String get avatarLetter {
    if (name.isNotEmpty) return name[0].toUpperCase();
    return email[0].toUpperCase();
  }
  
  String get roleDisplay {
    if (isAdmin) return 'Quản trị viên';
    if (isCiso) return 'Giám đốc an ninh';
    if (isManager) return 'Quản lý';
    if (roles.isNotEmpty) return roles.first;
    return 'Người dùng';
  }
}