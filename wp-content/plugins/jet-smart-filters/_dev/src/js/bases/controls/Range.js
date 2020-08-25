import Filter from 'bases/Filter';
import filtersUI from 'includes/filters-ui';

export default class RangeControl extends Filter {
	rangeInputSelector = filtersUI.range.inputSelector;
	rangeSliderSelector = filtersUI.range.sliderSelector;
	sliderMinSelector = filtersUI.range.sliderMinSelector;
	sliderMaxSelector = filtersUI.range.sliderMaxSelector;
	sliderPrefixSelector = filtersUI.range.sliderPrefixSelector;
	sliderSuffixSelector = filtersUI.range.sliderSuffixSelector;

	constructor($container, $filter, $rangeInput, $slider, $sliderMin, $sliderMax, prefix, suffix) {
		super($filter, $container);

		this.$rangeInput = $rangeInput || this.$filter.find(this.rangeInputSelector);
		this.$slider = $slider || this.$filter.find(this.rangeSliderSelector);
		this.$sliderMin = $sliderMin || this.$filter.find(this.sliderMinSelector);
		this.$sliderMax = $sliderMax || this.$filter.find(this.sliderMaxSelector);
		this.prefix = prefix || this.$filter.find(this.sliderPrefixSelector).first().text() || false;
		this.suffix = suffix || this.$filter.find(this.sliderSuffixSelector).first().text() || false;
		this.format = filtersUI.range.getFormat(this.$slider);

		this.initSlider();
		this.processData();
		this.initEvent();
	}

	initSlider() {
		filtersUI.range.init({
			$rangeInput: this.$rangeInput,
			$slider: this.$slider,
			$sliderMin: this.$sliderMin,
			$sliderMax: this.$sliderMax,
		});
	}

	addFilterChangeEvent() {
		this.$rangeInput.on('change', () => {
			this.processData();
			this.emitFiterChange();
		})
	}

	removeChangeEvent() {
		this.$rangeInput.off();
	}

	processData() {
		let val = this.$rangeInput.val(),
			values = val.split(':');

		if (!values[0] || !values[1]) {
			this.dataValue = false;
			return;
		}

		// Prevent of adding slider defaults
		if (this.$slider.length) {
			if (values[0] && values[0] == this.min && values[1] && values[1] == this.max) {
				this.dataValue = false;
				return;
			}
		}

		if (!val) {
			this.dataValue = false;
			return;
		}

		this.dataValue = val;
	}

	setData(newData) {
		this.$rangeInput.val(newData);

		const data = newData.split(':');
		if (data[0])
			this.$sliderMin.html(filtersUI.range.getFormattedData(Number(data[0]), this.format));
		if (data[1])
			this.$sliderMax.html(filtersUI.range.getFormattedData(Number(data[1]), this.format));

		this.$slider.slider('values', [data[0], data[1]]);

		this.processData();
	}

	reset() {
		this.dataValue = false;
		this.$slider.slider('values', [this.min, this.max]);
		this.$rangeInput.val(this.min + ':' + this.max);
		this.$sliderMin.html(filtersUI.range.getFormattedData(this.min, this.format));
		this.$sliderMax.html(filtersUI.range.getFormattedData(this.max, this.format));
	}

	get min() {
		return this.$slider.data('min');
	}

	get max() {
		return this.$slider.data('max');
	}

	get activeValue() {
		if (typeof this.dataValue === 'string') {
			const data = this.dataValue.split(':');
			let value = '';

			if (data[0]) {
				if (this.prefix)
					value += this.prefix;

				value += filtersUI.range.getFormattedData(Number(data[0]), this.format);

				if (this.suffix)
					value += this.suffix;

				if (data[1])
					value += ' — ';
			}

			if (data[1]) {
				if (this.prefix)
					value += this.prefix;

				value += filtersUI.range.getFormattedData(Number(data[1]), this.format);

				if (this.suffix)
					value += this.suffix;
			}

			return value;
		} else {
			return this.dataValue;
		}
	}
}