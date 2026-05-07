import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

import '../utils/constants.dart';

class ApiService {
  final String baseUrl = AppConstants.apiBaseUrl;
  final FlutterSecureStorage _secureStorage = const FlutterSecureStorage();
  
  Future<Map<String, String>> _getHeaders() async {
    final token = await _secureStorage.read(key: 'auth_token');
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    };
  }

  Future<dynamic> post(String endpoint, Map<String, dynamic> data) async {
    final url = Uri.parse('$baseUrl/$endpoint');
    final headers = await _getHeaders();
    
    final response = await http.post(
      url,
      headers: headers,
      body: jsonEncode(data),
    );
    
    return _handleResponse(response);
  }

  Future<dynamic> get(String endpoint) async {
    final url = Uri.parse('$baseUrl/$endpoint');
    final headers = await _getHeaders();
    
    final response = await http.get(url, headers: headers);
    
    return _handleResponse(response);
  }

  Future<dynamic> put(String endpoint, Map<String, dynamic> data) async {
    final url = Uri.parse('$baseUrl/$endpoint');
    final headers = await _getHeaders();
    
    final response = await http.put(
      url,
      headers: headers,
      body: jsonEncode(data),
    );
    
    return _handleResponse(response);
  }

  Future<dynamic> delete(String endpoint) async {
    final url = Uri.parse('$baseUrl/$endpoint');
    final headers = await _getHeaders();
    
    final response = await http.delete(url, headers: headers);
    
    return _handleResponse(response);
  }

  dynamic _handleResponse(http.Response response) {
    final data = jsonDecode(response.body);
    
    if (response.statusCode >= 200 && response.statusCode < 300) {
      return data;
    } else {
      throw data['message'] ?? 'Something went wrong';
    }
  }

  // Auth endpoints
  Future<dynamic> login(String email, String password) async {
    return post('login', {'email': email, 'password': password});
  }

  Future<dynamic> register(Map<String, dynamic> userData) async {
    return post('register', userData);
  }

  Future<dynamic> logout() async {
    return post('logout', {});
  }

  Future<dynamic> getCurrentUser() async {
    return get('user');
  }

  // Device endpoints
  Future<dynamic> getDevices() async {
    return get('devices');
  }

  Future<dynamic> linkDevice(Map<String, dynamic> deviceData) async {
    return post('devices/link', deviceData);
  }

  Future<dynamic> unlinkDevice(String deviceId) async {
    return delete('devices/$deviceId');
  }

  // Evaluation endpoints
  Future<dynamic> getEvaluations() async {
    return get('evaluations');
  }

  Future<dynamic> createEvaluation(Map<String, dynamic> data) async {
    return post('evaluations', data);
  }

  // Report endpoints
  Future<dynamic> getReports() async {
    return get('reports');
  }

  Future<dynamic> generateReport(Map<String, dynamic> params) async {
    return post('reports/generate', params);
  }
}