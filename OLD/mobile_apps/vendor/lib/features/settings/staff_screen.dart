import 'package:flutter/material.dart';
import '../../core/api_client.dart';

class StaffScreen extends StatefulWidget {
  const StaffScreen({super.key});

  @override
  State<StaffScreen> createState() => _StaffScreenState();
}

class _StaffScreenState extends State<StaffScreen> {
  List<dynamic> _staff = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchStaff();
  }

  Future<void> _fetchStaff() async {
    setState(() => _isLoading = true);
    try {
      final response = await ApiClient.dio.get('/staff');
      setState(() {
        _staff = response.data['data'] as List;
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
      appBar: AppBar(
        title: const Text('الموظفين'),
        actions: [
          IconButton(onPressed: () {}, icon: const Icon(Icons.add)), // Placeholder
        ],
      ),
      body: _isLoading 
        ? const Center(child: CircularProgressIndicator())
        : ListView.builder(
            itemCount: _staff.length,
            itemBuilder: (context, index) {
              final member = _staff[index];
              return Card(
                child: ListTile(
                  leading: CircleAvatar(child: Text(member['name'][0].toUpperCase())),
                  title: Text(member['name']),
                  subtitle: Text(member['email']),
                  trailing: const Icon(Icons.more_vert),
                ),
              );
            },
          ),
    );
  }
}
