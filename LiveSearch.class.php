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

class Live_Search
{
	private $db = null;

	public function __construct($db)
	{
		$this->db = $db;
	}

	public function fetch($term, $type, $limit = 5)
	{
		$request = $this->db->query('', '
			SELECT t.id_topic, m.subject, m.poster_time,
				IFNULL(mem.member_name, m.poster_name) AS poster_name, mem.avatar,
				IFNULL(a.id_attach, 0) AS id_attach, a.filename, a.attachment_type, mem.email_address
			FROM {db_prefix}topics AS t
				LEFT JOIN {db_prefix}messages AS m ON (t.id_first_msg = m.id_msg)
				LEFT JOIN {db_prefix}members AS mem ON (mem.id_member = m.id_member)
				LEFT JOIN {db_prefix}attachments AS a ON (a.id_member = m.id_member AND a.id_member != 0)
			WHERE m.subject LIKE {string:term}
			LIMIT {int:start}, {int:limit}',
			array(
				'term' => '%' . $term . '%',
				'start' => 0,
				'limit' => $limit,
			)
		);

		$msgs = array();
		while ($row = $this->db->fetch_assoc($request))
		{
			$row['subject'] = censorText($row['subject']);
			$row['avatar'] = determineAvatar($row);
			$row['html_time'] = htmlTime($row['poster_time']);
			$row['time'] = standardTime($row['poster_time']);
			$row['timestamp'] = forum_time(true, $row['poster_time']);
			$msgs[] = $row;
		}
		$this->db->free_result($request);

		return $msgs;
	}
}
