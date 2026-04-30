import 'package:flutter/material.dart';
import '../../core/api_client.dart';

class RoutersScreen extends StatefulWidget {
  const RoutersScreen({super.key});

  @override
  State<RoutersScreen> createState() => _RoutersScreenState();
}

class _RoutersScreenState extends State<RoutersScreen> {
  List<dynamic> _routers = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchRouters();
  }

  Future<void> _fetchRouters() async {
    setState(() => _isLoading = true);
    try {
      final response = await ApiClient.dio.get('/routers');
      setState(() {
        _routers = response.data['data'] as List;
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
      appBar: AppBar(title: const Text('الراوترات')),
      body: _isLoading 
        ? const Center(child: CircularProgressIndicator())
        : ListView.builder(
            itemCount: _routers.length,
            itemBuilder: (context, index) {
              final router = _routers[index];
              final isOnline = router['is_online'] ?? false;
              
              return Card(
                child: ListTile(
                  leading: Icon(Icons.router, color: isOnline ? Colors.green : Colors.red),
                  title: Text(router['name']),
                  subtitle: Text(router['ip_address']),
                  trailing: Column(
                     mainAxisAlignment: MainAxisAlignment.center,
                     children: [
                       Text('${router['clients_count'] ?? 0}', style: const TextStyle(fontWeight: FontWeight.bold)),
                       const Text('Clients', style: TextStyle(fontSize: 10)),
                     ],
                  ),
                ),
              );
            },
          ),
    );
  }
}
