class PermissionUtils {
  static const Map<String, List<String>> permissionGroups = {
    'user_management': ['view_users', 'create_users', 'edit_users', 'delete_users'],
    'role_management': ['view_roles', 'create_roles', 'edit_roles', 'delete_roles'],
    'reporting': ['view_reports', 'create_reports', 'export_reports'],
    'security': ['view_security', 'manage_security', 'view_alerts'],
    'audit': ['view_audit_logs', 'export_audit_logs'],
    'system': ['view_system', 'manage_system', 'view_logs'],
    'data': ['view_data', 'import_data', 'export_data', 'delete_data'],
    'evaluation': ['view_evaluations', 'create_evaluations', 'edit_evaluations', 'delete_evaluations'],
  };

  static bool hasPermission(List<String> userPermissions, String permission) {
    return userPermissions.contains(permission);
  }

  static bool hasAnyPermission(List<String> userPermissions, List<String> permissions) {
    for (var permission in permissions) {
      if (userPermissions.contains(permission)) {
        return true;
      }
    }
    return false;
  }

  static bool hasAllPermissions(List<String> userPermissions, List<String> permissions) {
    for (var permission in permissions) {
      if (!userPermissions.contains(permission)) {
        return false;
      }
    }
    return true;
  }

  static bool hasGroupPermission(List<String> userPermissions, String group) {
    final permissions = permissionGroups[group] ?? [];
    return hasAnyPermission(userPermissions, permissions);
  }
}