(function()
{
	angular
		.module('WithdrawForm', [])
		.controller('FormController', FormController);

	FormController.$inject = ['$scope'];

	function FormController($scope)
	{
		var vm = this;

		vm.purses = [];

		vm.purseNumber = null;

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
	}

})();