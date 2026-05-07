import 'package:flutter/material.dart';

import '../../core/constants/role_constants.dart';
import '../../core/utils/role_utils.dart';

class RoleBadge extends StatelessWidget {
  final List<String> roles;
  final double size;
  final bool showName;

  const RoleBadge({
    super.key,
    required this.roles,
    this.size = 40,
    this.showName = true,
  });

  @override
  Widget build(BuildContext context) {
    final role = RoleUtils.getCurrentRole(roles);
    
    if (role == null) {
      return const SizedBox.shrink();
    }

    final style = RoleUtils.getRoleBadgeStyle(roles);
    final bgColor = style['bg'] as Color;
    final textColor = style['text'] as Color;

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(20),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Text(
            role.icon,
            style: const TextStyle(fontSize: 14),
          ),
          if (showName) ...[
            const SizedBox(width: 6),
            Text(
              role.displayName,
              style: TextStyle(
                color: textColor,
                fontSize: 12,
                fontWeight: FontWeight.w500,
              ),
            ),
          ],
        ],
      ),
    );
  }
}