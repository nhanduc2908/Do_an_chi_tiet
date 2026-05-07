import 'package:device_info_plus/device_info_plus.dart';

class DeviceUtils {
  static final DeviceInfoPlugin _deviceInfo = DeviceInfoPlugin();

  static Future<String> getDeviceId() async {
    if (await _isAndroid()) {
      final androidInfo = await _deviceInfo.androidInfo;
      return androidInfo.id;
    } else {
      final iosInfo = await _deviceInfo.iosInfo;
      return iosInfo.identifierForVendor ?? '';
    }
  }

  static Future<String> getDeviceName() async {
    if (await _isAndroid()) {
      final androidInfo = await _deviceInfo.androidInfo;
      return androidInfo.model;
    } else {
      final iosInfo = await _deviceInfo.iosInfo;
      return iosInfo.name;
    }
  }

  static Future<String> getOSVersion() async {
    if (await _isAndroid()) {
      final androidInfo = await _deviceInfo.androidInfo;
      return 'Android ${androidInfo.version.release}';
    } else {
      final iosInfo = await _deviceInfo.iosInfo;
      return 'iOS ${iosInfo.systemVersion}';
    }
  }

  static Future<bool> _isAndroid() async {
    return DeviceInfoPlugin().androidInfo.isAndroid;
  }

  static Future<bool> isTablet() async {
    if (await _isAndroid()) {
      final androidInfo = await _deviceInfo.androidInfo;
      return androidInfo.displayMetrics.widthPx > 1000;
    } else {
      final iosInfo = await _deviceInfo.iosInfo;
      return iosInfo.model.contains('iPad');
    }
  }
}