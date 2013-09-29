<?php

namespace OAuth2\Provider;

use OAuth2\Provider;
use OAuth2\Token_Access;

class Vkontakte extends Provider
{
	public $name = 'vkontakte';

	public $uid_key = 'uid';

	public $scope = array('wall','photos','offline','friends');

	private $fields = "nickname,contacts,bdate,photo_max_orig";

	public function url_authorize()
	{
		return 'https://oauth.vk.com/authorize';
	}

	public function url_access_token()
	{
		return 'https://oauth.vk.com/access_token';
	}

	public function get_user_info(Token_Access $token)
	{
		$url = 'https://api.vk.com/method/users.get?'.http_build_query(array(
			'access_token' => $token->access_token,
			'fields' => $this->fields,
		));

		$response = json_decode(file_get_contents($url));
		$user = $response->response[0];

		// Create a response from the request
		return array(
			'uid' => isset($user->uid) ? $user->uid : "",
			'nickname' => isset($user->nickname) ? $user->nickname : "",
			'name' => isset($user->first_name && $user->last_name) ? $user->first_name.' '.$user->last_name : "",
			'email' => isset($user->email) ? $user->email : "",
			'image' => isset($user->photo_max_orig) ? $user->photo_max_orig : "",
		);
	}
}
