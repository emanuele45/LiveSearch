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

class Live_Search_Integrate
{
	public static function load_theme()
	{
		Template_Layers::getInstance()->addBefore('livesearch_bodyclick', 'body');;
	}

	public static function pre_css_output()
	{
		global $context;

		if ($context['allow_search'])
		{
			$search_bar = array_search('search_bar', $context['theme_header_callbacks']);
			if ($search_bar !== false)
			{
				$context['theme_header_callbacks'] = elk_array_insert($context['theme_header_callbacks'], 'search_bar', array('livesearch'), 'after');
				unset($context['theme_header_callbacks'][$search_bar]);
			}
			else
				$context['theme_header_callbacks'][] = 'livesearch';

			loadTemplate('LiveSearch');
			loadLanguage('LiveSearch');
			loadCSSFile('LiveSearch.css');
			loadJavascriptFile(array(
				'//ajax.googleapis.com/ajax/libs/angularjs/1.2.23/angular.min.js',
				'LiveSearch.js'
			));
		}
	}
}
