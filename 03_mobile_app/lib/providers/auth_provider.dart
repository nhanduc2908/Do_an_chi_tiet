import 'package:flutter/material.dart';

class AuthProvider extends ChangeNotifier {
  bool _isLoading = false;
  bool _isAuthenticated = false;
  String? _errorMessage;
  User? _user;

  bool get isLoading => _isLoading;
  bool get isAuthenticated => _isAuthenticated;
  String? get errorMessage => _errorMessage;
  User? get user => _user;

  Future<bool> login(String email, String password) async {
    _isLoading = true;
    notifyListeners();

    await Future.delayed(const Duration(seconds: 1));
    
    if (email == 'admin@example.com' && password == '123456') {
      _isAuthenticated = true;
      _errorMessage = null;
      _user = User(
        id: '1',
        name: 'Administrator',
        email: email,
        roles: ['admin'],
      );
      _isLoading = false;
      notifyListeners();
      return true;
    } else {
      _isAuthenticated = false;
      _errorMessage = 'Sai email hoặc mật khẩu';
      _user = null;
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<bool> biometricLogin() async {
    _isLoading = true;
    notifyListeners();
    
    await Future.delayed(const Duration(seconds: 1));
    
    _isAuthenticated = true;
    _user = User(
      id: '1',
      name: 'Administrator',
      email: 'admin@example.com',
      roles: ['admin'],
    );
    _isLoading = false;
    notifyListeners();
    return true;
  }

  Future<void> logout() async {
    _isLoading = true;
    notifyListeners();
    
    await Future.delayed(const Duration(seconds: 1));
    
    _isAuthenticated = false;
    _user = null;
    _isLoading = false;
    notifyListeners();
  }
}

class User {
  final String id;
  final String name;
  final String email;
  final List<String> roles;

  User({
    required this.id,
    required this.name,
    required this.email,
    required this.roles,
  });
}