class ApiConstants {
  // Base URLs
  static const String baseUrl = 'http://localhost:8000/api';
  static const String wsUrl = 'ws://localhost:8000/ws';
  static const String imageUrl = 'http://localhost:8000/storage';
  
  // Auth endpoints
  static const String login = '/login';
  static const String register = '/register';
  static const String logout = '/logout';
  static const String refreshToken = '/refresh-token';
  static const String forgotPassword = '/forgot-password';
  static const String resetPassword = '/reset-password';
  static const String verifyOtp = '/verify-otp';
  static const String biometricLogin = '/biometric-login';
  
  // User endpoints
  static const String user = '/user';
  static const String users = '/users';
  static const String updateProfile = '/update-profile';
  static const String changePassword = '/change-password';
  static const String userActivity = '/user-activity';
  
  // Role endpoints
  static const String roles = '/roles';
  static const String permissions = '/permissions';
  static const String assignRole = '/assign-role';
  
  // Device endpoints
  static const String devices = '/devices';
  static const String linkDevice = '/devices/link';
  static const String unlinkDevice = '/devices/unlink';
  static const String deviceActivity = '/devices/activity';
  static const String screenMirror = '/devices/mirror';
  
  // Evaluation endpoints
  static const String evaluations = '/evaluations';
  static const String createEvaluation = '/evaluations/create';
  static const String updateEvaluation = '/evaluations/update';
  static const String deleteEvaluation = '/evaluations/delete';
  static const String evaluationDetails = '/evaluations/details';
  static const String evaluationStats = '/evaluations/stats';
  
  // Report endpoints
  static const String reports = '/reports';
  static const String generateReport = '/reports/generate';
  static const String exportReport = '/reports/export';
  static const String scheduleReport = '/reports/schedule';
  static const String reportTemplates = '/reports/templates';
  
  // Dashboard endpoints
  static const String dashboard = '/dashboard';
  static const String dashboardStats = '/dashboard/stats';
  static const String dashboardWidgets = '/dashboard/widgets';
  static const String refreshDashboard = '/dashboard/refresh';
  
  // Audit endpoints
  static const String auditLogs = '/audit/logs';
  static const String auditSummary = '/audit/summary';
  static const String exportAudit = '/audit/export';
  
  // Notification endpoints
  static const String notifications = '/notifications';
  static const String markAsRead = '/notifications/mark-read';
  static const String markAllAsRead = '/notifications/mark-all-read';
  static const String notificationSettings = '/notifications/settings';
  
  // System endpoints
  static const String systemHealth = '/system/health';
  static const String systemStats = '/system/stats';
  static const String systemLogs = '/system/logs';
  static const String backup = '/system/backup';
  static const String restore = '/system/restore';
  
  // WebSocket events
  static const String wsConnect = 'connect';
  static const String wsDisconnect = 'disconnect';
  static const String wsMirrorStart = 'mirror_start';
  static const String wsMirrorStop = 'mirror_stop';
  static const String wsMirrorFrame = 'mirror_frame';
  static const String wsNotification = 'notification';
  static const String wsAlert = 'alert';
  static const String wsPing = 'ping';
  static const String wsPong = 'pong';
  
  // Headers
  static const String authorization = 'Authorization';
  static const String bearer = 'Bearer';
  static const String contentType = 'Content-Type';
  static const String accept = 'Accept';
  static const String applicationJson = 'application/json';
  static const String multipartFormData = 'multipart/form-data';
  
  // Query parameters
  static const String page = 'page';
  static const String limit = 'limit';
  static const String search = 'search';
  static const String sort = 'sort';
  static const String order = 'order';
  static const String filter = 'filter';
  static const String from = 'from';
  static const String to = 'to';
  
  // Timeouts
  static const int connectionTimeout = 30000;
  static const int receiveTimeout = 30000;
  static const int sendTimeout = 30000;
  
  // Cache
  static const int cacheDuration = 3600; // seconds
  static const int staleCacheDuration = 86400; // 24 hours
}