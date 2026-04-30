import 'package:flutter/material.dart';
import '../../core/api_client.dart';
import '../auth/login_screen.dart';
import '../dashboard/dashboard_screen.dart';
import '../clients/clients_screen.dart';
import '../settings/settings_screen.dart';
import '../finance/invoices_screen.dart';
import '../services/services_menu_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _currentIndex = 0;
  
  final List<Widget> _screens = [
    const DashboardScreen(),
    const ClientsScreen(),
    const ServicesMenuScreen(), // New
    const InvoicesScreen(),     // New
    const SettingsScreen(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: IndexedStack(
        index: _currentIndex,
        children: _screens,
      ),
      bottomNavigationBar: NavigationBarTheme(
        data: NavigationBarThemeData(
          indicatorColor: const Color(0xFF4F46E5).withOpacity(0.1),
          labelTextStyle: MaterialStateProperty.all(
            const TextStyle(fontWeight: FontWeight.w600, fontSize: 12),
          ),
          iconTheme: MaterialStateProperty.resolveWith((states) {
            if (states.contains(MaterialState.selected)) {
              return const IconThemeData(color: Color(0xFF4F46E5)); // Indigo 600
            }
            return IconThemeData(color: Colors.grey[600]);
          }),
        ),
        child: NavigationBar(
          height: 70,
          backgroundColor: Colors.white,
          elevation: 2,
          shadowColor: Colors.black.withOpacity(0.1),
          selectedIndex: _currentIndex,
          onDestinationSelected: (idx) => setState(() => _currentIndex = idx),
          destinations: const [
            NavigationDestination(
              icon: Icon(Icons.dashboard_outlined),
              selectedIcon: Icon(Icons.dashboard_rounded),
              label: 'الرئيسية',
            ),
            NavigationDestination(
              icon: Icon(Icons.people_outline),
              selectedIcon: Icon(Icons.people_rounded),
              label: 'المشتركين',
            ),
            NavigationDestination(
              icon: Icon(Icons.grid_view_outlined),
              selectedIcon: Icon(Icons.grid_view_rounded),
              label: 'الخدمات',
            ),
            NavigationDestination(
              icon: Icon(Icons.receipt_long_outlined),
              selectedIcon: Icon(Icons.receipt_long_rounded),
              label: 'الفواتير',
            ),
            NavigationDestination(
              icon: Icon(Icons.settings_outlined),
              selectedIcon: Icon(Icons.settings_rounded),
              label: 'الإعدادات',
            ),
          ],
        ),
      ),
    );
  }
}
