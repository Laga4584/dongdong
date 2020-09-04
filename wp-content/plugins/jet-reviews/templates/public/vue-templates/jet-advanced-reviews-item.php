<div class="jet-reviews-advanced__review">
	<div class="jet-reviews-advanced__review-header">
		<div class="jet-reviews-advanced__review-author">
			<div class="jet-reviews-user-data">
				<div
					class="jet-reviews-user-data__avatar"
					v-html="itemData.author.avatar"
				></div>
				<div class="jet-reviews-user-data__info">
					<div class="jet-reviews-user-data__info-row">
						<div class="jet-reviews-user-data__name">
							<span>{{ itemData.author.name }}</span>
							<time class="jet-reviews-published-date" :datetime="itemData.date.raw" :title="itemData.date.raw"><span>{{ itemData.date.human_diff }}</span></time>
						</div>
						<div
							class="jet-reviews-user-data__verification"
							:class="[ authorVerificationData.slug ]"
							v-if="authorVerificationData"
						>
							<span class="verification-icon" v-html="authorVerificationData.icon"></span>
							<span class="verification-label" v-html="authorVerificationData.message"></span>
						</div>
					</div>

					<div class="jet-reviews-user-data__summary-rating">
						<component
							:is="options.ratingLayout"
							:before="false"
							:rating="+itemData.rating"
							:after="'points-field' === options.ratingLayout ? averageRatingData.value : false"
						></component>
						<div
							class="jet-reviews-button jet-reviews-button--secondary"
							tabindex="0"
							v-if="isDetailsFieldsAvaliable"
							@click="toggleRatingDetailsVisible"
							@keyup.enter="toggleRatingDetailsVisible"
						>
							<span class="jet-reviews-button__text">Details</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="jet-reviews-advanced__review-misc">
			<div
				class="jet-reviews-advanced__review-pin"
				v-html="pinnedIcon"
				v-if="pinnedVisible"
			>
			</div>
		</div>
	</div>
	<div
		class="jet-reviews-advanced__review-container"
	>
		<div
			class="jet-reviews-advanced__review-fields"
			v-if="detailsVisible"
		>
			<component
				v-for="(item, index) in itemData.rating_data"
				:is="options.ratingLayout"
				:key="index"
				:before="item.field_label"
				:rating="Math.round( +item.field_value * 100 / +item.field_max )"
				:after="'points-field' === options.ratingLayout ? +item.field_value : false"
			></component>
		</div>
		<h3 class="jet-reviews-advanced__review-title" v-html="itemData.title"></h3>
		<p class="jet-reviews-advanced__review-content" v-html="itemData.content"></p>
	</div>
	<div
		class="jet-reviews-advanced__review-footer"
	>
		<div class="jet-reviews-advanced__review-controls">
			<div
				class="jet-reviews-advanced__review-control-group"
				v-if="userCanApproval"
			>
				<div
					class="jet-reviews-button jet-reviews-button--secondary"
					:class="{ 'jet-progress-state': approvalSubmiting }"
					tabindex="0"
					@click="updateApprovalHandler( 'like' )"
					@keyup.enter="updateApprovalHandler( 'like' )"
				>
					<span class="jet-reviews-button__icon" v-html="likeIcon"></span>
					<span class="jet-reviews-button__text">{{ itemData.like }}</span>
				</div>

				<div
					class="jet-reviews-button jet-reviews-button--secondary"
					:class="{ 'jet-progress-state': approvalSubmiting }"
					tabindex="0"
					@click="updateApprovalHandler( 'dislike' )"
					@keyup.enter="updateApprovalHandler( 'dislike' )"
				>
					<span class="jet-reviews-button__icon" v-html="dislikeIcon"></span>
					<span class="jet-reviews-button__text">{{ itemData.dislike }}</span>
				</div>
			</div>

			<div class="jet-reviews-advanced__review-control-group">
				<div
					v-if="!isCommentsEmpty"
					class="jet-reviews-button jet-reviews-button--primary"
					tabindex="0"
					@click="toggleCommentsVisible"
					@keyup.enter="toggleCommentsVisible"
				>
					<span class="jet-reviews-button__icon" v-if="showCommentsIcon" v-html="showCommentsIcon"></span>
					<span class="jet-reviews-button__text" v-if="!commentsVisible">{{ options.labels.showCommentsButton }}</span>
					<span class="jet-reviews-button__text" v-if="commentsVisible">{{ options.labels.hideCommentsButton }}</span>
				</div>

				<div
					v-if="userCanComment"
					class="jet-reviews-button jet-reviews-button--primary"
					tabindex="0"
					@click="showReplyForm"
					@keyup.enter="showReplyForm"
				>
					<span class="jet-reviews-button__icon" v-if="addCommentIcon" v-html="addCommentIcon"></span>
					<span class="jet-reviews-button__text">{{ options.labels.newCommentButton }}</span>
				</div>
			</div>
		</div>
		<div
			class="jet-review-new-comment"
			:class="{ 'jet-progress-state': replySubmiting }"
			v-if="replyFormVisible"
		>
			<div
				class="jet-review-new-comment-form"
			>
				<html-textarea
					class="jet-reviews-input jet-reviews-input--textarea"
					:data-placeholder="options.labels.commentPlaceholder"
					ref="commentContent"
					v-model="replyText"
				></html-textarea>
				<div
					class="jet-review-new-comment-controls"
				>
					<div
						class="jet-reviews-button jet-reviews-button--secondary"
						tabindex="0"
						@click="cancelNewReply"
						@keyup.enter="cancelNewReply"
					>
						<div class="jet-reviews-button__text">{{ options.labels.cancelButtonLabel }}</div>
					</div>
					<div
						v-if="commentControlsVisible"
						class="jet-reviews-button jet-reviews-button--primary"
						tabindex="0"
						@click="submitNewReply"
						@keyup.enter="submitNewReply"
					>
						<div class="jet-reviews-button__text">{{ options.labels.submitCommentButton }}</div>
					</div>
				</div>
				<div
					class="jet-review-new-comment-message"
					v-if="responseMessage"
				>
					<span>{{ responseMessage }}</span>
				</div>
			</div>
		</div>
		<div
			class="jet-reviews-advanced__review-comments"
			v-if="isCommentsVisible"
		>
			<h4 class="jet-reviews-advanced__comments-title">{{ options.labels.сommentsTitle }}</h4>
			<jet-advanced-reviews-comment
				v-for="comment in itemData.comments"
				:key="comment.id"
				:options="options"
				:comment-data="comment"
				:parent-id="0"
				:parent-comments="[]"
				:depth="0"
			>
			</jet-advanced-reviews-comment>
		</div>
	</div>
</div>
