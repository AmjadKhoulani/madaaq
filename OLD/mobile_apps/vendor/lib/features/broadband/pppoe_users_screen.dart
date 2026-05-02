import 'package:flutter/material.dart';
import '../../core/api_client.dart';

class PPPoEUsersScreen extends StatefulWidget {
  const PPPoEUsersScreen({super.key});

  @override
  State<PPPoEUsersScreen> createState() => _PPPoEUsersScreenState();
}

class _PPPoEUsersScreenState extends State<PPPoEUsersScreen> {
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
      final response = await ApiClient.dio.get('/broadband/users');
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
      appBar: AppBar(title: const Text('مشتركين البرودباند')),
      body: _isLoading 
        ? const Center(child: CircularProgressIndicator())
        : ListView.builder(
            itemCount: _users.length,
            itemBuilder: (context, index) {
              final user = _users[index];
              return Card(
                child: ListTile(
                  leading: const Icon(Icons.router, color: Colors.blue),
                  title: Text(user['username']),
                  subtitle: Text(user['name'] ?? ''),
                  trailing: Text(user['status'] ?? '-'),
                ),
              );
            },
          ),
    );
  }
}
