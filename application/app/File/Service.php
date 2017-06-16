<?php namespace App\File;

use App\File\Model\FileModel as FileModel;

class Service
{
	/**
	 * Lay config upload
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function configUpload($key = null, $default = null)
	{
		$config = config('upload', 'main');

		return array_get($config, $key, $default);
	}

	/**
	 * Tao uri upload
	 *
	 * @param string $path
	 * @return string
	 */
	public function uploadUri($path = '')
	{
		return static::configUpload('folder') . ($path ? '/'.$path : '');
	}

	/**
	 * Tao path upload
	 *
	 * @param string $path
	 * @return string
	 */
	public function uploadPath($path = '')
	{
		$uri = static::uploadUri($path);

		return rtrim(static::configUpload('path'), '/').'/'.$uri;
	}

	/**
	 * Tap url upload
	 *
	 * @param string $path
	 * @param bool   $server
	 * @return string
	 */
	public function uploadUrl($path, $server = true)
	{
		$uri = static::uploadUri($path);

		if ($server && static::configUpload('server.status'))
		{
			return static::configUpload('server.url').$uri;
		}

		return base_url($uri);
	}

}