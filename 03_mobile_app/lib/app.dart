import 'package:flutter/material.dart';

class AppRoutes {
  static const String login = '/login';
  static const String register = '/register';
  static const String forgotPassword = '/forgot-password';
  static const String verifyOtp = '/verify-otp';
  static const String dashboard = '/dashboard';
  static const String devices = '/devices';
  static const String linkDevice = '/link-device';
  static const String mirror = '/mirror';
  static const String evaluations = '/evaluations';
  static const String reports = '/reports';
  static const String profile = '/profile';
  
  static Map<String, WidgetBuilder> get routes {
    return {
      login: (context) => const LoginScreen(),
      register: (context) => const RegisterScreen(),
      forgotPassword: (context) => const ForgotPasswordScreen(),
      verifyOtp: (context) => const VerifyOtpScreen(),
      dashboard: (context) => const DashboardScreen(),
      devices: (context) => const DevicesScreen(),
      linkDevice: (context) => const LinkDeviceScreen(),
      mirror: (context) => const MirrorScreen(),
      evaluations: (context) => const EvaluationsScreen(),
      reports: (context) => const ReportsScreen(),
      profile: (context) => const ProfileScreen(),
    };
  }
}