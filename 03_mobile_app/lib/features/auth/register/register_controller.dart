import 'package:flutter/material.dart';
import '../../../services/api_service.dart';

class RegisterController extends ChangeNotifier {
  final ApiService _apiService = ApiService();
  
  bool _isLoading = false;
  List<String> _errors = [];

  bool get isLoading => _isLoading;
  List<String> get errors => _errors;

  Future<bool> register(Map<String, dynamic> userData) async {
    _isLoading = true;
    _errors = [];
    notifyListeners();

    try {
      await _apiService.register(userData);
      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      _errors = [e.toString()];
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  void clearErrors() {
    _errors = [];
    notifyListeners();
  }
}