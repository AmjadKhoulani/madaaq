import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';

class ApiClient {
  // static const String baseUrl = 'http://10.0.2.2:8000/api/v1/vendor'; // Android Emulator
  static const String baseUrl = 'https://madaaq.com/api/v1/vendor'; // Production
  // static const String baseUrl = 'http://127.0.0.1:8000/api/v1/vendor'; // iOS Simulator
  
  static final Dio _dio = Dio(BaseOptions(
    baseUrl: baseUrl,
    connectTimeout: const Duration(seconds: 10),
    receiveTimeout: const Duration(seconds: 10),
    headers: {'Accept': 'application/json'},
  ));

  static Dio get dio => _dio;

  static Future<void> setToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('token', token);
    _dio.options.headers['Authorization'] = 'Bearer $token';
  }

  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('token');
  }
  
  static Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('token');
    _dio.options.headers.remove('Authorization');
  }
}
