<?php

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

function template_th_livesearch()
{
	global $context, $modSettings, $txt, $scripturl;

	echo '
			<form id="search_form" action="', $scripturl, '?action=search;sa=results" method="post" lsstop-dropdown accept-charset="UTF-8">
				<label for="livesearch">
					<input type="text" name="search" id="livesearch" value="" class="input_text" placeholder="', $txt['search'], '" ng-model="search.term" ng-change="search.liveSearch()" ng-click="search.toggle()"/>
				</label>';

	$selected = !empty($context['current_topic']) || !empty($context['current_board']) ? 'current_board' : 'all';

	echo '
				<label for="search_selection">
				<select ng-model="search.type" ng-change="search.toggle()" name="search_selection" id="search_selection">
					<option value="all"', ($selected == 'all' ? ' ng-selected="selected"' : ''), '>', $txt['search_entireforum'], ' </option>';

	// Can't limit it to a specific board if we are not in one
	if (!empty($context['current_board']) || !empty($context['current_topic']))
		echo '
					<option value="board"', ($selected == 'current_board' ? ' ng-selected="selected"' : ''), '>', $txt['search_thisbrd'], '</option>';

	if (!empty($context['additional_dropdown_search']))
		foreach ($context['additional_dropdown_search'] as $name => $engine)
			echo '
					<option value="', $name, '">', $engine['name'], '</option>';

	echo '
					<option value="members"', ($selected == 'members' ? ' ng-selected="selected"' : ''), '>', $txt['search_members'], ' </option>
				</select>
				</label>';

	if (!empty($context['current_topic']))
		echo '
				<input type="hidden" name="', (!empty($modSettings['search_dropdown']) ? 'sd_topic' : 'topic'), '" value="', $context['current_topic'], '" />';
	// If we're on a certain board, limit it to this board ;).
	elseif (!empty($context['current_board']))
		echo '
				<input type="hidden" name="', (!empty($modSettings['search_dropdown']) ? 'sd_brd[' : 'brd['), $context['current_board'], ']"', ' value="', $context['current_board'], '" />';

	echo '
				<button type="submit" name="search;sa=results" value="', $txt['search'], '" class="button_submit', (!empty($modSettings['search_dropdown'])) ? ' with_select' : '', '"><i class="fa fa-search fa-lg"></i></button>
				<input type="hidden" name="advanced" value="0" />';
	template_livesearch_dropresults();
	echo '
			</form>';
}	

function template_livesearch_dropresults()
{
	global $scripturl, $txt;

	echo '
		<div class="search_container" ng-cloak ng-show="search.isVisible()" lsstop-dropdown>
			<ul>
				<li class="message" ng-repeat="message in search.msgs">
				<span class="poster">
					<img class="avatar" ng-src="{{message.avatar.href}}" />
					<span class="name">{{message.poster_name}}</span>
					<a class="subject" href="', $scripturl, '?topic={{message.id_topic}}.0" ng-bind-html="search.unsafeString(message.subject)"></a>
				</span>
				</li>
				<li class="showall">
					<button type="submit"><a href="#">', $txt['live_search_all_results'], '</a></button>
				</li>
			</ul>
		</div>';
}

function template_livesearch_bodyclick_above()
{
	echo '<div ng-app="livesearch" ng-controller="LiveSearch as search" ng-click="search.hideResults()">';
}

function template_livesearch_bodyclick_below()
{
	echo '</div>';
}