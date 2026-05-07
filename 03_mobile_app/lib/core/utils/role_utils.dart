import 'package:flutter/material.dart';

import '../constants/role_constants.dart';

class RoleUtils {
  // Get current user's role from list of roles
  static UserRole? getCurrentRole(List<String> roles) {
    for (var role in roles) {
      switch (role.toLowerCase()) {
        case 'admin':
        case 'administrator':
          return UserRole.admin;
        case 'dev':
        case 'developer':
          return UserRole.dev;
        case 'ba':
        case 'business_analyst':
          return UserRole.ba;
        case 'da':
        case 'data_analyst':
          return UserRole.da;
        case 'hr':
        case 'human_resources':
          return UserRole.hr;
        case 'qa':
        case 'quality_assurance':
          return UserRole.qa;
        case 'secops':
        case 'security_operations':
          return UserRole.secops;
        case 'auditor':
          return UserRole.auditor;
        case 'manager':
          return UserRole.manager;
        case 'ciso':
          return UserRole.ciso;
        default:
          return null;
      }
    }
    return null;
  }

  // Check if user has specific role
  static bool hasRole(List<String> userRoles, UserRole targetRole) {
    final currentRole = getCurrentRole(userRoles);
    return currentRole == targetRole;
  }

  // Check if user has any of the specified roles
  static bool hasAnyRole(List<String> userRoles, List<UserRole> targetRoles) {
    final currentRole = getCurrentRole(userRoles);
    return targetRoles.contains(currentRole);
  }

  // Check permission
  static bool hasPermission(List<String> userRoles, String permission) {
    final currentRole = getCurrentRole(userRoles);
    if (currentRole == null) return false;
    return currentRole.permissions.contains(permission);
  }

  // Check multiple permissions
  static bool hasAllPermissions(List<String> userRoles, List<String> permissions) {
    for (var permission in permissions) {
      if (!hasPermission(userRoles, permission)) {
        return false;
      }
    }
    return true;
  }

  // Get role priority
  static int getRolePriority(List<String> userRoles) {
    final currentRole = getCurrentRole(userRoles);
    return currentRole?.priority ?? 0;
  }

  // Check if user is admin
  static bool isAdmin(List<String> userRoles) {
    return hasRole(userRoles, UserRole.admin);
  }

  // Check if user is CISO
  static bool isCiso(List<String> userRoles) {
    return hasRole(userRoles, UserRole.ciso);
  }

  // Check if user is manager or above
  static bool isManagerOrAbove(List<String> userRoles) {
    final priority = getRolePriority(userRoles);
    return priority >= UserRole.manager.priority;
  }

  // Check if user is executive (CISO or Admin)
  static bool isExecutive(List<String> userRoles) {
    return isAdmin(userRoles) || isCiso(userRoles);
  }

  // Get accessible modules based on role
  static List<String> getAccessibleModules(List<String> userRoles) {
    final role = getCurrentRole(userRoles);
    
    switch (role) {
      case UserRole.admin:
        return [
          'dashboard', 'users', 'devices', 'evaluations', 
          'reports', 'audit', 'settings', 'backup', 
          'security', 'logs', 'monitoring'
        ];
      case UserRole.ciso:
        return [
          'dashboard', 'security', 'audit', 'reports', 
          'incidents', 'policies', 'risk_assessment',
          'compliance', 'threats', 'vulnerabilities'
        ];
      case UserRole.manager:
        return [
          'dashboard', 'team', 'projects', 'reports', 
          'tasks', 'kpi', 'budget', 'schedule'
        ];
      case UserRole.secops:
        return [
          'dashboard', 'security', 'alerts', 'incidents', 
          'threats', 'logs', 'firewall', 'intrusion'
        ];
      case UserRole.auditor:
        return [
          'dashboard', 'audit', 'compliance', 'reports', 
          'logs', 'user_activity', 'system_logs'
        ];
      case UserRole.dev:
        return [
          'dashboard', 'development', 'deployment', 'logs', 
          'apis', 'performance', 'environment'
        ];
      case UserRole.ba:
        return [
          'dashboard', 'analytics', 'reports', 'kpi', 
          'insights', 'trends', 'forecast'
        ];
      case UserRole.da:
        return [
          'dashboard', 'data', 'analytics', 'reports', 
          'queries', 'datasets', 'visualization'
        ];
      case UserRole.qa:
        return [
          'dashboard', 'testing', 'bugs', 'test_cases', 
          'coverage', 'suites', 'releases'
        ];
      case UserRole.hr:
        return [
          'dashboard', 'employees', 'attendance', 'leave', 
          'recruitment', 'payroll', 'contracts', 'training'
        ];
      default:
        return ['dashboard', 'profile', 'notifications'];
    }
  }

