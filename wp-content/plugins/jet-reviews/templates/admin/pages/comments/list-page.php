<?php
/**
 * Review List template
 */
?><div id="jet-reviews-comments-list-page" class="jet-reviews-admin-page jet-reviews-admin-page--comments-list">
	<div class="jet-reviews-admin-page__header">
		<h1 class="wp-heading-inline"><?php _e( 'Comment List', 'jet-reviews' ); ?></h1>
	</div>
	<hr class="wp-header-end">
	<div class="jet-reviews-admin-page__filters">
		<div class="jet-reviews-admin-page__filter">
			<label for="cx_search-review-input"><?php _e( 'Search Comments', 'jet-reviews' ); ?></label>
			<div class="jet-reviews-search-form">
				<cx-vui-input
					name="search-comment-input"
					:wrapper-css="[ 'equalwidth' ]"
					size="fullwidth"
					:prevent-wrap="true"
					type="text"
					v-model="searchText"
				>
				</cx-vui-input>
				<cx-vui-button
					button-style="accent-border"
					size="mini"
					@click="searchCommentHandle"
					:loading="commentsGetting"
				>
					<span slot="label"><?php _e( 'Search', 'jet-reviews' ); ?></span>
				</cx-vui-button>
			</div>
		</div>
	</div>

	<div class="jet-reviews-admin-page__content">
		<cx-vui-list-table
			:is-empty="0 === itemsList.length"
			empty-message="<?php _e( 'No reviews found', 'jet-reviews' ); ?>"
			class="jet-reviews-admin-page__table jet-reviews-admin-page__table--comments"
			:class="{ 'loading-status': commentsGetting || actionExecution }"
		>
			<cx-vui-list-table-heading
				:slots="[ 'author', 'content', 'post', 'date', 'actions' ]"
				slot="heading"
			>
				<div slot="author"><?php _e( 'Author', 'jet-reviews' ); ?></div>
				<div slot="content"><?php _e( 'Comment', 'jet-reviews' ); ?></div>
				<div slot="post"><?php _e( 'Assigned to', 'jet-reviews' ); ?></div>
				<div slot="date"><?php _e( 'Date', 'jet-reviews' ); ?></div>
				<div slot="actions"><?php _e( 'Actions', 'jet-reviews' ); ?></div>
			</cx-vui-list-table-heading>
			<cx-vui-list-table-item
				:class="{ 'not-approved': ! item.approved }"
				:slots="[ 'author', 'content', 'post', 'date', 'actions' ]"
				slot="items"
				v-for="( item, index ) in itemsList"
				:key="index"
			>
				<div slot="author">
					<div class="author-data">
						<a class="author-data__avatar" :href="item.author.url" v-html="item.author.avatar"></a>
						<div class="author-data__info">
							<b>{{ item.author.name }}</b>
							<i>{{ item.author.mail }}</i>
						</div>
					</div>
				</div>
				<div slot="content" v-html="item.content"></div>
				<div slot="post">
					<i>{{ item.post.type }}: </i><a class="link" target="_blank" :href="item.post.link">{{ item.post.title }}</a>
				</div>
				<div slot="date">{{ item.date.raw }}</div>
				<div slot="actions">
					<span
						class="approve-action"
						@click='approveHandler( index, item.id )'
					>
						<span v-if="item.approved" :style="{ color: '#d98500'}"><?php _e( 'Unapprove', 'jet-reviews' ); ?></span>
						<span v-if="!item.approved" :style="{ color: '#46B450'}"><?php _e( 'Approve', 'jet-reviews' ); ?></span>
					</span>
					<span>|</span>
					<span
						class="edit-action"
						@click="openEditPopup( index )"
					><?php
						_e( 'Edit', 'jet-reviews' );
					?></span>
					<span>|</span>
					<span
						class="delete-action"
						@click="openDeletePopup( index )"
					><?php
						_e( 'Delete', 'jet-reviews' );
					?></span>
				</div>
			</cx-vui-list-table-item>
		</cx-vui-list-table>
		<div
			class="jet-reviews-admin-page__pagination"
			v-if="0 !== itemsList.length"
		>
			<cx-vui-pagination
				:total="commentsCount"
				:page-size="pageSize"
				@on-change="changePage"
			></cx-vui-pagination>
		</div>
	</div>

	<transition name="popup">
		<cx-vui-popup
			class="jet-reviews-admin-page__popup"
			v-model="editPopupVisible"
			:header="false"
			:footer="false"
			body-width="600px"
		>
			<div class="cx-vui-subtitle" slot="title"><?php _e( 'Edit Comment', 'jet-reviews' ); ?></div>
			<div
				slot="content"
			>

				<cx-vui-textarea
					name="comment-content"
					label="<?php _e( 'Comment', 'jet-reviews' ); ?>"
					:wrapper-css="[ 'equalwidth' ]"
					size="fullwidth"
					v-model="editCommentData['content']"
					:rows="9"
				>
				</cx-vui-textarea>

				<div class="cx-vui-popup__controls">
					<cx-vui-button
						button-style="accent-border"
						size="mini"
						@click="saveCommentHandle"
						:loading="commentSavingState"
					>
						<span slot="label"><?php _e( 'Save', 'jet-menu' ); ?></span>
					</cx-vui-button>
				</div>

			</div>
		</cx-vui-popup>
	</transition>

	<transition name="popup">
		<cx-vui-popup
			v-model="deletePopupVisible"
			body-width="350px"
			:ok-label="'<?php _e( 'Delete', 'jet-reviews' ) ?>'"
			:cancel-label="'<?php _e( 'Cancel', 'jet-reviews' ) ?>'"
			@on-ok="deleteCommentHandle"
		>
			<div class="cx-vui-subtitle" slot="title"><?php _e( 'Please confirm comment deletion', 'jet-reviews' ); ?></div>
		</cx-vui-popup>
	</transition>
</div>
