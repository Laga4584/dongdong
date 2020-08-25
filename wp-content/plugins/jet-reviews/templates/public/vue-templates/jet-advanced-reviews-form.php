<div
	class="jet-reviews-advanced__new-review-form"
	:class="{ 'jet-progress-state': reviewSubmiting }"
>
	<div class="jet-new-review-content">
		<html-textarea
			class="jet-reviews-input jet-reviews-input--textarea"
			:data-placeholder="options.labels.reviewContentPlaceholder"
			ref="reviewContent"
			v-model="reviewContent"
		></html-textarea>
	</div>

	<div class="jet-new-review-title">
		<html-textarea
			class="jet-reviews-input"
			:data-placeholder="options.labels.reviewTitlePlaceholder"
			v-model="reviewTitle"
		></html-textarea>
	</div>

	<div class="jet-new-review-fields">
		<div
			class="jet-new-review-field jet-reviews-range-input"
			v-for="(field, index) in fields"
			:key="index"
		>
			<span class="jet-new-review-field-label">{{ field.field_label }}</span>
			<input tabindex="0" type="range" min="0" :step="field.field_step" :max="field.field_max" v-model="field.field_value">
			<span class="jet-new-review-field-value">{{ field.field_value }}</span>
		</div>
	</div>

	<div
		class="jet-new-review-controls"
	>
		<div
			class="jet-reviews-button jet-reviews-button--secondary"
			tabindex="0"
			@click="cancelSubmit"
			@keyup.enter="cancelSubmit"
		>
			<div class="jet-reviews-button__text">{{ options.labels.cancelButtonLabel }}</div>
		</div>
		<div
			v-if="formControlsVisible"
			class="jet-reviews-button jet-reviews-button--primary"
			tabindex="0"
			@click="submitReview"
			@keyup.enter="submitReview"
		>
			<div class="jet-reviews-button__text">{{ options.labels.submitReviewButton }}</div>
		</div>
	</div>

	<div
		class="jet-new-review-message"
		v-if="formMessageVisible"
	>
		<span>{{ messageText }}</span>
	</div>

</div>