  // Get role-specific dashboard widgets
  static List<DashboardWidget> getDashboardWidgets(List<String> userRoles) {
    final role = getCurrentRole(userRoles);
    
    switch (role) {
      case UserRole.admin:
        return [
          DashboardWidget('system_health', 'Sức khỏe hệ thống', Icons.health_and_safety, Colors.blue),
          DashboardWidget('user_activity', 'Hoạt động người dùng', Icons.people, Colors.green),
          DashboardWidget('security_alerts', 'Cảnh báo bảo mật', Icons.warning, Colors.red),
          DashboardWidget('system_logs', 'Nhật ký hệ thống', Icons.receipt, Colors.orange),
          DashboardWidget('storage', 'Dung lượng lưu trữ', Icons.storage, Colors.purple),
          DashboardWidget('backup', 'Sao lưu dữ liệu', Icons.backup, Colors.teal),
        ];
      case UserRole.ciso:
        return [
          DashboardWidget('risk_score', 'Điểm rủi ro', Icons.risk_assessment, Colors.red),
          DashboardWidget('incidents', 'Sự cố', Icons.incident, Colors.orange),
          DashboardWidget('compliance', 'Tuân thủ', Icons.verified, Colors.green),
          DashboardWidget('security_stats', 'Thống kê bảo mật', Icons.security, Colors.blue),
          DashboardWidget('threats', 'Mối đe dọa', Icons.bug_report, Colors.purple),
        ];
      case UserRole.manager:
        return [
          DashboardWidget('team_performance', 'Hiệu suất nhóm', Icons.analytics, Colors.blue),
          DashboardWidget('project_status', 'Tiến độ dự án', Icons.project, Colors.green),
          DashboardWidget('tasks', 'Công việc', Icons.task, Colors.orange),
          DashboardWidget('kpi', 'KPI', Icons.trending_up, Colors.purple),
          DashboardWidget('budget', 'Ngân sách', Icons.attach_money, Colors.teal),
        ];
      case UserRole.secops:
        return [
          DashboardWidget('alerts', 'Cảnh báo', Icons.notifications_active, Colors.red),
          DashboardWidget('threats', 'Mối đe dọa', Icons.bug_report, Colors.orange),
          DashboardWidget('firewall', 'Tường lửa', Icons.firewall, Colors.blue),
          DashboardWidget('logs', 'Nhật ký', Icons.receipt, Colors.green),
        ];
      case UserRole.auditor:
        return [
          DashboardWidget('audit_trail', 'Vết kiểm toán', Icons.timeline, Colors.blue),
          DashboardWidget('compliance', 'Tuân thủ', Icons.verified, Colors.green),
          DashboardWidget('reports', 'Báo cáo', Icons.report, Colors.orange),
          DashboardWidget('findings', 'Phát hiện', Icons.search, Colors.red),
        ];
      case UserRole.dev:
        return [
          DashboardWidget('commits', 'Commits', Icons.code, Colors.blue),
          DashboardWidget('deployments', 'Deployments', Icons.cloud_upload, Colors.green),
          DashboardWidget('bugs', 'Lỗi', Icons.bug_report, Colors.red),
          DashboardWidget('coverage', 'Test Coverage', Icons.health_and_safety, Colors.teal),
        ];
      case UserRole.ba:
        return [
          DashboardWidget('revenue', 'Doanh thu', Icons.trending_up, Colors.green),
          DashboardWidget('customers', 'Khách hàng', Icons.people, Colors.blue),
          DashboardWidget('conversion', 'Tỷ lệ chuyển đổi', Icons.percent, Colors.orange),
          DashboardWidget('kpi', 'KPI', Icons.analytics, Colors.purple),
        ];
      case UserRole.da:
        return [
          DashboardWidget('data_quality', 'Chất lượng dữ liệu', Icons.data_usage, Colors.blue),
          DashboardWidget('datasets', 'Datasets', Icons.storage, Colors.green),
          DashboardWidget('queries', 'Truy vấn', Icons.search, Colors.orange),
          DashboardWidget('insights', 'Insights', Icons.lightbulb, Colors.yellow),
        ];
      case UserRole.qa:
        return [
          DashboardWidget('test_passed', 'Test Passed', Icons.check_circle, Colors.green),
          DashboardWidget('test_failed', 'Test Failed', Icons.cancel, Colors.red),
          DashboardWidget('coverage', 'Coverage', Icons.health_and_safety, Colors.blue),
          DashboardWidget('bugs', 'Bugs', Icons.bug_report, Colors.orange),
        ];
      case UserRole.hr:
        return [
          DashboardWidget('employees', 'Nhân viên', Icons.people, Colors.blue),
          DashboardWidget('attendance', 'Chấm công', Icons.access_time, Colors.green),
          DashboardWidget('leave', 'Nghỉ phép', Icons.beach_access, Colors.orange),
          DashboardWidget('recruitment', 'Tuyển dụng', Icons.person_add, Colors.purple),
        ];
      default:
        return [
          DashboardWidget('overview', 'Tổng quan', Icons.dashboard, Colors.blue),
          DashboardWidget('recent_activity', 'Hoạt động gần đây', Icons.history, Colors.green),
          DashboardWidget('notifications', 'Thông báo', Icons.notifications, Colors.orange),
        ];
    }
  }

