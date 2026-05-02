import 'package:flutter/material.dart';
import '../../core/api_client.dart';
import 'package:dio/dio.dart';

class InvoiceDetailsScreen extends StatefulWidget {
  final int invoiceId;

  const InvoiceDetailsScreen({super.key, required this.invoiceId});

  @override
  State<InvoiceDetailsScreen> createState() => _InvoiceDetailsScreenState();
}

class _InvoiceDetailsScreenState extends State<InvoiceDetailsScreen> {
  bool _isLoading = true;
  Map<String, dynamic>? _invoice;

  @override
  void initState() {
    super.initState();
    _fetchDetails();
  }

  Future<void> _fetchDetails() async {
    try {
      final response = await ApiClient.dio.get('/finance/invoices/${widget.invoiceId}');
      if (mounted) {
        setState(() {
          _invoice = response.data;
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() => _isLoading = false);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error loading details: $e')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return const Scaffold(body: Center(child: CircularProgressIndicator()));
    }

    if (_invoice == null) {
      return const Scaffold(body: Center(child: Text('Invoice not found')));
    }

    final items = _invoice!['items'] as List<dynamic>? ?? [];

    return Scaffold(
      appBar: AppBar(title: Text('فاتورة #${_invoice!['invoice_number']}')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            _buildInfoCard(),
            const SizedBox(height: 20),
            Text('الأصناف', style: Theme.of(context).textTheme.titleLarge),
            const SizedBox(height: 10),
            ...items.map((item) => Card(
              margin: const EdgeInsets.only(bottom: 8),
              child: ListTile(
                title: Text(item['description'] ?? 'Item'),
                trailing: Text('${item['total']} ${_invoice!['currency'] ?? ''}'),
                subtitle: Text('الكمية: ${item['quantity']} | السعر: ${item['unit_price']}'),
              ),
            )),
             const SizedBox(height: 20),
            _buildTotalSection(),
          ],
        ),
      ),
    );
  }

  Widget _buildInfoCard() {
    return Card(
      elevation: 2,
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            _row('الحالة', _invoice!['status'] ?? '-'),
            const Divider(),
            _row('تاريخ الاستحقاق', _invoice!['due_date'] ?? '-'),
            const Divider(),
            _row('العميل', _invoice!['client']?['name'] ?? 'Guest'),
          ],
        ),
      ),
    );
  }

  Widget _buildTotalSection() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Theme.of(context).primaryColor.withOpacity(0.1),
        borderRadius: BorderRadius.circular(12),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          const Text('المجموع الكلي', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18)),
          Text('${_invoice!['total']} ${_invoice!['currency'] ?? ''}', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18, color: Theme.of(context).primaryColor)),
        ],
      ),
    );
  }

  Widget _row(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8.0),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: const TextStyle(color: Colors.grey)),
          Text(value, style: const TextStyle(fontWeight: FontWeight.w500)),
        ],
      ),
    );
  }
}
