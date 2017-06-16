<?php namespace App\Product\Job;

use App\Product\ProductFactory as ProductFactory;
use App\Product\Library\Provider\FindTranResponse;
use App\Product\Library\Provider\ProviderTranStatus;
use App\Product\Model\OrderModel as OrderModel;
use App\Product\Model\LogProviderRequestModel as LogProviderRequestModel;
use TF\Support\Collection;

class OrderCompletedInProvider extends \Core\Base\Job
{
	/**
	 * Doi tuong OrderModel
	 *
	 * @var OrderModel
	 */
	protected $order;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param OrderModel $order
	 */
	public function __construct(OrderModel $order)
	{
		$this->order = $order;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return bool
	 */
	public function handle()
	{
		foreach ($this->getRequests() as $request)
		{
			if ($this->isRequestSuccess($request)) return true;
		}

		return false;
	}

	/**
	 * Kiem tra request co thanh cong hay khong
	 *
	 * @param LogProviderRequestModel $request
	 * @return bool
	 */
	protected function isRequestSuccess(LogProviderRequestModel $request)
	{
		if ($request->status) return true;

		$tran = $this->findProviderTran($request);

		$this->updateLogProviderTran($request, $tran);

		return $this->isProviderTranSuccess($tran);
	}

	/**
	 * Kiem tra giao dich phat sinh ben nha cung cap co thanh cong hay khong
	 *
	 * @param FindTranResponse $tran
	 * @return bool
	 */
	protected function isProviderTranSuccess(FindTranResponse $tran)
	{
		return in_array($tran->status, [
			ProviderTranStatus::PROCESSING,
			ProviderTranStatus::SUCCESS,
		]);
	}

	/**
	 * Lay thong tin giao dich phat sinh ben nha cung cap
	 *
	 * @param LogProviderRequestModel $log
	 * @return FindTranResponse
	 */
	protected function findProviderTran(LogProviderRequestModel $log)
	{
		return ProductFactory::logProviderRequest()->findTran($log);
	}

	/**
	 * Cap nhat log giao dich phat sinh ben nha cung cap
	 *
	 * @param LogProviderRequestModel $log
	 * @param FindTranResponse        $tran
	 */
	protected function updateLogProviderTran(LogProviderRequestModel $log, FindTranResponse $tran)
	{
		$data = [];

		if ( ! $log->status && $tran->status == ProviderTranStatus::SUCCESS)
		{
		    $data['status'] = 1;
		}

		if ( ! $log->provider_tran_id && $tran->provider_tran_id)
		{
		    $data['provider_tran_id'] = $tran->provider_tran_id;
		}

		if ( ! count($log->provider_tran) && count($tran->provider_tran))
		{
			$data['provider_tran'] = $tran->provider_tran;
		}

		if (count($data))
		{
		    $log->update($data);
		}
	}

	/**
	 * Lay danh sach requests
	 *
	 * @return Collection
	 */
	protected function getRequests()
	{
		return LogProviderRequestModel::listOfInvoiceOrder($this->order->invoice_order_id);
	}

}