import 'package:flutter/material.dart';
import '../../core/api_client.dart';
import 'webfig_screen.dart';

class ServerDetailsScreen extends StatefulWidget {
  final dynamic server;
  const ServerDetailsScreen({super.key, required this.server});

  @override
  State<ServerDetailsScreen> createState() => _ServerDetailsScreenState();
}

class _ServerDetailsScreenState extends State<ServerDetailsScreen> {
  bool _isLoading = false;

  Future<void> _testConnection() async {
    setState(() => _isLoading = true);
    try {
      final response = await ApiClient.dio.post('/servers/${widget.server['id']}/test-connection');
      if (mounted) {
        final success = response.data['success'] == true;
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(response.data['message'] ?? (success ? 'Connection OK' : 'Connection Failed')),
            backgroundColor: success ? Colors.green : Colors.red,
          ),
        );
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error: $e'), backgroundColor: Colors.red),
        );
      }
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  Future<void> _openWebfig() async {
    setState(() => _isLoading = true);
    try {
      final response = await ApiClient.dio.get('/servers/${widget.server['id']}/webfig-url');
      if (mounted) {
        final url = response.data['url'];
        if (url != null) {
          Navigator.push(
            context,
            MaterialPageRoute(builder: (_) => WebfigScreen(url: url, title: widget.server['name'])),
          );
        } else {
          throw Exception('No URL returned');
        }
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Failed to get Webfig URL: $e'), backgroundColor: Colors.red),
        );
      }
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  Future<void> _importUsers(String type) async {
    setState(() => _isLoading = true);
    try {
      final endpoint = type == 'pppoe' ? 'import-pppoe' : 'import-hotspot';
      final response = await ApiClient.dio.post('/servers/${widget.server['id']}/$endpoint');
      if (mounted) {
        final success = response.data['success'] == true;
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(response.data['message'] ?? 'Import result unknown'),
            backgroundColor: success ? Colors.green : Colors.orange,
          ),
        );
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Import Failed: $e'), backgroundColor: Colors.red),
        );
      }
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  Future<void> _showSetupScript() async {
     setState(() => _isLoading = true);
    try {
      final response = await ApiClient.dio.get('/servers/${widget.server['id']}/setup-script');
      if (mounted) {
        final script = response.data['script'];
        showDialog(
          context: context,
          builder: (ctx) => AlertDialog(
            title: const Text('Setup Script'),
            content: SingleChildScrollView(
              child: SelectableText(script, style: const TextStyle(fontFamily: 'monospace', fontSize: 12)),
            ),
            actions: [
              TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Close')),
              TextButton(
                onPressed: () {
                   // Copy to clipboard logic could be here
                   Navigator.pop(ctx);
                   ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Script Copied (Simulated)')));
                }, 
                child: const Text('Copy')
              ),
            ],
          ),
        );
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Failed to get script: $e'), backgroundColor: Colors.red),
        );
      }
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[50], // Slate 50
      appBar: AppBar(
        title: Text(widget.server['name'] ?? 'Server Details'),
        centerTitle: true,
        backgroundColor: Colors.white,
        elevation: 0,
        iconTheme: const IconThemeData(color: Colors.black87),
        titleTextStyle: const TextStyle(color: Colors.black87, fontWeight: FontWeight.bold, fontSize: 18),
      ),
      body: _isLoading 
        ? const Center(child: CircularProgressIndicator()) 
        : SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              _buildInfoCard(),
              const SizedBox(height: 24),
              const Text('الإجراءات السريعة', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18)),
              const SizedBox(height: 16),
              GridView.count(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                crossAxisCount: 2,
                crossAxisSpacing: 16,
                mainAxisSpacing: 16,
                childAspectRatio: 1.3,
                children: [
                  _buildActionCard(
                    'فحص الاتصال', 
                    Icons.network_check, 
                    Colors.blue, 
                    _testConnection
                  ),
                  _buildActionCard(
                    'Webfig', 
                    Icons.web, 
                    Colors.indigo, 
                    _openWebfig
                  ),
                  _buildActionCard(
                    'استيراد PPPoE', 
                    Icons.download_rounded, 
                    Colors.orange, 
                    () => _importUsers('pppoe')
                  ),
                  _buildActionCard(
                    'استيراد Hotspot', 
                    Icons.wifi_tethering, 
                    Colors.deepOrange, 
                    () => _importUsers('hotspot')
                  ),
                   _buildActionCard(
                    'كود الربط', 
                    Icons.code, 
                    Colors.grey, 
                    _showSetupScript
                  ),
                ],
              ),
            ],
          ),
      ),
    );
  }

  Widget _buildInfoCard() {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(color: Colors.grey.withOpacity(0.05), blurRadius: 10, offset: const Offset(0, 4)),
        ],
      ),
      child: Column(
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: const Color(0xFF4F46E5).withOpacity(0.1),
                  shape: BoxShape.circle,
                ),
                child: const Icon(Icons.dns, color: Color(0xFF4F46E5), size: 32),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      widget.server['ip'] ?? '0.0.0.0',
                      style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                    ),
                    Text(
                      widget.server['location'] ?? 'الموقع غير محدد',
                      style: TextStyle(color: Colors.grey[500]),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildActionCard(String title, IconData icon, Color color, VoidCallback onTap) {
    return Material(
      color: Colors.white,
      borderRadius: BorderRadius.circular(16),
      elevation: 0, // Flat look with border
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(16),
        side: BorderSide(color: Colors.grey.withOpacity(0.1)),
      ),
      child: InkWell(
        borderRadius: BorderRadius.circular(16),
        onTap: onTap,
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: color.withOpacity(0.1),
                shape: BoxShape.circle,
              ),
              child: Icon(icon, color: color, size: 28),
            ),
            const SizedBox(height: 12),
            Text(
              title,
              style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
            ),
          ],
        ),
      ),
    );
  }
}
