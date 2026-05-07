import 'package:flutter/material.dart';
import 'package:qr_flutter/qr_flutter.dart';

class DeviceLinkPage extends StatefulWidget {
  const DeviceLinkPage({super.key});

  @override
  State<DeviceLinkPage> createState() => _DeviceLinkPageState();
}

class _DeviceLinkPageState extends State<DeviceLinkPage> {
  final TextEditingController _deviceNameController = TextEditingController();
  String _generatedCode = '';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Liên kết thiết bị mới'),
        elevation: 0,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          children: [
            // Icon
            Container(
              width: 80,
              height: 80,
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [Colors.blue, Colors.purple],
                ),
                borderRadius: BorderRadius.circular(20),
              ),
              child: const Icon(Icons.qr_code_scanner, size: 40, color: Colors.white),
            ),
            const SizedBox(height: 24),

            // Title
            const Text(
              'Liên kết thiết bị mới',
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            Text(
              'Quét mã QR bằng ứng dụng xác thực để liên kết',
              style: TextStyle(color: Colors.grey[600]),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 32),

            // Device name input
            TextField(
              controller: _deviceNameController,
              decoration: InputDecoration(
                labelText: 'Tên thiết bị',
                hintText: 'VD: iPhone của tôi',
                prefixIcon: const Icon(Icons.devices),
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
              ),
            ),
            const SizedBox(height: 24),

            // Generate button
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () {
                  setState(() {
                    _generatedCode = _generateCode();
                  });
                },
                style: ElevatedButton.styleFrom(
                  padding: const EdgeInsets.symmetric(vertical: 14),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
                child: const Text('Tạo mã liên kết'),
              ),
            ),
            const SizedBox(height: 32),

            // QR Code
            if (_generatedCode.isNotEmpty) ...[
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(16),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.grey.withOpacity(0.2),
                      blurRadius: 10,
                      offset: const Offset(0, 4),
                    ),
                  ],
                ),
                child: Column(
                  children: [
                    QrImageView(
                      data: _generatedCode,
                      version: QrVersions.auto,
                      size: 200,
                      gapless: false,
                    ),
                    const SizedBox(height: 16),
                    Text(
                      'Mã liên kết:',
                      style: TextStyle(color: Colors.grey[600], fontSize: 12),
                    ),
                    const SizedBox(height: 4),
                    SelectableText(
                      _generatedCode,
                      style: const TextStyle(
                        fontFamily: 'monospace',
                        fontSize: 14,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 24),
              
              // Complete button
              SizedBox(
                width: double.infinity,
                child: OutlinedButton(
                  onPressed: () {
                    Navigator.pop(context);
                  },
                  style: OutlinedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                  child: const Text('Hoàn tất'),
                ),
              ),
            ],
          ],
        ),
      ),
    );
  }

  String _generateCode() {
    final deviceName = _deviceNameController.text.trim();
    if (deviceName.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Vui lòng nhập tên thiết bị')),
      );
      return '';
    }
    
    // Generate unique code
    final timestamp = DateTime.now().millisecondsSinceEpoch;
    final random = (timestamp % 10000).toString().padLeft(4, '0');
    return 'DEVICE-${deviceName.toUpperCase().replaceAll(' ', '_')}-$random';
  }
}