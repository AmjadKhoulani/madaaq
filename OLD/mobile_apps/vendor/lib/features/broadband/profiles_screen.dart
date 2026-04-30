import 'package:flutter/material.dart';
import '../../core/api_client.dart';

class ProfilesScreen extends StatefulWidget {
  const ProfilesScreen({super.key});

  @override
  State<ProfilesScreen> createState() => _ProfilesScreenState();
}

class _ProfilesScreenState extends State<ProfilesScreen> {
  List<dynamic> _profiles = [];
  String _currency = '';
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchProfiles();
  }

  Future<void> _fetchProfiles() async {
    setState(() => _isLoading = true);
    try {
      final response = await ApiClient.dio.get('/broadband/profiles');
      setState(() {
        _profiles = response.data['data'] as List;
        _currency = response.data['currency'] ?? '';
        _isLoading = false;
      });
    } catch (e) {
      if(mounted) {
        setState(() => _isLoading = false);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('البروفايلات')),
      body: _isLoading 
        ? const Center(child: CircularProgressIndicator())
        : ListView.builder(
            itemCount: _profiles.length,
            itemBuilder: (context, index) {
              final profile = _profiles[index];
              return Card(
                child: ListTile(
                  leading: const Icon(Icons.speed, color: Colors.blueAccent),
                  title: Text(profile['name']),
                  subtitle: Text('${profile['speed_down']}M/${profile['speed_up']}M'),
                  trailing: Text('${profile['price']} $_currency'),
                ),
              );
            },
          ),
    );
  }
}
