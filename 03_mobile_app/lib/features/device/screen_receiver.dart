import 'package:flutter/material.dart';
import 'dart:typed_data';

class ScreenReceiver extends StatefulWidget {
  const ScreenReceiver({super.key, required this.sessionId});
  final String sessionId;

  @override
  State<ScreenReceiver> createState() => _ScreenReceiverState();
}

class _ScreenReceiverState extends State<ScreenReceiver> {
  Uint8List? _screenData;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _connectToMirrorSession();
  }

  void _connectToMirrorSession() {
    // TODO: Connect to WebSocket and receive screen frames
    Future.delayed(const Duration(seconds: 2), () {
      setState(() {
        _isLoading = false;
      });
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Đang xem: ${widget.sessionId}'),
        actions: [
          IconButton(
            icon: const Icon(Icons.fullscreen),
            onPressed: () {
              // TODO: Full screen
            },
          ),
        ],
      ),
      body: _isLoading
          ? const Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  CircularProgressIndicator(),
                  SizedBox(height: 16),
                  Text('Đang kết nối đến màn hình nguồn...'),
                ],
              ),
            )
          : Container(
              color: Colors.black,
              child: Center(
                child: _screenData != null
                    ? Image.memory(_screenData!)
                    : Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.screen_share, size: 64, color: Colors.grey[600]),
                          const SizedBox(height: 16),
                          Text(
                            'Đang chờ dữ liệu màn hình',
                            style: TextStyle(color: Colors.grey[600]),
                          ),
                        ],
                      ),
              ),
            ),
    );
  }
}