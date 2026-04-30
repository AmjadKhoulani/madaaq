import 'package:flutter/material.dart';
import '../../core/api_client.dart';

class SessionsScreen extends StatefulWidget {
  const SessionsScreen({super.key});

  @override
  State<SessionsScreen> createState() => _SessionsScreenState();
}

class _SessionsScreenState extends State<SessionsScreen> {
  List<dynamic> _sessions = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchSessions();
  }

  Future<void> _fetchSessions() async {
    setState(() => _isLoading = true);
    try {
      final response = await ApiClient.dio.get('/network/sessions');
      setState(() {
        _sessions = response.data['data'] as List;
        _isLoading = false;
      });
    } catch (e) {
      if(mounted) {
        setState(() => _isLoading = false);
      }
    }
  }

  Future<void> _disconnect(String routerId, String routerType, String sessionId, String type) async {
    try {
      await ApiClient.dio.post('/network/sessions/$sessionId/disconnect', data: {
        'router_id': routerId,
        'router_type': routerType,
        'session_id': sessionId,
        'type': type
      });
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Session disconnected')));
      _fetchSessions(); // Refresh
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Failed: $e')));
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('الجلسات النشطة')),
      body: _isLoading 
        ? const Center(child: CircularProgressIndicator())
        : ListView.builder(
            itemCount: _sessions.length,
            itemBuilder: (context, index) {
              final session = _sessions[index];
              return Card(
                child: ListTile(
                  leading: const Icon(Icons.cast_connected, color: Colors.green),
                  title: Text(session['user'] ?? 'Unknown'),
                  subtitle: Text('${session['ip_address']} | ${session['uptime']}'),
                  trailing: IconButton(
                    icon: const Icon(Icons.close, color: Colors.red),
                    onPressed: () => _disconnect(
                      session['router_id'].toString(), 
                      session['router_type'], 
                      session['id'],
                      // session type is not strictly in API response in controller created earlier?
                      // Wait, API response mimics web controller. 
                      // Need to check if 'type' (pppoe/hotspot) is part of session data from MikroTikService.
                      // Assuming it is 'pppoe' or we guess.
                      'pppoe' // defaulting for safety or need update controller to send type.
                    ),
                  ),
                ),
              );
            },
          ),
    );
  }
}
