<?php
/**
 * Settings template
 */
?><div id="jet-reviews-settings-page" class="jet-reviews-admin-page jet-reviews-admin-page--settings-page">
	<div class="jet-reviews-admin-page__header">
		<h1 class="wp-heading-inline"><?php _e( 'Settings', 'jet-reviews' ); ?></h1>
	</div>
	<hr class="wp-header-end">
	<div class="jet-reviews-admin-page__content">
		<div class="cx-vui-panel">

			<cx-vui-tabs
				:in-panel="false"
				:value="activeTab"
				layout="vertical"
				@input="tabSwitch"
			>

				<cx-vui-tabs-panel
					v-for="( postData, index ) in avaliablePostTypes"
					:name="`${postData.value}-post-type-settings`"
					:label="pageOptions[`${postData.value}-type-settings`]['name']"
					:key="`${postData.value}-post-type-settings`"
				>
					<cx-vui-switcher
						:name="`allowed-post-type-${postData.value}`"
						label="<?php _e( 'Use review for post type', 'jet-reviews' ); ?>"
						description="<?php _e( 'Allow this type of post to use JetReviews', 'jet-reviews' ); ?>"
						:wrapper-css="[ 'equalwidth' ]"
						:return-true="true"
						:return-false="false"
						v-model="pageOptions[`${postData.value}-type-settings`]['allowed']"
					>
					</cx-vui-switcher>

					<cx-vui-component-wrapper
						:wrapper-css="[ 'fullwidth-control' ]"
						:conditions="[
							{
								input: pageOptions[`${postData.value}-type-settings`]['allowed'],
								compare: 'equal',
								value: true,
							}
						]"
					>

						<cx-vui-select
							:name="`${postData.value}-review-type`"
							label="<?php _e( 'Review type', 'jet-reviews' ); ?>"
							description="<?php _e( 'Choose review type for post type', 'jet-reviews' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							size="fullwidth"
							:options-list="reviewTypeOptions"
							v-model="pageOptions[`${postData.value}-type-settings`]['review_type']">
						</cx-vui-select>

						<cx-vui-f-select
							:name="`${postData.value}-allowed-roles`"
							label="<?php _e( 'Allowed roles', 'jet-reviews' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							:placeholder="'Select role...'"
							:multiple="true"
							:options-list="allRolesOptions"
							v-model="pageOptions[`${postData.value}-type-settings`]['allowed_roles']"
						></cx-vui-f-select>

						<cx-vui-select
							v-if="verificationVisible"
							:name="`${postData.value}-review-verification`"
							label="<?php _e( 'Review author verification type', 'jet-reviews' ); ?>"
							description="<?php _e( 'Choose review author verification label type', 'jet-reviews' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							size="fullwidth"
							:options-list="verificationOptions"
							v-model="pageOptions[`${postData.value}-type-settings`]['verification']">
						</cx-vui-select>

						<cx-vui-switcher
							:name="`need-approve-review-${postData.value}`"
							label="<?php _e( 'New review approval', 'jet-reviews' ); ?>"
							description="<?php _e( 'Need admin approval for a new review', 'jet-reviews' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							:return-true="true"
							:return-false="false"
							v-model="pageOptions[`${postData.value}-type-settings`]['need_approve']"
						>
						</cx-vui-switcher>

						<cx-vui-switcher
							:name="`review-comments-allowed-${postData.value}`"
							label="<?php _e( 'Allow comments', 'jet-reviews' ); ?>"
							description="<?php _e( 'Allow review comments for this type of post', 'jet-reviews' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							:return-true="true"
							:return-false="false"
							v-model="pageOptions[`${postData.value}-type-settings`]['comments_allowed']"
						>
						</cx-vui-switcher>

						<cx-vui-switcher
							:name="`review-comments-need-approve-${postData.value}`"
							label="<?php _e( 'New review comments need approval', 'jet-reviews' ); ?>"
							description="<?php _e( 'Need admin approval for a new review comment', 'jet-reviews' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							:return-true="true"
							:return-false="false"
							v-model="pageOptions[`${postData.value}-type-settings`]['comments_need_approve']"
						>
						</cx-vui-switcher>

						<cx-vui-switcher
							:name="`review-approval-allowed-${postData.value}`"
							label="<?php _e( 'Allow review approval actions', 'jet-reviews' ); ?>"
							description="<?php _e( 'Allow likes/dislikes for review items for this type of post', 'jet-reviews' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							:return-true="true"
							:return-false="false"
							v-model="pageOptions[`${postData.value}-type-settings`]['approval_allowed']"
						>
						</cx-vui-switcher>

					</cx-vui-component-wrapper>

				</cx-vui-tabs-panel>

			</cx-vui-tabs>

		</div>
	</div>
</div>
