<?php

	class Utils_Facebook
	{

		const SIGNED_REQUEST_ALGORITHM = 'HMAC-SHA256';

		public static function parseSignedRequest($signed_request)
		{
			list($encoded_sig, $payload) = explode('.', $signed_request, 2);

			$sig = self::base64UrlDecode($encoded_sig);
			$data = json_decode(self::base64UrlDecode($payload), true);

			if (strtoupper($data['algorithm']) !== self::SIGNED_REQUEST_ALGORITHM)
			{
				throw new Exception('Unknown algorithm. Expected ' . self::SIGNED_REQUEST_ALGORITHM);
			}

			$facebook_configs = Config::get('facebook');

			$expected_sig = hash_hmac('sha256', $payload, $facebook_configs->app_secret, $raw = true);

			if ($sig !== $expected_sig) {
				throw new Exception('Bad signed JSON signature!');
			}

			if (isset($data['expires']) && $data['expires'] < time() + 3600)
			{
				throw new Exception('Signed request has expired!');
			}

			return $data;
		}

		public static function getAuthorizeUrl()
		{
			$facebook_configs = Config::get('facebook');

			$params = array(
				'client_id'    => $facebook_configs->app_id,
				'redirect_uri' => 'http://apps.facebook.com/' . $facebook_configs->app_name . '/',
				'scope'        => implode(',', $facebook_configs->permissions)
			);

			$url = 'https://www.facebook.com/dialog/oauth?' . http_build_query($params);
			return $url;
		}

		public static function redirectViaJS($url)
		{
			echo '<script type="text/javascript">';
			echo 'top.location.href = "' . $url . '";';
			echo '</script>';

			die();
		}

		private static function base64UrlDecode($input) {
			return base64_decode(strtr($input, '-_', '+/'));
		}

	}