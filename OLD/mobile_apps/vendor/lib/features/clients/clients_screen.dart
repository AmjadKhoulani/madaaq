import 'package:flutter/material.dart';
import 'package:dio/dio.dart'; // Import dio 
import '../../core/api_client.dart';
import 'client_details_screen.dart';
import 'add_client_screen.dart';

class ClientsScreen extends StatefulWidget {
  const ClientsScreen({super.key});

  @override
  State<ClientsScreen> createState() => _ClientsScreenState();
}

class _ClientsScreenState extends State<ClientsScreen> {
  final TextEditingController _searchController = TextEditingController();
  List<dynamic> _clients = [];
  bool _isLoading = true;
  String _searchQuery = '';

  @override
  void initState() {
    super.initState();
    _fetchClients();
  }

  Future<void> _fetchClients() async {
    setState(() => _isLoading = true);
    try {
      final response = await ApiClient.dio.get('/clients', queryParameters: {
        'search': _searchQuery,
      });
      
      if (mounted) {
        setState(() {
          _clients = response.data['data']; // Laravel paginate returns {data: [...]}
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() => _isLoading = false);
        // Error handling
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('فشل تحميل المشتركين')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC), // Slate 50
      body: NestedScrollView(
        headerSliverBuilder: (context, innerBoxIsScrolled) => [
          SliverAppBar(
            expandedHeight: 120.0,
            floating: true,
            pinned: true,
            backgroundColor: const Color(0xFF4F46E5),
            shape: const RoundedRectangleBorder(
              borderRadius: BorderRadius.vertical(bottom: Radius.circular(24)),
            ),
            flexibleSpace: FlexibleSpaceBar(
              titlePadding: const EdgeInsets.only(left: 16, right: 16, bottom: 16),
              title: const Text(
                'المشتركين',
                style: TextStyle(
                  color: Colors.white,
                  fontWeight: FontWeight.bold,
                  fontSize: 20,
                ),
              ),
              background: Container(
                decoration: const BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                    colors: [Color(0xFF312E81), Color(0xFF4F46E5)],
                  ),
                  borderRadius: BorderRadius.vertical(bottom: Radius.circular(24)),
                ),
                child: Stack(
                  children: [
                    Positioned(
                      left: -20,
                      top: -20,
                      child: Icon(Icons.people_alt, size: 150, color: Colors.white.withOpacity(0.1)),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ],
        body: Column(
          children: [
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Container(
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(16),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.grey.withOpacity(0.1),
                      blurRadius: 10,
                      offset: const Offset(0, 4),
                    ),
                  ],
                ),
                child: TextField(
                  controller: _searchController,
                  decoration: InputDecoration(
                    hintText: 'بحث باسم المشترك، الهاتف، أو المعرف...',
                    hintStyle: TextStyle(color: Colors.grey[400]),
                    prefixIcon: const Icon(Icons.search, color: Color(0xFF4F46E5)), // Indigo
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(16),
                      borderSide: BorderSide.none,
                    ),
                    filled: true,
                    fillColor: Colors.white,
                    contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
                  ),
                  onChanged: (val) { // Debounce could be added here
                     setState(() => _searchQuery = val);
                     _fetchClients();
                  },
                ),
              ),
            ),
            Expanded(
              child: _isLoading 
                ? const Center(child: CircularProgressIndicator())
                : _clients.isEmpty 
                  ? Center(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.search_off, size: 64, color: Colors.grey[300]),
                          const SizedBox(height: 16),
                          Text('لا يوجد مشتركين', style: TextStyle(color: Colors.grey[500])),
                        ],
                      ),
                    )
                  : ListView.builder(
                      padding: const EdgeInsets.fromLTRB(16, 0, 16, 80), // Padding for FAB
                      itemCount: _clients.length,
                      itemBuilder: (context, index) {
                        final client = _clients[index];
                        final isActive = client['status'] == 'active';
                        
                        return Container(
                          margin: const EdgeInsets.only(bottom: 12),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(16),
                            boxShadow: [
                              BoxShadow(
                                color: Colors.grey.withOpacity(0.05),
                                blurRadius: 10,
                                offset: const Offset(0, 2),
                              ),
                            ],
                            border: Border.all(color: Colors.grey.withOpacity(0.1)),
                          ),
                          child: Material(
                            color: Colors.transparent,
                            child: InkWell(
                              borderRadius: BorderRadius.circular(16),
                              onTap: () {
                                Navigator.push(context, MaterialPageRoute(
                                  builder: (_) => ClientDetailsScreen(clientId: client['id']),
                                ));
                              },
                              child: Padding(
                                padding: const EdgeInsets.all(16),
                                child: Row(
                                  children: [
                                    Container(
                                      width: 50,
                                      height: 50,
                                      decoration: BoxDecoration(
                                        color: isActive ? Colors.green.shade50 : Colors.red.shade50,
                                        shape: BoxShape.circle,
                                      ),
                                      child: Icon(
                                        Icons.person, 
                                        color: isActive ? Colors.green : Colors.red,
                                      ),
                                    ),
                                    const SizedBox(width: 16),
                                    Expanded(
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            client['name'] ?? 'بدون اسم',
                                            style: const TextStyle(
                                              fontWeight: FontWeight.bold,
                                              fontSize: 16,
                                              color: Color(0xFF1E293B),
                                            ),
                                          ),
                                          const SizedBox(height: 4),
                                          Row(
                                            children: [
                                              Icon(Icons.alternate_email, size: 14, color: Colors.grey[400]),
                                              const SizedBox(width: 4),
                                              Text(
                                                client['username'] ?? '-',
                                                style: TextStyle(color: Colors.grey[600], fontSize: 13),
                                              ),
                                              if (client['phone'] != null) ...[
                                                const SizedBox(width: 8),
                                                Text('•', style: TextStyle(color: Colors.grey[300])),
                                                const SizedBox(width: 8),
                                                Icon(Icons.phone, size: 14, color: Colors.grey[400]),
                                                const SizedBox(width: 4),
                                                Text(
                                                  client['phone'],
                                                  style: TextStyle(color: Colors.grey[600], fontSize: 13),
                                                ),
                                              ],
                                            ],
                                          ),
                                        ],
                                      ),
                                    ),
                                    Icon(Icons.arrow_forward_ios_rounded, size: 16, color: Colors.grey[300]),
                                  ],
                                ),
                              ),
                            ),
                          ),
                        );
                      },
                    ),
            ),
          ],
        ),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () async {
          final result = await Navigator.push(
            context,
            MaterialPageRoute(builder: (_) => const AddClientScreen()),
          );
          if (result == true) {
            _fetchClients();
          }
        },
        backgroundColor: const Color(0xFF4F46E5),
        elevation: 4,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        child: const Icon(Icons.add, color: Colors.white),
      ),
    );
  }
}
