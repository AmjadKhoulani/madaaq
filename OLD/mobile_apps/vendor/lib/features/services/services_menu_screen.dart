import 'package:flutter/material.dart';
import '../hotspot/vouchers_screen.dart';
import '../hotspot/hotspot_users_screen.dart';
import '../broadband/pppoe_users_screen.dart';
import '../broadband/profiles_screen.dart';
import '../network/sessions_screen.dart';
import '../network/towers_screen.dart';
import '../network/routers_screen.dart';
import '../servers/servers_screen.dart';

class ServicesMenuScreen extends StatelessWidget {
  const ServicesMenuScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('الخدمات'),
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            _buildSectionHeader('Hotspot'),
            const SizedBox(height: 10),
            Row(
              children: [
                _buildServiceCard(
                  context,
                  'الكروت',
                  Icons.wifi_tethering,
                  Colors.orange,
                  () => Navigator.push(context, MaterialPageRoute(builder: (_) => const VouchersScreen())),
                ),
                const SizedBox(width: 16),
                _buildServiceCard(
                  context,
                  'المستخدمين',
                  Icons.people_alt,
                  Colors.orangeAccent,
                  () => Navigator.push(context, MaterialPageRoute(builder: (_) => const HotspotUsersScreen())),
                ),
              ],
            ),
            const SizedBox(height: 24),
            
            _buildSectionHeader('Broadband (PPPoE)'),
            const SizedBox(height: 10),
            Row(
              children: [
                _buildServiceCard(
                  context,
                  'المشتركين',
                  Icons.router,
                  Colors.blue,
                  () => Navigator.push(context, MaterialPageRoute(builder: (_) => const PPPoEUsersScreen())),
                ),
                const SizedBox(width: 16),
                _buildServiceCard(
                  context,
                  'البروفايلات',
                  Icons.speed,
                  Colors.blueAccent,
                  () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ProfilesScreen())),
                ),
              ],
            ),
            const SizedBox(height: 24),

            _buildSectionHeader('الشبكة'),
            const SizedBox(height: 10),
             Row(
              children: [
                _buildServiceCard(
                  context,
                  'السيرفرات',
                  Icons.dns,
                  Colors.indigoAccent,
                  () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ServersScreen())),
                ),
                const SizedBox(width: 16),
                _buildServiceCard(
                  context,
                  'الجلسات الحالية',
                  Icons.cast_connected,
                  Colors.green,
                  () => Navigator.push(context, MaterialPageRoute(builder: (_) => const SessionsScreen())),
                ),
                 const SizedBox(width: 16),
                 _buildServiceCard(
                  context,
                  'الأبراج',
                  Icons.cell_tower,
                  Colors.indigo,
                  () => Navigator.push(context, MaterialPageRoute(builder: (_) => const TowersScreen())),
                ),
                const SizedBox(width: 16),
                _buildServiceCard(
                  context,
                  'الراوترات',
                  Icons.router_outlined,
                  Colors.blueGrey,
                  () => Navigator.push(context, MaterialPageRoute(builder: (_) => const RoutersScreen())),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSectionHeader(String title) {
    return Text(
      title,
      style: const TextStyle(
        fontSize: 18,
        fontWeight: FontWeight.bold,
        color: Colors.black87,
      ),
    );
  }

  Widget _buildServiceCard(BuildContext context, String title, IconData icon, Color color, VoidCallback onTap) {
    return Expanded(
      child: GestureDetector(
        onTap: onTap,
        child: Container(
          height: 120,
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            boxShadow: [
              BoxShadow(
                color: Colors.grey.withOpacity(0.1),
                spreadRadius: 2,
                blurRadius: 5,
                offset: const Offset(0, 3),
              ),
            ],
          ),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: color.withOpacity(0.1),
                  shape: BoxShape.circle,
                ),
                child: Icon(icon, color: color, size: 30),
              ),
              const SizedBox(height: 12),
              Text(
                title,
                style: const TextStyle(
                  fontSize: 15,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
