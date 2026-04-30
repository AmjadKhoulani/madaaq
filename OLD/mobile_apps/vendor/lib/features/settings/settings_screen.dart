import 'package:flutter/material.dart';
import '../../core/api_client.dart';
import '../auth/login_screen.dart';
import 'staff_screen.dart';
import 'activity_logs_screen.dart';

class SettingsScreen extends StatelessWidget {
  const SettingsScreen({super.key});

  Future<void> _logout(BuildContext context) async {
    await ApiClient.logout();
    if (context.mounted) {
      Navigator.pushAndRemoveUntil(
        context,
        MaterialPageRoute(builder: (_) => const LoginScreen()),
        (route) => false,
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('الإعدادات')),
      body: ListView(
        children: [
          const UserAccountsDrawerHeader(
            accountName: Text('مدير النظام'),
            accountEmail: Text('admin@isp.com'), // Could fetch from API
            currentAccountPicture: CircleAvatar(child: Icon(Icons.person)),
          ),
          ListTile(
            leading: const Icon(Icons.person_outline),
            title: const Text('الملف الشخصي'),
            onTap: () {
               // Navigate to Profile Edit Screen (Placeholder)
               ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Profile Edit Coming Soon')));
            },
          ),
           ListTile(
            leading: const Icon(Icons.lock_outline),
            title: const Text('تغيير كلمة المرور'),
            onTap: () {
               // Show Change Password Dialog (Placeholder)
               ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Change Password Coming Soon')));
            },
          ),
          const Divider(),
          ListTile(
            leading: const Icon(Icons.manage_accounts),
            title: const Text('إدارة الموظفين'),
            trailing: const Icon(Icons.arrow_forward_ios, size: 16),
            onTap: () {
               Navigator.push(context, MaterialPageRoute(builder: (_) => const StaffScreen()));
            },
          ),
          ListTile(
            leading: const Icon(Icons.history),
            title: const Text('سجل النشاطات'),
            trailing: const Icon(Icons.arrow_forward_ios, size: 16),
             onTap: () {
               Navigator.push(context, MaterialPageRoute(builder: (_) => const ActivityLogsScreen()));
            },
          ),
          const Divider(),
          ListTile(
            leading: const Icon(Icons.language),
            title: const Text('اللغة'),
            trailing: const Text('العربية'),
            onTap: () {},
          ),
          ListTile(
            leading: const Icon(Icons.dark_mode),
            title: const Text('الوضع الليلي'),
            trailing: Switch(value: false, onChanged: (v) {}),
          ),
          const Divider(),
          ListTile(
            leading: const Icon(Icons.logout, color: Colors.red),
            title: const Text('تسجيل الخروج', style: TextStyle(color: Colors.red)),
            onTap: () => _logout(context),
          ),
        ],
      ),
    );
  }
}
