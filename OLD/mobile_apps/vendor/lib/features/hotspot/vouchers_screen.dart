import 'package:flutter/material.dart';
import '../../core/api_client.dart';

class VouchersScreen extends StatefulWidget {
  const VouchersScreen({super.key});

  @override
  State<VouchersScreen> createState() => _VouchersScreenState();
}

class _VouchersScreenState extends State<VouchersScreen> {
  List<dynamic> _vouchers = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchVouchers();
  }

  Future<void> _fetchVouchers() async {
    setState(() => _isLoading = true);
    try {
      final response = await ApiClient.dio.get('/hotspot/vouchers');
      setState(() {
        _vouchers = response.data['data'] as List;
        _isLoading = false;
      });
    } catch (e) {
      if(mounted) {
        setState(() => _isLoading = false);
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Error: $e')));
      }
    }
  }

  void _generateVouchers() {
    // Show Dialog to Create Vouchers (Simplified for now)
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('توليد كروت جديدة'),
        content: const Text('سيتم تنفيذ هذه الميزة قريباً'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('إغلاق')),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('كروت الشبكة'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: _generateVouchers,
          )
        ],
      ),
      body: _isLoading 
        ? const Center(child: CircularProgressIndicator())
        : ListView.builder(
            itemCount: _vouchers.length,
            itemBuilder: (context, index) {
              final voucher = _vouchers[index];
              return Card(
                child: ListTile(
                  leading: const Icon(Icons.wifi_tethering, color: Colors.orange),
                  title: Text(voucher['username']),
                  subtitle: Text('Password: ${voucher['password']}'),
                  trailing: Chip(
                    label: Text(voucher['status'] ?? 'Unknown'),
                    backgroundColor: voucher['status'] == 'active' ? Colors.green[100] : Colors.grey[200],
                  ),
                ),
              );
            },
          ),
    );
  }
}