  // Get role color
  static Color getRoleColor(List<String> userRoles) {
    final role = getCurrentRole(userRoles);
    if (role == null) return Colors.grey;
    
    switch (role) {
      case UserRole.admin:
        return const Color(0xFF1E3A8A);
      case UserRole.dev:
        return const Color(0xFF0F172A);
      case UserRole.ba:
        return const Color(0xFF0D9488);
      case UserRole.da:
        return const Color(0xFF4F46E5);
      case UserRole.hr:
        return const Color(0xFFDB2777);
      case UserRole.qa:
        return const Color(0xFF059669);
      case UserRole.secops:
        return const Color(0xFFDC2626);
      case UserRole.auditor:
        return const Color(0xFFD97706);
      case UserRole.manager:
        return const Color(0xFF7C3AED);
      case UserRole.ciso:
        return const Color(0xFF2563EB);
    }
  }

  // Get role badge style
  static Map<String, dynamic> getRoleBadgeStyle(List<String> userRoles) {
    final role = getCurrentRole(userRoles);
    if (role == null) {
      return {'bg': Colors.grey[200], 'text': Colors.grey[700]};
    }
    
    switch (role) {
      case UserRole.admin:
        return {'bg': const Color(0xFF1E3A8A), 'text': Colors.white};
      case UserRole.dev:
        return {'bg': const Color(0xFF0F172A), 'text': Colors.white};
      case UserRole.ba:
        return {'bg': const Color(0xFF0D9488), 'text': Colors.white};
      case UserRole.da:
        return {'bg': const Color(0xFF4F46E5), 'text': Colors.white};
      case UserRole.hr:
        return {'bg': const Color(0xFFDB2777), 'text': Colors.white};
      case UserRole.qa:
        return {'bg': const Color(0xFF059669), 'text': Colors.white};
      case UserRole.secops:
        return {'bg': const Color(0xFFDC2626), 'text': Colors.white};
      case UserRole.auditor:
        return {'bg': const Color(0xFFD97706), 'text': Colors.white};
      case UserRole.manager:
        return {'bg': const Color(0xFF7C3AED), 'text': Colors.white};
      case UserRole.ciso:
        return {'bg': const Color(0xFF2563EB), 'text': Colors.white};
    }
  }
}

class DashboardWidget {
  final String id;
  final String title;
  final IconData icon;
  final Color color;
  
  DashboardWidget(this.id, this.title, this.icon, this.color);
}