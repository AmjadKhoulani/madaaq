import 'package:flutter/material.dart';
import '../../core/api_client.dart';

class HotspotUsersScreen extends StatefulWidget {
  const HotspotUsersScreen({super.key});

  @override
  State<HotspotUsersScreen> createState() => _HotspotUsersScreenState();
}

class _HotspotUsersScreenState extends State<HotspotUsersScreen> {
  List<dynamic> _users = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchUsers();
  }

  Future<void> _fetchUsers() async {
    setState(() => _isLoading = true);
    try {
      final response = await ApiClient.dio.get('/hotspot/users');
      setState(() {
        _users = response.data['data'] as List;
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
      appBar: AppBar(title: const Text('مستخدمين الهوتسبوت')),
      body: _isLoading 
        ? const Center(child: CircularProgressIndicator())
        : ListView.builder(
            itemCount: _users.length,
            itemBuilder: (context, index) {
              final user = _users[index];
              return Card(
                child: ListTile(
                  leading: const Icon(Icons.person, color: Colors.orangeAccent),
                  title: Text(user['username']),
                  subtitle: Text(user['package']?['name'] ?? 'No Package'),
                  trailing: const Icon(Icons.chevron_right),
                ),
              );
            },
          ),
    );
  }
}
