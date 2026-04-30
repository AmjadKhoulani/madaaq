import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import '../../core/api_client.dart';
import '../home/home_screen.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _isLoading = false;

  Future<void> _login() async {
    setState(() => _isLoading = true);
    
    try {
      final response = await ApiClient.dio.post('/login', data: {
        'email': _emailController.text,
        'password': _passwordController.text,
      });

      final token = response.data['token'];
      await ApiClient.setToken(token);

      if (mounted) {
        Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => const HomeScreen()));
      }
    } on DioException catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(e.response?.data['message'] ?? 'فشل تسجيل الدخول')),
        );
      }
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [Color(0xFF0F172A), Color(0xFF1E1B4B), Color(0xFF312E81)], // Slate 900 -> Indigo 900
          ),
        ),
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(24.0),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                // Logo / Icon
                Container(
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(
                    color: Colors.white.withOpacity(0.1),
                    shape: BoxShape.circle,
                    border: Border.all(color: Colors.white.withOpacity(0.2)),
                  ),
                  child: const Icon(Icons.router_outlined, size: 60, color: Colors.white),
                ),
                
                const SizedBox(height: 24),
                
                const Text(
                  'SmartISP',
                  style: TextStyle(
                    fontSize: 32, 
                    fontWeight: FontWeight.bold, 
                    color: Colors.white,
                    letterSpacing: 1.5,
                  ),
                ),
                Text(
                  ' Vendor Portal',
                  style: TextStyle(
                    fontSize: 16, 
                    color: Colors.white.withOpacity(0.7),
                    letterSpacing: 1,
                  ),
                ),

                const SizedBox(height: 48),

                // Glass Form Container
                Container(
                  padding: const EdgeInsets.all(32),
                  decoration: BoxDecoration(
                    color: Colors.white.withOpacity(0.05),
                    borderRadius: BorderRadius.circular(24),
                    border: Border.all(color: Colors.white.withOpacity(0.1)),
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withOpacity(0.2),
                        blurRadius: 20,
                        offset: const Offset(0, 10),
                      ),
                    ],
                  ),
                  child: Column(
                    children: [
                      // Email Field
                      TextField(
                        controller: _emailController,
                        style: const TextStyle(color: Colors.white),
                        decoration: InputDecoration(
                          labelText: 'البريد الإلكتروني',
                          labelStyle: TextStyle(color: Colors.white.withOpacity(0.7)),
                          prefixIcon: Icon(Icons.email_outlined, color: Colors.indigo[300]),
                          enabledBorder: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(12),
                            borderSide: BorderSide(color: Colors.white.withOpacity(0.2)),
                          ),
                          focusedBorder: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(12),
                            borderSide: const BorderSide(color: Colors.indigoAccent),
                          ),
                          filled: true,
                          fillColor: Colors.black.withOpacity(0.2),
                        ),
                      ),
                      
                      const SizedBox(height: 20),
                      
                      // Password Field
                      TextField(
                        controller: _passwordController,
                        obscureText: true,
                        style: const TextStyle(color: Colors.white),
                        decoration: InputDecoration(
                          labelText: 'كلمة المرور',
                          labelStyle: TextStyle(color: Colors.white.withOpacity(0.7)),
                          prefixIcon: Icon(Icons.lock_outline, color: Colors.indigo[300]),
                          enabledBorder: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(12),
                            borderSide: BorderSide(color: Colors.white.withOpacity(0.2)),
                          ),
                          focusedBorder: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(12),
                            borderSide: const BorderSide(color: Colors.indigoAccent),
                          ),
                          filled: true,
                          fillColor: Colors.black.withOpacity(0.2),
                        ),
                      ),
                      
                      const SizedBox(height: 32),
                      
                      // Login Button
                      SizedBox(
                        width: double.infinity,
                        height: 56,
                        child: _isLoading 
                          ? const Center(child: CircularProgressIndicator(color: Colors.indigoAccent))
                          : ElevatedButton(
                            onPressed: _login,
                            style: ElevatedButton.styleFrom(
                              backgroundColor: const Color(0xFF4F46E5), // Indigo 600
                              foregroundColor: Colors.white,
                              elevation: 8,
                              shadowColor: Colors.indigo.withOpacity(0.5),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(16),
                              ),
                            ),
                            child: const Text(
                              'تسجيل الدخول',
                              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                            ),
                          ),
                      ),
                    ],
                  ),
                ),
                
                const SizedBox(height: 24),
                
                Text(
                  'SmartISP System © 2024',
                  style: TextStyle(color: Colors.white.withOpacity(0.3), fontSize: 12),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
