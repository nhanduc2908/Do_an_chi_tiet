class StorageConstants {
  // Shared Preferences Keys
  static const String prefAuthToken = 'auth_token';
  static const String prefRefreshToken = 'refresh_token';
  static const String prefUserData = 'user_data';
  static const String prefThemeMode = 'theme_mode';
  static const String prefLanguage = 'language';
  static const String prefNotificationsEnabled = 'notifications_enabled';
  static const String prefBiometricEnabled = 'biometric_enabled';
  static const String prefLastSync = 'last_sync';
  static const String prefDeviceId = 'device_id';
  static const String prefDeviceName = 'device_name';
  
  // Secure Storage Keys
  static const String secureAuthToken = 'secure_auth_token';
  static const String secureRefreshToken = 'secure_refresh_token';
  static const String secureUserPassword = 'secure_user_password';
  static const String secureBiometricKey = 'secure_biometric_key';
  static const String secureEncryptionKey = 'secure_encryption_key';
  
  // Hive Box Names
  static const String hiveUsers = 'users';
  static const String hiveDevices = 'devices';
  static const String hiveEvaluations = 'evaluations';
  static const String hiveReports = 'reports';
  static const String hiveNotifications = 'notifications';
  static const String hiveCache = 'cache';
  static const String hiveSettings = 'settings';
  
  // Database Names
  static const String dbName = 'security_evaluation.db';
  static const int dbVersion = 1;
  
  // File Storage
  static const String storageReports = 'reports';
  static const String storageExports = 'exports';
  static const String storageDownloads = 'downloads';
  static const String storageCache = 'cache';
  static const String storageLogs = 'logs';
  
  // Cache durations
  static const int cacheShort = 300; // 5 minutes
  static const int cacheMedium = 3600; // 1 hour
  static const int cacheLong = 86400; // 24 hours
  static const int cacheWeek = 604800; // 7 days
}