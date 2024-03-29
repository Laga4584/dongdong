<div id="<?php echo 'jet-reviews-advanced-' . $uniqid ;?>" class="jet-reviews-advanced" <?php echo $options_attr; ?>>
	<div
		:class="instanceClass"
	>
		<div
			class="jet-reviews-advanced__loader"
			v-if="!reviewsLoaded"
		>
			<svg
				xmlns:svg="http://www.w3.org/2000/svg"
				xmlns="http://www.w3.org/2000/svg"
				xmlns:xlink="http://www.w3.org/1999/xlink"
				version="1.0"
				width="24px"
				height="25px"
				viewBox="0 0 128 128"
				xml:space="preserve"
			>
				<g>
					<linearGradient id="linear-gradient">
						<stop offset="0%" stop-color="#3a3a3a" stop-opacity="0"/>
						<stop offset="100%" stop-color="#3a3a3a" stop-opacity="1"/>
					</linearGradient>
				<path d="M63.85 0A63.85 63.85 0 1 1 0 63.85 63.85 63.85 0 0 1 63.85 0zm.65 19.5a44 44 0 1 1-44 44 44 44 0 0 1 44-44z" fill="url(#linear-gradient)" fill-rule="evenodd"/>
				<animateTransform attributeName="transform" type="rotate" from="0 64 64" to="360 64 64" dur="1080ms" repeatCount="indefinite"></animateTransform>
				</g>
			</svg>
		</div>

		<div
			class="jet-reviews-advanced__header"
			v-if="reviewsLoaded"
		>
			<div class="jet-reviews-advanced__header-top">
				<div class="jet-reviews-advanced__header-info">
					<h2
						class="jet-reviews-advanced__header-title"
						v-if="0 !== reviewsLength && 1 === reviewsLength"
					>
						<span>{{ reviewsLength }}</span> <?php
						_e( 'Review', 'jet-reviews' );
					?></h2>
					<h2
						class="jet-reviews-advanced__header-title"
						v-if="0 !== reviewsLength && 1 < reviewsLength"
					>
						<span>{{ reviewsLength }}</span> <?php
						_e( 'Reviews', 'jet-reviews' );
					?></h2>
					<div
						class="jet-reviews-advanced__header-title"
						v-html="options.labels.noReviewsLabel"
						v-if="0 === reviewsLength"
					></div>
					<div
						v-if="userCanReview"
						class="jet-reviews-button jet-reviews-button--primary"
						tabindex="0"
						@click="formVisibleToggle"
						@keyup.enter="formVisibleToggle"
					>
						<span class="jet-reviews-button__icon" v-if="addReviewIcon" v-html="addReviewIcon"></span>
						<span class="jet-reviews-button__text">{{ options.labels.newReviewButton }}</span>
					</div>
					<span class="jet-reviews-message" v-if="!userCanReview">{{ canReviewMessage }}</span>
				</div>

				<div
					class="jet-reviews-advanced__summary-rating"
					v-if="!reviewsListEmpty"
				>
					<component
						:is="options.ratingLayout"
						:before="false"
						:rating="averageRating"
						:after="'points-field' === options.ratingLayout ? averageValue : false"
					></component>
				</div>
			</div>

			<jet-advanced-reviews-form
				v-if="formVisible"
				:review-fields="preparedFields"
				:options="options"
			></jet-advanced-reviews-form>
		</div>

		<div
			class="jet-reviews-advanced__reviews"
			v-if="!reviewsListEmpty"
		>
			<transition-group name="fade" tag="div">
				<jet-advanced-reviews-item
					v-for="item in reviewsList"
					:key="item.id"
					:options="options"
					:item-data="item"
				>
				</jet-advanced-reviews-item>
			</transition-group>
		</div>
	</div>
	<script type="application/ld+json">{{ structuredData }}</script>

	<?php echo $widget_refs; ?>
</div>
