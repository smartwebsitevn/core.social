<?php namespace App\Product\Service;

use App\Product\Library\ProductType;
use App\Product\Model\ProductModel as ProductModel;
use App\Product\ProductFactory;
use App\User\UserFactory as UserFactory;
use App\User\Model\UserGroupModel as UserGroupModel;

class ProductService
{
	/**
	 * Them moi
	 *
	 * @param array $data
	 * @return ProductModel
	 */
	public function add(array $data)
	{
		return ProductModel::create($data);
	}
	/**
	 * Chinh sua
	 *
	 * @param ProductModel $product
	 * @param array $data
	 * @return ProductModel
	 */
	public function edit(ProductModel $product, array $data)
	{
		$product->update($data);

		return $product;
	}

	/**
	 * Xoa
	 *
	 * @param ProductModel $product
	 */
	public function delete(ProductModel $product)
	{
		$product->delete();

		t('load')->helper('file');
		file_del_table($product->getTable(), $product->id);
	}

	/**
	 * Lay discount cua product
	 *
	 * @param ProductModel   $product
	 * @param UserGroupModel $user_group
	 * @return float
	 */
	public function getDiscount(ProductModel $product, UserGroupModel $user_group = null)
	{
		$user_group = $user_group ?: UserFactory::auth()->userGroup();

		// Lay discount cua product gan cho user_group
		$discount = array_get($product->discounts, (int) $user_group->id);

		// Neu khong ton tai thi lay theo discount mac dinh cua user_group
		$discount = $discount ?: $user_group->discount;

		return (float) $discount;
	}

	/**
	 * Lay gia cua product theo user_group
	 *
	 * @param ProductModel   $product
	 * @param UserGroupModel $user_group
	 * @return float
	 */
	public function getPriceUser(ProductModel $product, UserGroupModel $user_group = null)
	{
		$discount = $this->getDiscount($product, $user_group);

		return $product->price * (100 - $discount) * 0.01;
	}

	/**
	 * Lay so luong san pham trong kho
	 *
	 * @param ProductModel $product
	 * @return int
	 */
	public function getAvailable(ProductModel $product)
	{
		$provider_key = $product->provider_key;

		$provider_service = ProductFactory::providerService($provider_key);

		$availables = $provider_service->getAvailables([$product]);

		return array_get($availables, $product->id, -1);
	}

}