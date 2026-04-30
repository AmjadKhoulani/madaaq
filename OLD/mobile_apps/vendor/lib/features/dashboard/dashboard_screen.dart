import 'package:flutter/material.dart';
import '../../core/api_client.dart';
import 'package:fl_chart/fl_chart.dart'; // Ensure this package is added

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  Map<String, dynamic> _stats = {};
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchStats();
  }

  Future<void> _fetchStats() async {
     try {
       // Fetch Dashboard specific stats or reuse reports endpoint
       final response = await ApiClient.dio.get('/reports/financial');
       if(mounted) {
         setState(() {
           _stats = response.data;
           _isLoading = false;
         });
       }
     } catch(e) {
       if(mounted) setState(() => _isLoading = false);
     }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('لوحة التحكم')),
      body: _isLoading 
        ? const Center(child: CircularProgressIndicator())
        : SingleChildScrollView(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Revenue Card
                Container(
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(
                    gradient: const LinearGradient(colors: [Color(0xFF4F46E5), Color(0xFF818CF8)]),
                    borderRadius: BorderRadius.circular(16),
                    boxShadow: [BoxShadow(color: Colors.indigo.withOpacity(0.3), blurRadius: 10, offset: const Offset(0,5))],
                  ),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('إجمالي الدخل', style: TextStyle(color: Colors.white70)),
                          const SizedBox(height: 8),
                          Text('${_stats['total_revenue'] ?? 0} ${_stats['currency'] ?? ''}', style: const TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold)),
                        ],
                      ),
                      const Icon(Icons.monetization_on, color: Colors.white24, size: 48),
                    ],
                  ),
                ),
                const SizedBox(height: 20),
                
                 // Pending Amount Card
                Card(
                  child: ListTile(
                    leading: const Icon(Icons.pending_actions, color: Colors.orange),
                    title: const Text('مبالغ غير مسددة'),
                    trailing: Text('${_stats['pending_revenue'] ?? 0} ${_stats['currency'] ?? ''}', style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  ),
                ),

                const SizedBox(height: 20),
                Text('آخر الفواتير', style: Theme.of(context).textTheme.titleLarge),
                const SizedBox(height: 10),
                ...(_stats['recent_invoices'] as List? ?? []).map((inv) => Card(
                  child: ListTile(
                    title: Text('Invoice #${inv['invoice_number']}'),
                    trailing: Text('${inv['total']} ${_stats['currency'] ?? ''}'),
                  ),
                )),
              ],
            ),
          ),
    );
  }
}
