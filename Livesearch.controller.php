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

class Livesearch_Controller extends Action_Controller
{
	private $_response = array();

	public function action_index()
	{
		return $this->action_search();
	}

	public function action_search()
	{
		if (isset($_REQUEST['term']))
		{
			$term = Util::htmlspecialchars(trim($_REQUEST['term']));
			$this->_fetch($term, $this->_validType());
		}
		return $this->_return();
	}

	private function _validType()
	{
		$knownTypes = array('all', 'board');
		if (isset($_REQUEST['type']) && in_array($_REQUEST['type'], $knownTypes))
			return $_REQUEST['type'];
		else
			return 'all';
	}

	private function _fetch($term, $type)
	{
		require_once(SUBSDIR . '/LiveSearch.class.php');
		$db = database();
		$search = new Live_Search($db);
		$this->_response = $search->fetch($term, $type);
	}

	private function _return()
	{
		global $context;

		loadTemplate('Json');
		$context['sub_template'] = 'send_json';
		$context['json_data'] = $this->_response;
	}
}
