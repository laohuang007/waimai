-- 支付配置表
DROP TABLE IF EXISTS `la_paypal`;
CREATE TABLE `la_paypal`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付名称',
  `fee_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'USD' COMMENT '支付单位',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付图片',
  `client_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付CLIENT_ID',
  `secret_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付SECRET_KEY',
  `sort` tinyint(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '是否禁用：0-否；1-是；',
  `create_by` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `update_by` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '支付配置表';

-- 用户通道表
DROP TABLE IF EXISTS `la_paypal_user`;
CREATE TABLE `la_paypal_user`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `payid` tinyint(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '支付通道id ',
  `uid` tinyint(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id ',
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付名称',
  `fee_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'USD' COMMENT '支付单位',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付图片',
  `rate` decimal(10, 4) UNSIGNED NULL DEFAULT 0.0000 COMMENT '费率',
  `sort` tinyint(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '是否禁用：0-否；1-是；',
  `create_by` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `update_by` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户通道表';

-- 商品分类表
DROP TABLE IF EXISTS `la_goods_cate`;
CREATE TABLE `la_goods_cate`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` tinyint(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分类 0=>一级 ',
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类图片',
  `sort` tinyint(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '是否禁用：0-否；1-是；',
  `create_by` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `update_by` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '商品分类表';

-- 商品表
DROP TABLE IF EXISTS `la_goods`;
CREATE TABLE `la_goods`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cid` tinyint(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分类id ',
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '商品名称',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '商品图片',
  `amount` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '商品价格',
  `reality_amount` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '实际价格',
  `fee_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'USD' COMMENT '价格代为',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '详情内容',
  `sort` tinyint(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '是否禁用：0-否；1-是；',
  `create_by` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `update_by` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '商品表';

-- 用户商品表
DROP TABLE IF EXISTS `la_user_goods`;
CREATE TABLE `la_user_goods`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `gid` tinyint(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id ',
  `uid` tinyint(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id ',
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '商品名称',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '商品图片',
  `amount` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '商品价格',
  `reality_amount` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '实际价格',
  `fee_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'USD' COMMENT '价格代为',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '详情内容',
  `sort` tinyint(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '是否禁用：0-否；1-是；',
  `create_by` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `update_by` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户商品表';

-- 订单表
DROP TABLE IF EXISTS `la_orders`;
CREATE TABLE `la_orders`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `random` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '随机数',
  `order_sn` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '流水号',
  `paypal_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'PAYPALID',
  `gid` tinyint(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id ',
  `uid` tinyint(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id ',
  `payid` tinyint(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '支付通道id ',
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '商品名称',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '商品图片',
  `amount` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '商品价格',
  `order_amount` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '订单实际价格',
  `rate` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '手续费价格',
  `reality_amount` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '用户实际到账价格',
  `fee_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'USD' COMMENT '价格单位',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '详情内容',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '支付状态：0-待支付；1-支付成功；2-处理中,3-超时关闭,4-系统关闭',
  `status_time` int(10) NULL DEFAULT NULL COMMENT '状态变换时间',
  `pay_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付跳转地址',
  `pay_time` int(10) NULL DEFAULT NULL COMMENT '支付时间',
  `payer_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付id',
  `payer_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付返回token',
  `payer_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付人邮箱',
  `payer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付人姓名',
  `payer_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '预留扩展字段',
  `paypal_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '预留扩展字段',
  `create_by` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_by` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `update_time` int(10) NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '订单表';

-- 流水记录表
DROP TABLE IF EXISTS `la_account_log`;
CREATE TABLE `la_account_log`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '流水号',
  `uid` int NOT NULL COMMENT '用户id',
  `change_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '变动类型;[1=充值,2=提现,3=订单收款,4=后台操作]',
  `action` tinyint(1) NOT NULL DEFAULT '0' COMMENT '动作 2-减少,1-增加 ',
  `left_amount` decimal(14,2) DEFAULT '0.00' COMMENT '变动前数量',
  `change_amount` decimal(14,2) DEFAULT '0.00' COMMENT '变动数量',
  `right_amount` decimal(14,2) DEFAULT '0.00' COMMENT '变动后数量',
  `source_sn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '关联单号',
  `ip` varchar(39) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT 'ip',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '备注',
  `extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '预留扩展字段',
  `create_by` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `create_time` int DEFAULT NULL COMMENT '创建时间',
  `update_time` int DEFAULT NULL COMMENT '更新时间',
  `delete_time` int DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流水记录表';

-- 提现表
DROP TABLE IF EXISTS `la_withdraw`;
CREATE TABLE `la_withdraw`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '订单编号',
  `uid` int NOT NULL COMMENT '用户id',
  `pay_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '到账类型 1-TRC20 ',
  `wallet_address` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '钱包地址',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核状态:0-待审核;1-已审核;2-审核失败 3-取消提现',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '凭证',
  `pay_time` int DEFAULT NULL COMMENT '审核时间',
  `rate` decimal(14,2) DEFAULT '0.00' COMMENT '汇率',
  `service_charge` decimal(14,2) DEFAULT '0.00' COMMENT '手续费',
  `order_amount` decimal(14,2) DEFAULT '0.00' COMMENT '提现金额',
  `reality_amount` decimal(14,2) DEFAULT '0.00' COMMENT '到账金额',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '备注',
  `extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '预留扩展字段',
  `ip` varchar(39) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT 'ip',
  `update_by` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '后台修改的用户id',
  `create_by` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `create_time` int DEFAULT NULL COMMENT '创建时间',
  `update_time` int DEFAULT NULL COMMENT '更新时间',
  `delete_time` int DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '提现表';

-- 充值表
DROP TABLE IF EXISTS `la_recharge`;
CREATE TABLE `la_recharge`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '订单编号',
  `uid` int NOT NULL COMMENT '用户id',
  `pay_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '支付类型 1-USDT 2-银行卡 ',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核状态:0-待审核;1-已审核;2-审核失败',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '充值凭证',
  `pay_time` int DEFAULT NULL COMMENT '审核时间',
  `order_amount` decimal(14,2) DEFAULT '0.00' COMMENT '充值金额',
  `rate` decimal(14,2) DEFAULT '0.00' COMMENT '汇率',
  `service_charge` decimal(14,2) DEFAULT '0.00' COMMENT '手续费',
  `reality_amount` decimal(14,2) DEFAULT '0.00' COMMENT '到账金额',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '备注',
  `extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '预留扩展字段',
  `ip` varchar(39) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT 'ip',
  `update_by` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '后台修改的用户id',
  `create_by` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `create_time` int DEFAULT NULL COMMENT '创建时间',
  `update_time` int DEFAULT NULL COMMENT '更新时间',
  `delete_time` int DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '充值表';