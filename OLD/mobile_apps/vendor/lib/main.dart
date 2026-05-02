import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'features/auth/login_screen.dart';

void main() {
  runApp(const VendorApp());
}

class VendorApp extends StatelessWidget {
  const VendorApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'MadaaQ',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(
          seedColor: const Color(0xFF4F46E5), // Indigo 600
          primary: const Color(0xFF4F46E5),
          secondary: const Color(0xFF4338CA), // Indigo 700
          surface: const Color(0xFFF8FAFC), // Slate 50
        ),
        useMaterial3: true,
        textTheme: GoogleFonts.rubikTextTheme(),
      ),
      home: const LoginScreen(),
    );
  }
}
