<?php
return [
		//应用ID,您的APPID。
		'app_id' => "2016092700609353",

		//商户私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEAux3pGvKYD4MF4J4KCEEJxPQIXEZrN2wpyx2pZ+2Osq5MkUsi0fbeudqmrBtXyaxLD+wPvts4FFnD/Gnzv6Vs4MWt4XSkmWkBJIXnD2m5hjJJXElpT1YWrpG/0ZujiihtkgdfKKpEl4pAcEhiSZbmmdFrBY2nvssl4rZs1EIFSxRV2UejJlNSWkv5+oxjnTSrO7O+wUzHo5KY3s6DA533dwjbi61jQzzSGJLDrhptAkk4tx4PnSj3A6e/0TcGjEJXY83K6BLHVvqLw+KSUVKDwfqT/pEEICgY3L3EBEY1P3bqiWvU3Iw6KIfRVjpV7Isj9pD5zVmK0jK+llNbA49XvwIDAQABAoIBAEpeHepu5o6rtWdLiJM1PivADZ6XAj/ZxlaK6Gx81w0fByFr6l/mrSjSxRF8IIzv5DlkyfFB8WztLF6iwZS2q6o+BtinYk/yktiwO2A91dWHIO8D4k382jDxjnpBUGM1pC64rVJdMbvE382Ah0fWKLqTp+RiI3xKAzmy5VgLEU2ADZ1oof8cwAXRbvgG57VG6yUx45vX3FqiE4Gprbiwwn5NV0W/0GRJ0mCPAN1mu5OVepWsxc5kQkQ8mKkjGpxc+qMmqdpAmlRD3vJ0q6q2cFHR4Jv5Sk2u/ck73Ccy0bVWuGM8n2IioLRXMpu2Z+H699qxxvL45K2jke/gkskb1iECgYEA8wEwc84uHCa1GM03R2nAdcRTXRQmGasE2kWTkhGAMDWP7CIqODYK4PXFo4LHs44JnNqXAO3bP9azfI9+nEFmf7V/UimAY6WOSRRLNxdClwyOxDTFLFqhtexvmIozLYDnNgmpzNtkX73zMT9NeEVtG7rn/s3QSFM5RrV5uYt05ZECgYEAxR+Zd09uGQP03tzl7x3LvH9WTzOeVBNcfjwp1gWuDqiLWROF4SGYwo4zv+BlPcfHhiEdymVBgJBwPS92J5orBR3B6nWuFwXqYtdXSzFkfN5xYVGTkBLhm+RZAbmskfql3GcZMXGA/Qg/nsD3TrwDkOFj1NjLUdzDq/5ttWJ0gE8CgYAmNMmpa4FUa8GNZLaMQ9Q+r99rgv96iaj5qHbLQHUnH0TBm52HuPd85ydrzeQYFvzr7HJRcxRFtTwDGYyOhLnY2nBqOpw1DpKQauBoiP+vQAoyTxmxH47NS59AHHpvbRTtKhfIXbYqV3MlCw7jGgdNlNVk+ncE6BDZTS8WPRbkAQKBgQCbd9ip6NOEl0JBoycV+AH/oM6JmMR+uuZwCTNVwRUOKo/8qREmBkFc8JqP8oLmvvH2L5g3ULTgmQpst0oQ4d6cImQWbsnnDptVaVFH1KTUdvwlLRmcv8eAFBC+85nPaEf4FLgh7ss0xZSnuLlUN6T4V0tNS87PubX5qKkOLaCEsQKBgBJxoJvzl+VQthLlk09k0MadaP61wDQ0ijKm6vpDcx8N3xUDwj1LRQLOS4VUDIXF8fSsD369vAqxx5jSFCC+O5DmjPJ48V+CZp/zxvFHDTvAPKFLsf6MwG+gdTMu6WdZjPWmPMlxCcbB4VTUlZUHkR8ObfjoXHel6CTFlgTAf5lQ",
		
		//异步通知地址
		'notify_url' => "http://39.105.152.37/notify",
		
		//同步跳转
		'return_url' => "http://39.105.152.37/treturn",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwPgnM83w/jIlZTed3EWJ6QKYxTIoPcBG8d5/rfdYuhMRwfNLtQvZGDFurUn1f9/UnfT332rtjlJpqI7ituBAVbi76n+CJz4QnxMRxCfsF8f6DWLqF+42RmDJGWGCYXwddLumNsMPVs0stmBBbYdDl+7CiS4iAC5A7GPd8sjFhs1Z81JHbtrlCTbRNxbwjEgt8Zi0qgbTYDtt4F4XMZ78TqJ9l9MYN+KPXavOpA7xzVfBwWU5n0OdjjBtTY2HUZhOsLGvAQxe9QjBDDDqJJJ0yLeFkwO4DaWGNMBB/KYaDXkZlxK/GKdxe4qh2Kmo+w+dYFZR2zkExQ/ZwPNcyFVyQwIDAQAB",

		"seller_id" => "2088102177527347"
];