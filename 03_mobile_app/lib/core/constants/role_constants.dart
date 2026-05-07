enum UserRole {
  admin,      // 👑 Administrator
  dev,        // 💻 Developer
  ba,         // 📊 Business Analyst
  da,         // 🗄️ Data Analyst
  hr,         // 👥 Human Resources
  qa,         // 🧪 QA Engineer
  secops,     // 🛡️ Security Operations
  auditor,    // 📋 Auditor
  manager,    // 📈 Manager
  ciso;       // 🎯 CISO

  String get displayName {
    switch (this) {
      case UserRole.admin:
        return 'Quản trị viên';
      case UserRole.dev:
        return 'Nhà phát triển';
      case UserRole.ba:
        return 'Chuyên viên phân tích';
      case UserRole.da:
        return 'Chuyên viên dữ liệu';
      case UserRole.hr:
        return 'Quản lý nhân sự';
      case UserRole.qa:
        return 'Kiểm định chất lượng';
      case UserRole.secops:
        return 'Vận hành bảo mật';
      case UserRole.auditor:
        return 'Kiểm toán viên';
      case UserRole.manager:
        return 'Quản lý';
      case UserRole.ciso:
        return 'Giám đốc an ninh';
    }
  }

  String get icon {
    switch (this) {
      case UserRole.admin:
        return '👑';
      case UserRole.dev:
        return '💻';
      case UserRole.ba:
        return '📊';
      case UserRole.da:
        return '🗄️';
      case UserRole.hr:
        return '👥';
      case UserRole.qa:
        return '🧪';
      case UserRole.secops:
        return '🛡️';
      case UserRole.auditor:
        return '📋';
      case UserRole.manager:
        return '📈';
      case UserRole.ciso:
        return '🎯';
    }
  }

  int get priority {
    switch (this) {
      case UserRole.admin:
        return 100;
      case UserRole.ciso:
        return 95;
      case UserRole.manager:
        return 85;
      case UserRole.secops:
        return 80;
      case UserRole.auditor:
        return 75;
      case UserRole.dev:
        return 65;
      case UserRole.ba:
        return 60;
      case UserRole.da:
        return 55;
      case UserRole.qa:
        return 50;
      case UserRole.hr:
        return 40;
    }
  }

  String get colorCode {
    switch (this) {
      case UserRole.admin:
        return '#1E3A8A';
      case UserRole.dev:
        return '#0F172A';
      case UserRole.ba:
        return '#0D9488';
      case UserRole.da:
        return '#4F46E5';
      case UserRole.hr:
        return '#DB2777';
      case UserRole.qa:
        return '#059669';
      case UserRole.secops:
        return '#DC2626';
      case UserRole.auditor:
        return '#D97706';
      case UserRole.manager:
        return '#7C3AED';
      case UserRole.ciso:
        return '#2563EB';
    }
  }

  List<String> get permissions {
    switch (this) {
      case UserRole.admin:
        return [
          'view_all', 'edit_all', 'delete_all', 'manage_users',
          'manage_roles', 'view_logs', 'system_settings', 'backup_restore',
          'manage_security', 'view_audit'
        ];
      case UserRole.ciso:
        return [
          'view_security', 'manage_policies', 'view_audit_logs',
          'security_settings', 'risk_assessment', 'incident_response',
          'view_threats', 'manage_compliance'
        ];
      case UserRole.manager:
        return [
          'view_team', 'manage_team', 'view_reports', 'approve_requests',
          'view_projects', 'manage_tasks', 'view_kpi', 'manage_budget'
        ];
      case UserRole.secops:
        return [
          'view_security', 'manage_alerts', 'view_threats',
          'incident_response', 'security_scan', 'view_logs',
          'manage_firewall', 'view_intrusions'
        ];
      case UserRole.auditor:
        return [
          'view_audit_logs', 'view_compliance', 'generate_reports',
          'view_system_logs', 'view_user_activity', 'export_audit'
        ];
      case UserRole.dev:
        return [
          'view_code', 'deploy', 'view_logs', 'manage_api_keys',
          'view_performance', 'manage_environment', 'view_deployments'
        ];
      case UserRole.ba:
        return [
          'view_analytics', 'view_reports', 'export_data',
          'view_kpi', 'create_dashboards', 'view_trends'
        ];
      case UserRole.da:
        return [
          'view_data', 'export_data', 'run_queries',
          'view_analytics', 'create_reports', 'view_datasets'
        ];
      case UserRole.qa:
        return [
          'view_tests', 'run_tests', 'view_bugs',
          'create_test_cases', 'view_coverage', 'manage_test_suites'
        ];
      case UserRole.hr:
        return [
          'view_employees', 'manage_leave', 'view_attendance',
          'manage_recruitment', 'view_payroll', 'manage_contracts'
        ];
    }
  }
}