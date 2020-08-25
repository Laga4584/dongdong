(function( $, settingsPageConfig ) {

	'use strict';

	Vue.config.devtools = true;

	window.JetReviewsSettingsPage = new Vue( {
		el: '#jet-reviews-settings-page',

		data: {
			avaliablePostTypes: settingsPageConfig.avaliablePostTypes,
			allRolesOptions: settingsPageConfig.allRolesOptions,
			verificationOptions: settingsPageConfig.verificationOptions,
			pageOptions: settingsPageConfig.settingsData || [],
			activeTab: window.localStorage.getItem( 'jetReviewActiveSettingsTab' ) || 'page-post-type-settings',
			savingStatus: false,
			ajaxSaveHandler: null
		},

		mounted: function() {
			this.$el.className = this.$el.className + ' is-mounted';
		},

		watch: {
			pageOptions: {
				handler( options ) {
					let prepared = {};

					this.saveOptions();
				},
				deep: true
			}
		},

		computed: {
			preparedOptions: function() {
				return this.pageOptions;
			},

			reviewTypeOptions: function() {

				let reviewTypeOptions = [];

				for ( var prop in settingsPageConfig.avaliableReviewTypes ) {
					let typeData = settingsPageConfig.avaliableReviewTypes[ prop ];

					reviewTypeOptions.push( {
						label: typeData.name,
						value: typeData.slug
					} );
				}

				return reviewTypeOptions;
			},

			verificationVisible: function() {
				return this.verificationOptions.length;
			}
		},

		methods: {

			tabSwitch: function( currentTab ) {
				window.localStorage.setItem( 'jetReviewActiveSettingsTab', currentTab );
			},

			saveOptions: function() {

				let self = this;

				this.savingStatus = true;

				wp.apiFetch( {
					method: 'post',
					path: settingsPageConfig.saveSettingsRoute,
					data: {
						settings: self.preparedOptions
					},
				} ).then( function( response ) {

					if ( response.success ) {

						self.$CXNotice.add( {
							message: response.message,
							type: 'success',
							duration: 5000,
						} );
					} else {
						self.$CXNotice.add( {
							message: response.message,
							type: 'error',
							duration: 5000,
						} );
					}
				} );

			},
		}
	} );

})( jQuery, window.JetReviewsSettingsConfig );
