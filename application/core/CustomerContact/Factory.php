<?php namespace Core\CustomerContact;

class Factory
{
	/**
	 * Danh sach params
	 *
	 * @var array
	 */
	protected $params = ['email', 'name', 'phone', 'address'];

	/**
	 * Doi tuong storage
	 *
	 * @var Storage
	 */
	protected $storage;


	public static function _t()
	{
		$me = new static();

		$v = $me->getContact();

//		$v->update(['name' => 'abcd']);

		pr($v);
	}

	/**
	 * Khoi tao doi tuong
	 *
	 * @param array   $params
	 * @param Storage $storage
	 */
	public function __construct(array $params = null, Storage $storage = null)
	{
		if ($params)
		{
			$this->params = $params;
		}

		$this->storage = $storage ?: new Storage('customer_contact');
	}

	/**
	 * Luu contact
	 *
	 * @param array $contact
	 */
	public function setContact(array $contact)
	{
		$contact = array_only($contact, $this->getParams());

		$contact = array_filter($contact);

		$this->storage->set($contact);
	}

	/**
	 * Lay contact
	 *
	 * @param array $defaults
	 * @return Contact
	 */
	public function getContact(array $defaults = [])
	{
		$contact = $this->storage->get();

		$contact = is_array($contact) ? array_filter($contact) : [];

		$contact = array_merge($defaults, $contact);

		return $this->makeContact($contact);
	}

	/**
	 * Tao doi tuong Contact
	 *
	 * @param array $contact
	 * @return Contact
	 */
	public function makeContact(array $contact)
	{
		return new Contact($this, $contact);
	}

	/**
	 * Cap nhat contact
	 *
	 * @param array $attributes
	 */
	public function updateContact(array $attributes)
	{
		$this->getContact()->update($attributes);
	}

	/**
	 * Xoa thong tin contact
	 */
	public function deleteContact()
	{
		$this->storage->delete();
	}

	/**
	 * Lay params
	 *
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * Lay doi tuong Storage
	 *
	 * @return Storage
	 */
	public function getStorage()
	{
		return $this->storage;
	}

}