<?php namespace App\File\Model;

use App\File\FileFactory;

class FileModel extends \Core\Base\Model
{
	protected $table = 'file';


	public static function _t()
	{
		$me = new static([
			//'file_name' => 'path/to/file.jpg',
		]);

		$v = $me->onlyAttributes([
			'uri_dir_status',
			'uri',
			'url',
			'path',
			'uri_dir_thumb',
			'uri_thumb',
			'url_thumb',
			'path_thumb',
		]);

		pr($v);
	}

	/**
	 * Gan url mac dinh
	 *
	 * @param string $value
	 */
	protected function setUrlDefaultAttribute($value)
	{
		$this->additional['url_default'] = $value;
	}

	/**
	 * Lay url mac dinh
	 *
	 * @return string
	 */
	protected function getUrlDefaultAttribute()
	{
		return array_get($this->additional, 'url_default') ?: public_url('img/no_image.png');
	}

	/**
	 * Lay duong dan cua file tinh tu thu muc tuong ung voi loai file (public || private)
	 *
	 * @return string
	 */
	protected function getUriDirStatusAttribute()
	{
		$dir = $this->getAttribute('status') ? 'private' : 'public';

		return $dir.'/'.$this->getAttribute('file_name');
	}

	/**
	 * Get uri attribute
	 *
	 * @return string
	 */
	protected function getUriAttribute()
	{
		return FileFactory::service()->uploadUri($this->getAttribute('uri_dir_status'));
	}

	/**
	 * Get url attribute
	 *
	 * @return string
	 */
	protected function getUrlAttribute()
	{
		if ( ! $this->getAttribute('file_name'))
		{
		    return $this->getAttribute('url_default');
		}

		$uri = $this->getAttribute('uri_dir_status');

		return FileFactory::service()->uploadUrl($uri, $this->getAttribute('server'));
	}

	/**
	 * Get path attribute
	 *
	 * @return string
	 */
	protected function getPathAttribute()
	{
		$uri = $this->getAttribute('uri_dir_status');

		return FileFactory::service()->uploadPath($uri);
	}

	/**
	 * Lay duong dan cua file tinh tu thu muc public_thumb
	 *
	 * @return string
	 */
	protected function getUriDirThumbAttribute()
	{
		return 'public_thumb/'.$this->getAttribute('file_name');
	}

	/**
	 * Get uri attribute
	 *
	 * @return string
	 */
	protected function getUriThumbAttribute()
	{
		return FileFactory::service()->uploadUri($this->getAttribute('uri_dir_thumb'));
	}

	/**
	 * Get url attribute
	 *
	 * @return string
	 */
	protected function getUrlThumbAttribute()
	{
		if ( ! $this->getAttribute('file_name'))
		{
			return $this->getAttribute('url_default');
		}

		$uri = $this->getAttribute('uri_dir_thumb');

		return FileFactory::service()->uploadUrl($uri, $this->getAttribute('server'));
	}

	/**
	 * Get path attribute
	 *
	 * @return string
	 */
	protected function getPathThumbAttribute()
	{
		$uri = $this->getAttribute('uri_dir_thumb');

		return FileFactory::service()->uploadPath($uri);
	}

	/**
	 * Tao doi tuong moi tu filename
	 *
	 * @param string $file_name
	 * @return static
	 */
	public static function newFromFileName($file_name)
	{
		return new static([
			'file_name' => $file_name,
			'status'    => 0,
			'server'    => FileFactory::service()->configUpload('server.status'),
		]);
	}

}