import 'package:flutter/material.dart';
import '../../core/api_client.dart';

class ClientDetailsScreen extends StatefulWidget {
  final int clientId;
  const ClientDetailsScreen({super.key, required this.clientId});

  @override
  State<ClientDetailsScreen> createState() => _ClientDetailsScreenState();
}

class _ClientDetailsScreenState extends State<ClientDetailsScreen> {
  Map<String, dynamic>? _client;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchClientDetails();
  }

  Future<void> _fetchClientDetails() async {
    setState(() => _isLoading = true);
    try {
      final response = await ApiClient.dio.get('/clients/${widget.clientId}');
      if (mounted) {
        setState(() {
          _client = response.data;
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() => _isLoading = false);
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('فشل تحميل بيانات المشترك')),
        );
      }
    }
  }

  Future<void> _toggleStatus() async {
    try {
      final response = await ApiClient.dio.post('/clients/${widget.clientId}/block');
      if (mounted) {
        setState(() {
          _client!['status'] = response.data['status'];
        });
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(response.data['message'])));
      }
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('فشل العملية')));
    }
  }

  Future<void> _renewSubscription() async {
    try {
      final response = await ApiClient.dio.post('/clients/${widget.clientId}/renew');
      if (mounted) {
        _fetchClientDetails(); // Refresh all details
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(response.data['message'])));
      }
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('فشل العملية')));
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) return const Scaffold(body: Center(child: CircularProgressIndicator()));
    if (_client == null) return const Scaffold(body: Center(child: Text('خطأ في التحميل')));

    return Scaffold(
      appBar: AppBar(title: Text(_client!['name'] ?? 'تفاصيل المشترك')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            // Status Card
            Card(
              color: _client!['status'] == 'active' ? Colors.green[50] : Colors.red[50],
              child: ListTile(
                leading: Icon(
                  _client!['status'] == 'active' ? Icons.check_circle : Icons.cancel,
                  color: _client!['status'] == 'active' ? Colors.green : Colors.red,
                ),
                title: Text(
                  _client!['status'] == 'active' ? 'نشط' : 'غير نشط',
                  style: TextStyle(
                    fontWeight: FontWeight.bold,
                    color: _client!['status'] == 'active' ? Colors.green[800] : Colors.red[800],
                  ),
                ),
                subtitle: Text('ID: #${_client!['id']}'),
              ),
            ),
            const SizedBox(height: 16),
            
            // Info Card
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  children: [
                    _buildRow(Icons.person, 'اسم المستخدم', _client!['username']),
                    const Divider(),
                    _buildRow(Icons.phone, 'رقم الهاتف', _client!['phone'] ?? '--'),
                    const Divider(),
                    _buildRow(Icons.speed, 'الباقة', _client!['package']?['name'] ?? 'مخصص'),
                    const Divider(),
                    _buildRow(Icons.router, 'الراوتر', _client!['router']?['name'] ?? '--'),
                    const Divider(),
                    _buildRow(Icons.attach_money, 'السعر الشهري', '${_client!['price'] ?? _client!['package']?['price'] ?? 0} ر.س'),
                  ],
                ),
              ),
            ),

            const SizedBox(height: 20),
            // Actions
            Row(
              children: [
                Expanded(
                  child: ElevatedButton.icon(
                    onPressed: _toggleStatus,
                    icon: Icon(_client!['status'] == 'active' ? Icons.block : Icons.check_circle),
                    label: Text(_client!['status'] == 'active' ? 'إيقاف' : 'تفعيل'),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: _client!['status'] == 'active' ? Colors.red : Colors.green, 
                      foregroundColor: Colors.white
                    ),
                  ),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: ElevatedButton.icon(
                    onPressed: _renewSubscription,
                    icon: const Icon(Icons.refresh),
                    label: const Text('تجديد'),
                    style: ElevatedButton.styleFrom(backgroundColor: Colors.blue, foregroundColor: Colors.white),
                  ),
                ),
              ],
            )
          ],
        ),
      ),
    );
  }

  Widget _buildRow(IconData icon, String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8.0),
      child: Row(
        children: [
          Icon(icon, size: 20, color: Colors.grey),
          const SizedBox(width: 12),
          Text(label, style: const TextStyle(color: Colors.grey)),
          const Spacer(),
          Text(value, style: const TextStyle(fontWeight: FontWeight.bold)),
        ],
      ),
    );
  }
}
