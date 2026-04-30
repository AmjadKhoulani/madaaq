import 'package:flutter/material.dart';
import '../../core/api_client.dart';

class TowersScreen extends StatefulWidget {
  const TowersScreen({super.key});

  @override
  State<TowersScreen> createState() => _TowersScreenState();
}

class _TowersScreenState extends State<TowersScreen> {
  List<dynamic> _towers = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchTowers();
  }

  Future<void> _fetchTowers() async {
    setState(() => _isLoading = true);
    try {
      final response = await ApiClient.dio.get('/network/towers');
      setState(() {
        _towers = response.data['data'] as List;
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
      appBar: AppBar(title: const Text('الأبراج')),
      body: _isLoading 
        ? const Center(child: CircularProgressIndicator())
        : ListView.builder(
            itemCount: _towers.length,
            itemBuilder: (context, index) {
              final tower = _towers[index];
              return Card(
                child: ListTile(
                  leading: const Icon(Icons.cell_tower, color: Colors.indigo),
                  title: Text(tower['name']),
                  subtitle: Text('Location: ${tower['location'] ?? '-'}'),
                  trailing: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text('${tower['clients_count'] ?? 0}', style: const TextStyle(fontWeight: FontWeight.bold)),
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
