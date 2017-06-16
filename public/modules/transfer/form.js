(function()
{
	angular
		.module('TransferForm', [])
		.controller('FormController', FormController);

	FormController.$inject = ['$scope', '$http'];

	function FormController($scope, $http)
	{
		var vm = this;

		vm.purses = [];

		vm.purseNumber = null;

		vm.receiverPurse = null;
		
		vm.receiverName = null;
		
		vm.urlLoadName = null;

		vm.init = function(data)
		{
			angular.extend(vm, data);

			if (vm.purseNumber == null && vm.purses.length)
			{
				vm.purseNumber = _.first(vm.purses).number;
			}
		};

		vm.purse = function()
		{
			return _.findWhere(vm.purses, {number: vm.purseNumber});
		};
		
		vm.loadReceiverName = function()
		{
			vm.receiverName = '...';

			jQuery.post(vm.urlLoadName, {purse_number: vm.receiverPurse}, function(data)
			{
				$scope.$apply(function()
				{
					vm.receiverName = data.status ? data.name : '';
				});
			}, 'json');
		};
	}

})();