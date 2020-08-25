export default {
	initFiltersUI() {
		const widgets = {
			'jet-smart-filters-range.default': this.range,
			'jet-smart-filters-date-range.default': this.dateRange
		};

		for (const widget in widgets) {
			const callback = widgets[widget];

			window.elementorFrontend.hooks.addAction('frontend/element_ready/' + widget, callback.bind(this));
		}
	},

	range($scope) {
		const rangeUI = window.JetSmartFilters.filtersUI.range;

		rangeUI.init({
			$container: $scope
		});
	},

	dateRange($scope) {
		const dateRangeUI = window.JetSmartFilters.filtersUI.dateRange;

		dateRangeUI.init({
			id: $scope.data('id'),
			$container: $scope
		});
	},
};