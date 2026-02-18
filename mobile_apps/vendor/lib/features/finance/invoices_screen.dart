import 'package:flutter/material.dart';
import '../../core/api_client.dart';
import 'invoice_details_screen.dart';

class InvoicesScreen extends StatefulWidget {
  const InvoicesScreen({super.key});

  @override
  State<InvoicesScreen> createState() => _InvoicesScreenState();
}

class _InvoicesScreenState extends State<InvoicesScreen> {
  final List<dynamic> _invoices = [];
  String _currency = '';
  bool _isLoading = true;
  int _page = 1;
  bool _hasMore = true;
  final ScrollController _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    _fetchInvoices();
    _scrollController.addListener(() {
      if (_scrollController.position.pixels == _scrollController.position.maxScrollExtent) {
        if (_hasMore && !_isLoading) {
          _fetchInvoices();
        }
      }
    });
  }

  Future<void> _fetchInvoices() async {
    if (!_hasMore) return;
    
    setState(() => _isLoading = true);
    try {
      final response = await ApiClient.dio.get('/finance/invoices', queryParameters: {'page': _page});
      final data = response.data['data'] as List;
      final meta = response.data; 
      
      setState(() {
        _invoices.addAll(data);
        _currency = response.data['currency'] ?? '';
        _page++;
        _isLoading = false;
        // Check if last page
        if (data.isEmpty || data.length < 20) {
          _hasMore = false;
        }
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
      appBar: AppBar(title: const Text('الفواتير')),
      body: _invoices.isEmpty && !_isLoading 
          ? const Center(child: Text('لا توجد فواتير'))
          : ListView.builder(
              controller: _scrollController,
              itemCount: _invoices.length + (_hasMore ? 1 : 0),
              itemBuilder: (context, index) {
                if (index == _invoices.length) {
                  return const Center(child: CircularProgressIndicator());
                }
                
                final invoice = _invoices[index];
                return Card(
                  margin: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                  child: ListTile(
                    leading: CircleAvatar(
                      backgroundColor: _getStatusColor(invoice['status']),
                      child: const Icon(Icons.receipt, color: Colors.white),
                    ),
                    title: Text('${invoice['client']?['name'] ?? "Guest"}'),
                    subtitle: Text('Due: ${invoice['due_date']}'),
                    trailing: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      crossAxisAlignment: CrossAxisAlignment.end,
                      children: [
                        Text('${invoice['total']} $_currency', style: const TextStyle(fontWeight: FontWeight.bold)),
                        Text('#${invoice['invoice_number']}', style: const TextStyle(fontSize: 12, color: Colors.grey)),
                      ],
                    ),
                    onTap: () {
                      Navigator.push(context, MaterialPageRoute(builder: (_) => InvoiceDetailsScreen(invoiceId: invoice['id'])));
                    },
                  ),
                );
              },
            ),
    );
  }
  
  Color _getStatusColor(String? status) {
    switch (status) {
      case 'paid': return Colors.green;
      case 'unpaid': return Colors.red;
      case 'overdue': return Colors.orange;
      default: return Colors.grey;
    }
  }
}
