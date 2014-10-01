/**
 * Live Search
 *
 * @name      Live Search
 * @copyright Live Search contributors
 * @license   BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * @version 0.0.1
 *
 */

var liveSearch;

(function(){
	var app = angular.module('livesearch', []);

	app.directive('lsstopDropdown', function() {
		return {
			link: function(scope, elm, attrs) {
				$(elm).click(function(event) {
					event.stopPropagation();
				});
			}
		};
	});

	app.controller('LiveSearch', ['$document', '$http', '$sce', function($document, $http, $sce) {
		var search = this;
		search.type = 'all';
		search.msgs = {};
		search.show = 0;
		search.term = '';

		/**
		 * Function that fetches the results of the search
		 */
		this.liveSearch = function() {
			term = search.term.trim().replace(/ /g, '+');
			if (!search.searchable())
				return;

			clearTimeout(liveSearch);
			if (term.length > 2)
			{
				liveSearch = setTimeout(function(search) {
					$http.get(elk_scripturl + "?action=livesearch;sa=search;xml;api=json;term=" + term + ';type=' + search.type)
						.success(function(data) {
							search.msgs = data;
							search.show = 1;
						});
				}, 500, search);
			}
		};

		/**
		 * Returns if the overlay should be visible or not
		 */
		this.isVisible = function() {
			return search.show === 1;
		};

		/**
		 * Takes care of hiding the overlay setting this.show to false
		 */
		this.hideResults = function() {
			search.show = 0
		}
		/**
		 * This is attached to the search-type dropdown
		 * (all/board/external/members) and hides the results if
		 * searching something not searchable by the addon
		 */
		this.toggle = function() {
			if (search.searchable())
				search.liveSearch();
			else
				search.hideResults();
		};

		/**
		 * We know how to search only few methods
		 */
		this.searchable = function() {
			return (search.type === 'all' || search.type === 'board');
		}
		/**
		 * Returns an unsafe string (used for the body
		 * @todo Could be useful for the name as well in conjunction with the colored names addons
		 */
		this.unsafeString = function(string) {
			return $sce.trustAsHtml(string);
		};
	}]);
})();
