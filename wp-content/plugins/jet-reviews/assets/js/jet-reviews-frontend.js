( function( $, elementorFrontend, publicConfig ) {

	'use strict';

	var JetReviews = {

		eventBus: new Vue(),

		initedInstance: [],

		init: function() {

			var widgets = {
				'jet-reviews.default' : JetReviews.widgetJetReviewsSimple,
				'jet-reviews-advanced.default' : JetReviews.widgetJetReviewsAdvanced,
			};

			$.each( widgets, function( widget, callback ) {
				elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widget, callback );
			});

			JetReviews.defineVueComponents();

		},

		widgetJetReviewsSimple: function( $scope ) {
			var $target       = $scope.find( '.jet-review' ),
				settings      = $target.data( 'settings' ),
				$form         = $( '.jet-review__form', $target ),
				$submitButton = $( '.jet-review__form-submit', $target ),
				$removeButton = $( '.jet-review__item-remove', $target ),
				$message      = $( '.jet-review__form-message', $target ),
				$rangeControl = $( '.jet-review__form-field.type-range input', $target ),
				ajaxRequest   = null;

			$rangeControl.on( 'input', function( event ) {
				var $this         = $( this ),
					$parent       = $this.closest( '.jet-review__form-field' ),
					$currentValue = $( '.current-value', $parent ),
					value         = $this.val();

					$currentValue.html( value );
			} );

			$submitButton.on( 'click.widgetJetReviews', function() {
				addReviewHandle();

				return false;
			} );

			$removeButton.on( 'click.widgetJetReviews', function() {
				var $this = $( this );

				removeReviewHandle( $this );

				return false;
			} );

			function addReviewHandle() {
				var now            = new Date(),
					reviewTime     = now.getTime(),
					reviewDate     = new Date( reviewTime ).toLocaleString(),
					sendData       = {
						'post_id': settings['post_id'],
						'review_time': reviewTime,
						'review_date': reviewDate
					},
					serializeArray = $form.serializeObject();

				sendData = jQuery.extend( sendData, serializeArray );

				ajaxRequest = jQuery.ajax( {
					type: 'POST',
					url: window.jetReviewPublicConfig.ajax_url,
					data: {
						'action': 'jet_reviews_add_meta_review',
						'data': sendData
					},
					beforeSend: function( jqXHR, ajaxSettings ) {
						if ( null !== ajaxRequest ) {
							ajaxRequest.abort();
						}

						$submitButton.addClass( 'load-state' );
					},
					error: function( jqXHR, ajaxSettings ) {

					},
					success: function( data, textStatus, jqXHR ) {

						var responseType = data['type'],
							message      = data.message || '';

						if ( 'error' === responseType ) {
							$submitButton.removeClass( 'load-state' );
							$message.addClass( 'visible-state' );

							$( 'span', $message ).html( message );
						}

						if ( 'success' === responseType ) {
							location.reload();
						}
					}
				} );
			};

			function removeReviewHandle( $removeButton ) {
				var $reviewItem  = $removeButton.closest( '.jet-review__item' ),
					reviewUserId = $reviewItem.data( 'user-id' ),
					sendData     = {
						'post_id': settings['post_id'],
						'user_id': reviewUserId
					};

				ajaxRequest = jQuery.ajax( {
					type: 'POST',
					url: window.jetReviewPublicConfig.ajax_url,
					data: {
						'action': 'jet_reviews_remove_review',
						'data': sendData
					},
					beforeSend: function( jqXHR, ajaxSettings ) {
						if ( null !== ajaxRequest ) {
							ajaxRequest.abort();
						}

						$removeButton.addClass( 'load-state' );
					},
					error: function( jqXHR, ajaxSettings ) {

					},
					success: function( data, textStatus, jqXHR ) {
						var successType   = data.type,
							message       = data.message || '';

						if ( 'error' == successType ) {

						}

						if ( 'success' == successType ) {
							location.reload();
						}
					}
				} );
			};
		},

		widgetJetReviewsAdvanced: function( $scope ) {
			let $target    = $scope.find( '.jet-reviews-advanced' ),
				instanceId = $target.attr( 'id' ),
				options    = $target.data( 'options' ) || {};

			if ( ! $target[0] ) {
				return;
			}

			JetReviews.createJetReviewAdvancedInstance( instanceId, options );
		},

		defineVueComponents: function() {

			Vue.component( 'jet-advanced-reviews-form', {
				template: '#jet-advanced-reviews-form-template',

				props: {
					reviewFields: Array,
					options: Object,
				},

				data: function() {
					return ( {
						reviewSubmiting: false,
						reviewTitle: '',
						reviewContent: '',
						messageText: '',
						fields: this.reviewFields
					} );
				},

				mounted: function() {
					let self = this;

					Vue.nextTick().then( function () {
						let reviewContent = self.$refs.reviewContent,
							textarea      = reviewContent.$refs.textarea;

						textarea.focus();
					} );
				},

				computed: {
					formControlsVisible: function() {
						return '' !== this.reviewTitle && '' !== this.reviewContent;
					},

					formMessageVisible: function() {
						return '' !== this.messageText;
					},
				},

				methods: {
					cancelSubmit: function() {
						JetReviews.eventBus.$emit( 'closeNewReviewForm', { uniqId: this.options.uniqId } );
					},

					submitReview: function() {
						let self = this;

						this.reviewSubmiting = true;
						this.messageText = '';

						wp.apiFetch( {
							method: 'post',
							path: publicConfig.submitReviewRoute,
							data: {
								post: self.options.postId,
								title: self.reviewTitle,
								content: self.reviewContent,
								rating_data: self.fields,
							},
						} ).then( function( response ) {

							self.reviewSubmiting = false;

							if ( response.success ) {

								self.messageText = response.message;

								JetReviews.eventBus.$emit( 'addReview', {
									uniqId: self.options.uniqId,
									reviewData: response.data
								} );

								self.$root.currentUserData.canReview = false;
								self.$root.currentUserData.canReviewMessage = response.message;
							} else {
								self.messageText = response.message;
							}
						} );
					}
				}
			});

			Vue.component( 'jet-advanced-reviews-item', {
				template: '#jet-advanced-reviews-item-template',

				props: {
					options: Object,
					itemData: Object,
				},

				data: function() {
					return ( {
						replyFormVisible: false,
						replyText: '',
						replySubmiting: false,
						approvalSubmiting: false,
						parentComment: 0,
						commentsVisible: false,
						responseMessage: '',
						detailsVisibleState: false
					} );
				},

				computed: {

					isDetailsFieldsAvaliable: function() {
						return 1 < this.itemData.rating_data.length;
					},

					detailsVisible: function() {
						return this.isDetailsFieldsAvaliable && this.detailsVisibleState;
					},

					detailsVisible: function() {
						return this.isDetailsFieldsAvaliable && this.detailsVisibleState;
					},

					authorVerificationData: function() {
						return this.itemData.verification;
					},

					isCommentsEmpty: function() {
						return 0 === this.itemData.comments.length;
					},

					isCommentsVisible: function() {
						return !this.isCommentsEmpty && this.commentsVisible;
					},

					pinnedVisible: function() {
						return this.itemData.pinned;
					},

					commentControlsVisible: function() {
						return '' !== this.replyText;
					},

					averageRatingData: function() {
						let ratingDatalength = this.itemData.rating_data.length,
							summaryValue = 0,
							avarageValue = 0,
							summaryMax = 0,
							avarageMax = 0;

						summaryValue = this.itemData.rating_data.reduce( function( accumulator, currentValue ) {
							return accumulator + +currentValue.field_value;
						}, 0 );

						summaryMax = this.itemData.rating_data.reduce( function( accumulator, currentValue ) {
							return accumulator + +currentValue.field_max;
						}, 0 );

						avarageValue = Math.round( summaryValue / ratingDatalength );
						avarageMax = Math.round( summaryMax / ratingDatalength );

						return {
							rating: Math.round( avarageValue * 100 / avarageMax, 1 ),
							max: Math.round( avarageMax, 1 ),
							value: Math.round( avarageValue, 1 )
						};
					},

					addCommentIcon: function() {
						return this.$root.refsHtml.newCommentButtonIcon || false;
					},

					showCommentsIcon: function() {
						return this.$root.refsHtml.showCommentsButtonIcon || false;
					},

					pinnedIcon: function() {
						return this.$root.refsHtml.pinnedIcon || '<i class="fas fa-thumbtack"></i>';
					},

					likeIcon: function() {
						let emptyLike  = this.$root.refsHtml.reviewEmptyLikeIcon || '<i class="far fa-thumbs-up"></i>',
							filledLike = this.$root.refsHtml.reviewFilledLikeIcon || '<i class="fas fa-thumbs-up"></i>';

						return ! this.itemData.approval.like ? emptyLike : filledLike;
					},

					dislikeIcon: function() {
						let emptyDislike  = this.$root.refsHtml.reviewEmptyDislikeIcon || '<i class="far fa-thumbs-down"></i>',
							filledDislike = this.$root.refsHtml.reviewFilledDislikeIcon || '<i class="fas fa-thumbs-down"></i>';

						return ! this.itemData.approval.dislike ? emptyDislike : filledDislike;
					},

					userCanComment: function() {
						return this.options.commentsAllowed && this.$root.currentUserData.canComment;
					},

					userCanApproval: function() {
						return this.options.approvalAllowed && this.$root.currentUserData.canApproval;
					},

				},

				methods: {
					showReplyForm: function() {
						let self = this;

						this.replyFormVisible = !this.replyFormVisible;

						if ( this.replyFormVisible ) {
							Vue.nextTick().then( function () {
								let commentContent = self.$refs.commentContent,
									textarea       = commentContent.$refs.textarea;

								textarea.focus();
							} );
						}
					},

					cancelNewReply: function() {
						this.replyFormVisible = false;
						this.responseMessage = '';
					},

					submitNewReply: function() {
						let self = this;

						this.replySubmiting = true;

						wp.apiFetch( {
							method: 'post',
							path: publicConfig.submitReviewCommentRoute,
							data: {
								post_id: self.options.postId,
								parent_id: self.parentComment,
								review_id: self.itemData.id,
								content: self.replyText,
							},
						} ).then( function( response ) {

							self.replySubmiting = false;

							if ( response.success ) {
								self.replyFormVisible = false;
								self.replyText = '';
								self.itemData.comments.unshift( response.data );
								self.commentsVisible = true;
							} else {
								self.responseMessage = response.message;
								console.log( response.message );
							}
						} );
					},

					updateApprovalHandler: function( type ) {
						let self = this;

						this.approvalSubmiting = true;

						wp.apiFetch( {
							method: 'post',
							path: publicConfig.likeReviewRoute,
							data: {
								review_id: self.itemData.id,
								type: type,
								inc: ! self.itemData.approval[ type ]
							},
						} ).then( function( response ) {
							self.approvalSubmiting = false;

							if ( response.success ) {
								self.$set( self.itemData, 'approval', response.data.approval );
								self.$set( self.itemData, 'like', response.data.like );
								self.$set( self.itemData, 'dislike', response.data.dislike );
							} else {
								console.log( response.message );
							}
						} );
					},

					toggleCommentsVisible: function() {
						this.commentsVisible = !this.commentsVisible;
					},

					toggleRatingDetailsVisible: function() {
						this.detailsVisibleState = !this.detailsVisibleState;
					},
				}
			});

			Vue.component( 'points-field', {
				template: '#jet-advanced-reviews-point-field-template',

				props: {
					before: {
						type: [ Number, String, Boolean ],
						default: false
					},
					rating: Number,
					after: {
						type: [ Number, String, Boolean ],
						default: false
					},
				},

				data: function() {
					return ( {} );
				},

				computed: {
					isBeforeEmpty: function() {
						return false === this.before || '' === this.before;
					},

					isAfterEmpty: function() {
						return false === this.after || '' === this.after;
					},

					preparedRating: function() {

						if ( 10 > this.rating ) {
							return 10;
						}

						return this.rating;
					},

					ratingClass: function() {
						let ratingClass = 'very-high-rating';

						if ( this.rating >= 80 && this.rating <= 100 ) {
							ratingClass = 'very-high-rating';
						}

						if ( this.rating >= 60 && this.rating <= 79 ) {
							ratingClass = 'high-rating';
						}

						if ( this.rating >= 40 && this.rating <= 59 ) {
							ratingClass = 'medium-rating';
						}

						if ( this.rating >= 20 && this.rating <= 39 ) {
							ratingClass = 'low-rating';
						}

						if ( this.rating >= 0 && this.rating <= 19 ) {
							ratingClass = 'very-low-rating';
						}

						return ratingClass;
					}
				}
			});

			Vue.component( 'stars-field', {
				template: '#jet-advanced-reviews-star-field-template',

				props: {
					before: {
						type: [ Number, String, Boolean ],
						default: false
					},
					rating: Number,
					after: {
						type: [ Number, String, Boolean ],
						default: false
					},
				},

				data: function() {
					return ( {} );
				},

				computed: {
					isBeforeEmpty: function() {
						return ! this.before || '' === this.before;
					},

					isAfterEmpty: function() {
						return ! this.after || '' === this.after;
					},

					preparedRating: function() {

						if ( 10 > this.rating ) {
							return 10;
						}

						return this.rating;
					},

					emptyIcons: function() {
						let icon = this.$root.refsHtml.emptyStarIcon || '<i class="far fa-star"></i>';

						return icon.repeat( 5 );
					},

					filledIcons: function() {
						let icon = this.$root.refsHtml.filledStarIcon || '<i class="fas fa-star"></i>';

						return icon.repeat( 5 );
					},

					ratingClass: function() {
						let ratingClass = 'very-high-rating';

						if ( this.rating >= 80 && this.rating <= 100 ) {
							ratingClass = 'very-high-rating';
						}

						if ( this.rating >= 60 && this.rating <= 79 ) {
							ratingClass = 'high-rating';
						}

						if ( this.rating >= 40 && this.rating <= 59 ) {
							ratingClass = 'medium-rating';
						}

						if ( this.rating >= 20 && this.rating <= 39 ) {
							ratingClass = 'low-rating';
						}

						if ( this.rating >= 0 && this.rating <= 19 ) {
							ratingClass = 'very-low-rating';
						}

						return ratingClass;
					}
				},
			});

			Vue.component( 'html-textarea', {
				template:'<div ref="textarea" contenteditable="true" tabindex="0" @input="updateHTML"></div>',

				props:[ 'value' ],

				mounted: function () {
					this.$el.innerHTML = this.value;
				},

				methods: {
					updateHTML: function( e ) {
						this.$emit( 'input', e.target.innerHTML );
					}
				}
			});

			Vue.component( 'jet-advanced-reviews-comment', {
				template: '#jet-advanced-reviews-comment-template',

				props: {
					options: Object,
					commentData: Object,
					parentId: Number,
					parentComments: Array,
					depth: Number,
				},

				data: function() {
					return ( {
						commentsList: this.commentData.children || [],
						replySubmiting: false,
						replyFormVisible: false,
						replyText: '',
						responseMessage: ''
					} );
				},

				computed: {
					commentClass: function() {
						return '';
					},

					formControlsVisible: function() {
						return this.options.commentsAllowed;
					},

					submitVisible: function() {
						return '' !== this.replyText;
					},

					replyIcon: function() {
						return this.$root.refsHtml.replyButtonIcon || false;
					}
				},

				methods: {
					showReplyForm: function() {
						let self = this;

						this.replyFormVisible = !this.replyFormVisible;

						if ( this.replyFormVisible ) {
							this.replyText = '<b>' + this.commentData.author.name + '</b>,&nbsp;';

							Vue.nextTick().then( function () {
								let commentText = self.$refs.commentText,
									textarea    = commentText.$refs.textarea;

								JetReviews.placeCaretAtEnd( textarea );
							} );
						}
					},

					cancelNewReply: function() {
						this.replyFormVisible = false;
						this.responseMessage = '';
					},

					submitNewReply: function() {
						let self = this;

						this.replySubmiting = true;

						wp.apiFetch( {
							method: 'post',
							path: publicConfig.submitReviewCommentRoute,
							data: {
								post_id: self.options.postId,
								parent_id: 0 === self.depth ? this.commentData.id : self.parentId,
								review_id: self.commentData.review_id,
								content: self.replyText,
							},
						} ).then( function( response ) {

							self.replySubmiting = false;

							if ( response.success ) {
								self.replyFormVisible = false;
								self.replyText = '';

								if ( 0 === self.depth ) {
									self.commentData.children.unshift( response.data );
								} else {
									self.parentComments.push( response.data );
								}
							} else {
								self.responseMessage = response.message;
								console.log( response.message );
							}
						} );
					}
				}
			});
		},

		createJetReviewAdvancedInstance: function( instanceId, options ) {

			if ( JetReviews.initedInstance.includes( instanceId ) ) {
				return;
			}

			JetReviews.initedInstance.push( instanceId );

			let JetReviewAdvancedInstance = new Vue( {
				el: '#' + instanceId,

				data: {
					uniqId: instanceId,
					options: options,
					reviewsLoaded: false,
					reviewsList: [],
					reviewsPage: 1,
					currentUserData: publicConfig.currentUserData,
					currentPostData: publicConfig.currentPostData,
					reviewTypeData: publicConfig.reviewTypeData,
					formVisible: false,
					isMounted: false,
					refsHtml: {},
				},

				mounted: function() {
					let self     = this,
						refsHtml = {};

					this.isMounted = true;

					for ( var ref in this.$refs ) {
						Object.assign( refsHtml, { [ ref ]: this.$refs[ ref ].innerHTML } );
					}

					this.refsHtml = refsHtml;

					wp.apiFetch( {
						method: 'post',
						path: publicConfig.getPublicReviewsRoute,
						data: {
							post: self.options.postId,
							page: self.reviewsPage - 1,
							page_size: self.options.page_size
						},
					} ).then( function( response ) {

						self.reviewsLoaded = true;

						if ( response.success && response.data ) {
							self.reviewsList = response.data.list;
						} else {
							console.log( 'Error' );
						}
					} );

					JetReviews.eventBus.$on( 'addReview', function ( payLoad ) {

						if ( self.options.uniqId !== payLoad.uniqId ) {
							return;
						}

						self.formVisible = false;

						self.reviewsList.unshift( payLoad.reviewData );

						if ( 0 !== self.$root.currentUserData.id ) {
							self.$root.currentUserData.canReview = false;
						}

					} );

					JetReviews.eventBus.$on( 'closeNewReviewForm', function ( payLoad ) {

						if ( self.options.uniqId !== payLoad.uniqId ) {
							return;
						}

						self.formVisible = false;
					} );

				},

				computed: {
					instanceClass: function() {
						let classes = [
							'jet-reviews-advanced__instance',
						];

						if ( this.isMounted ) {
							classes.push( 'is-mounted' );
						}

						return classes;
					},

					reviewsLength: function() {
						return this.reviewsList.length;
					},

					reviewsListEmpty: function() {
						return 0 === this.reviewsList.length ? true : false;
					},

					preparedFields: function() {

						let rawFields = this.reviewTypeData.fields,
							preparedFields = [];

						for ( let fieldData of rawFields ) {
							preparedFields.push( {
								field_label: fieldData.label,
								field_value: +fieldData.max,
								field_step: +fieldData.step,
								field_max: +fieldData.max,
							} );
						}

						return preparedFields;
					},

					averageRating: function() {
						let totalRating = 0;

						totalRating = this.reviewsList.reduce( function( sum, reviewItem ) {
							return +reviewItem.rating + sum;
						}, 0 );

						return Math.round( totalRating / this.reviewsList.length, 1 );
					},

					averageValue: function() {
						let summaryValue = 0;

						for ( let reviewItem of this.reviewsList ) {
							let ratingData = reviewItem.rating_data,
								itemSummary = 0;

							for ( let ratingItem of ratingData ) {
								itemSummary += +ratingItem.field_value
							}

							summaryValue += Math.round( itemSummary / ratingData.length, 1 )
						}

						return Math.round( summaryValue / this.reviewsList.length, 1 );
					},

					averageMax: function() {
						let totalMax = 0,
							fields   = this.reviewTypeData.fields;

						totalMax = fields.reduce( function( sum, field ) {
							return +field.max + sum;
						}, 0 );

						return Math.round( totalMax / fields.length, 1 );
					},

					addReviewIcon: function() {
						return this.refsHtml.newReviewButtonIcon || false;
					},

					userCanReview: function() {
						return this.currentUserData.canReview;
					},

					canReviewMessage: function() {
						return this.currentUserData.canReviewMessage;
					},

					structuredData: function() {
						let reviewsList = [];

						reviewsList = this.reviewsList.map( function( reviewData ) {
							let fields     = reviewData.rating_data,
								totalValue = 0,
								totalMax   = 0;

							totalValue = fields.reduce( function( sum, field ) {
								return +field.field_value + sum;
							}, 0 );

							totalMax = fields.reduce( function( sum, field ) {
								return +field.field_max + sum;
							}, 0 );

							return {
								'@type': 'Review',
								'name': reviewData.title,
								'reviewBody': reviewData.content,
								'reviewRating': {
									'@type': 'Rating',
									'ratingValue': Math.round( totalValue / fields.length, 1 ).toString(),
									'bestRating': Math.round( totalMax / fields.length, 1 ).toString(),
									'worstRating': '0'
								},
								'datePublished': reviewData.date.raw,
								'author': {
									'@type': 'Person',
									'name': reviewData.author.name
								}
							};
						} );

						return {
							'@context': 'https://schema.org/',
							'@type': 'Product',
							'name': this.currentPostData.title,
							'image': this.currentPostData.image_url,
							'description': this.currentPostData.excerpt,
							'aggregateRating': {
								'@type': 'AggregateRating',
								'ratingValue': this.averageValue.toString(),
								'bestRating': this.averageMax.toString(),
								'worstRating': '0',
								'ratingCount': this.reviewsLength.toString(),
								'reviewCount': this.reviewsLength.toString()
							},
							'review': reviewsList
						};
					}
				},

				methods: {
					formVisibleToggle: function() {
						this.formVisible = !this.formVisible;
					}
				}

			} );
		},

		placeCaretAtEnd: function( el ) {
			el.focus();

			if ( 'undefined' !== typeof window.getSelection && 'undefined' !== typeof document.createRange ) {
				let range = document.createRange();

					range.selectNodeContents( el );
					range.collapse( false );

				let selection = window.getSelection();

				selection.removeAllRanges();
				selection.addRange( range );
			} else if ( 'undefined' !== typeof document.body.createTextRange ) {
				let textRange = document.body.createTextRange();

				textRange.moveToElementText( el );
				textRange.collapse( false );
				textRange.select();
			}
		}
	};

	$( window ).on( 'elementor/frontend/init', JetReviews.init );

	$.fn.serializeObject = function(){

		var self = this,
			json = {},
			push_counters = {},
			patterns = {
				"validate": /^[a-zA-Z][a-zA-Z0-9_-]*(?:\[(?:\d*|[a-zA-Z0-9_-]+)\])*$/,
				"key":      /[a-zA-Z0-9_-]+|(?=\[\])/g,
				"push":     /^$/,
				"fixed":    /^\d+$/,
				"named":    /^[a-zA-Z0-9_-]+$/
			};

		this.build = function(base, key, value){
			base[key] = value;
			return base;
		};

		this.push_counter = function(key){
			if(push_counters[key] === undefined){
				push_counters[key] = 0;
			}
			return push_counters[key]++;
		};

		$.each($(this).serializeArray(), function(){
			// skip invalid keys
			if(!patterns.validate.test(this.name)){
				return;
			}

			var k,
				keys = this.name.match(patterns.key),
				merge = this.value,
				reverse_key = this.name;

			while((k = keys.pop()) !== undefined){

				// adjust reverse_key
				reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

				// push
				if(k.match(patterns.push)){
					merge = self.build([], self.push_counter(reverse_key), merge);
				}

				// fixed
				else if(k.match(patterns.fixed)){
					merge = self.build([], k, merge);
				}

				// named
				else if(k.match(patterns.named)){
					merge = self.build({}, k, merge);
				}
			}

			json = $.extend(true, json, merge);
		});

		return json;
	};

}( jQuery, window.elementorFrontend, window.jetReviewPublicConfig ) );
