class AppConstants {
  static const String appName = 'Security Evaluation System';
  static const String appVersion = '2.0.0';
  static const String appPackage = 'com.security.evaluation';
  
  // App settings
  static const int defaultPageSize = 20;
  static const int maxRetryAttempts = 3;
  static const int retryDelaySeconds = 2;
  
  // UI Constants
  static const double defaultPadding = 16.0;
  static const double defaultBorderRadius = 12.0;
  static const double defaultIconSize = 24.0;
  static const double defaultAvatarSize = 48.0;
  
  // Animation durations
  static const int animationFast = 150;
  static const int animationNormal = 300;
  static const int animationSlow = 500;
  
  // Form validation
  static const int minPasswordLength = 8;
  static const int maxNameLength = 100;
  static const int maxEmailLength = 255;
  static const int maxPhoneLength = 15;
  
  // File upload
  static const int maxFileSize = 10 * 1024 * 1024; // 10MB
  static const List<String> allowedImageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
  static const List<String> allowedDocumentTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
  
  // Date formats
  static const String dateFormat = 'dd/MM/yyyy';
  static const String dateTimeFormat = 'dd/MM/yyyy HH:mm:ss';
  static const String timeFormat = 'HH:mm:ss';
  static const String apiDateFormat = 'yyyy-MM-dd HH:mm:ss';
  
  // Deep links
  static const String deepLinkScheme = 'securityapp';
  static const String deepLinkHost = 'app.security.com';
  
  // Share messages
  static const String shareReportMessage = 'Kiểm tra báo cáo an toàn của tôi trên Security Evaluation System';
}