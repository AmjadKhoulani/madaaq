import 'package:flutter/material.dart';
import '../../core/api_client.dart';
import 'package:dio/dio.dart';

class AddClientScreen extends StatefulWidget {
  const AddClientScreen({super.key});

  @override
  State<AddClientScreen> createState() => _AddClientScreenState();
}

class _AddClientScreenState extends State<AddClientScreen> {
  final _formKey = GlobalKey<FormState>();
  
  String _selectedType = 'hotspot';
  String? _selectedPackageId;
  
  final TextEditingController _nameController = TextEditingController();
  final TextEditingController _usernameController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final TextEditingController _phoneController = TextEditingController();
  
  List<dynamic> _hotspotPackages = [];
  List<dynamic> _pppoePackages = [];
  String _currency = '';
  bool _isLoadingPackages = true;
  bool _isSubmitting = false;

  @override
  void initState() {
    super.initState();
    _fetchPackages();
  }

  Future<void> _fetchPackages() async {
    try {
      final hotspotRes = await ApiClient.dio.get('/hotspot/packages');
      final pppoeRes = await ApiClient.dio.get('/broadband/profiles');
      
      if (mounted) {
        setState(() {
          // Both now return {data: [], currency: ''}
          _hotspotPackages = hotspotRes.data['data'] as List;
          _pppoePackages = pppoeRes.data['data'] as List; 
          _currency = hotspotRes.data['currency'] ?? 'SR';
          
          _isLoadingPackages = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() => _isLoadingPackages = false);
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Error loading packages: $e')));
      }
    }
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    if (_selectedPackageId == null) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('الرجاء اختيار الباقة')));
      return;
    }

    setState(() => _isSubmitting = true);

    try {
      await ApiClient.dio.post('/clients', data: {
        'name': _nameController.text,
        'username': _usernameController.text,
        'password': _passwordController.text,
        'phone': _phoneController.text,
        'type': _selectedType,
        'package_id': _selectedPackageId,
      });

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('تم إضافة العميل بنجاح')));
        Navigator.pop(context, true); // Return true to refresh list
      }
    } catch (e) {
      if (mounted) {
        setState(() => _isSubmitting = false);
        String msg = 'فشل إضافة العميل';
        if(e is DioException && e.response != null) {
            msg = e.response?.data['message'] ?? msg;
        }
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(msg)));
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    List<dynamic> currentPackages = _selectedType == 'hotspot' ? _hotspotPackages : _pppoePackages;

    return Scaffold(
      appBar: AppBar(title: const Text('إضافة مشترك جديد')),
      body: _isLoadingPackages 
        ? const Center(child: CircularProgressIndicator())
        : SingleChildScrollView(
            padding: const EdgeInsets.all(16),
            child: Form(
              key: _formKey,
              child: Column(
                children: [
                   // Type Selector
                  DropdownButtonFormField<String>(
                    value: _selectedType,
                    decoration: const InputDecoration(labelText: 'نوع الاشتراك', border: OutlineInputBorder()),
                    items: const [
                       DropdownMenuItem(value: 'hotspot', child: Text('Hotspot')),
                       DropdownMenuItem(value: 'pppoe', child: Text('Broadband (PPPoE)')),
                    ],
                    onChanged: (val) {
                      setState(() {
                        _selectedType = val!;
                        _selectedPackageId = null; 
                      });
                    },
                  ),
                  const SizedBox(height: 16),
                  
                  // Package Selector
                  DropdownButtonFormField<String>(
                    value: _selectedPackageId,
                    decoration: const InputDecoration(labelText: 'الباقة / البروفايل', border: OutlineInputBorder()),
                    items: currentPackages.map((pkg) {
                      // Handle various structures if needed, strictly assuming id and name exist
                      // Broadband controller returns raw list of packages via Package::where...get() ?
                      // Or paginate?
                      // If paginate, data is in 'data'. If get(), it's list.
                      // Code: $packages = Package::where('type', 'hotspot')->get(); return response()->json($packages);
                      // So it is a List.
                      return DropdownMenuItem(
                        value: pkg['id'].toString(),
                        child: Text('${pkg['name']} (${pkg['price']} $_currency)'),
                      );
                    }).toList(),
                    onChanged: (val) => setState(() => _selectedPackageId = val),
                    validator: (val) => val == null ? 'مطلوب' : null,
                  ),
                  const SizedBox(height: 16),

                  TextFormField(
                    controller: _nameController,
                    decoration: const InputDecoration(labelText: 'اسم المشترك', border: OutlineInputBorder()),
                    validator: (val) => val!.isEmpty ? 'مطلوب' : null,
                  ),
                  const SizedBox(height: 16),

                  TextFormField(
                    controller: _phoneController,
                    decoration: const InputDecoration(labelText: 'رقم الهاتف', border: OutlineInputBorder()),
                    keyboardType: TextInputType.phone,
                  ),
                  const SizedBox(height: 16),
                  
                  TextFormField(
                    controller: _usernameController,
                    decoration: const InputDecoration(labelText: 'اسم المستخدم (للدخول)', border: OutlineInputBorder()),
                    validator: (val) => val!.isEmpty ? 'مطلوب' : null,
                  ),
                  const SizedBox(height: 16),

                  TextFormField(
                    controller: _passwordController,
                    decoration: const InputDecoration(labelText: 'كلمة المرور', border: OutlineInputBorder()),
                    validator: (val) => val!.length < 4 ? '4 أحرف على الأقل' : null,
                  ),
                  const SizedBox(height: 24),

                  SizedBox(
                    width: double.infinity,
                    height: 50,
                    child: ElevatedButton(
                      onPressed: _isSubmitting ? null : _submit,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF4F46E5),
                        foregroundColor: Colors.white,
                      ),
                      child: _isSubmitting 
                        ? const CircularProgressIndicator(color: Colors.white)
                        : const Text('إضافة المشترك'),
                    ),
                  )

                ],
              ),
            ),
          ),
    );
  }
}
