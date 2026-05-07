class AppConstants {
  static const String appName = 'Security Evaluation System';
  static const String appVersion = '1.0.0';
  
  // API
  static const String apiBaseUrl = 'http://localhost:8000/api';
  static const String wsBaseUrl = 'ws://localhost:8000/ws';
  
  // Storage keys
  static const String keyAuthToken = 'auth_token';
  static const String keyUserData = 'user_data';
  static const String keyThemeMode = 'theme_mode';
  
  // Pagination
  static const int defaultPageSize = 20;
  
  // Timeouts
  static const int connectionTimeout = 30000;
  static const int receiveTimeout = 30000;
  
  // Cache
  static const int cacheDuration = 3600; // seconds
}

class AppColors {
  static const primary = Color(0xFF3B82F6);
  static const secondary = Color(0xFF8B5CF6);
  static const success = Color(0xFF10B981);
  static const warning = Color(0xFFF59E0B);
  static const error = Color(0xFFEF4444);
  static const info = Color(0xFF06B6D4);
  
  static const background = Color(0xFFF9FAFB);
  static const surface = Color(0xFFFFFFFF);
  static const text = Color(0xFF111827);
  static const textSecondary = Color(0xFF6B7280);
}