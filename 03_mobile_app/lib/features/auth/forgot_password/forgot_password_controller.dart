import 'package:flutter/material.dart';
import '../../../services/api_service.dart';

class ForgotPasswordController extends ChangeNotifier {
  final ApiService _apiService = ApiService();
  
  bool _isLoading = false;
  String? _message;
  String? _error;

  bool get isLoading => _isLoading;
  String? get message => _message;
  String? get error => _error;

  Future<bool> sendResetCode(String email) async {
    _isLoading = true;
    _message = null;
    _error = null;
    notifyListeners();

    try {
      final response = await _apiService.forgotPassword(email);
      _message = response['message'] ?? 'Mã OTP đã được gửi';
      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      _error = e.toString();
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }
}