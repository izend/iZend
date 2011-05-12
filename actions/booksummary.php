<?php

/**
 *
 * @copyright  2010-2011 izend.org
 * @version    1
 * @link       http://www.izend.org
 */

require_once 'userhasrole.php';
require_once 'models/thread.inc';

function booksummary($lang, $book) {
	$book_id = thread_id($book);
	if (!$book_id) {
		return run('error/notfound', $lang);
	}

	$r = thread_get($lang, $book_id);
	if (!$r) {
		return run('error/notfound', $lang);
	}
	extract($r); /* thread_name thread_title thread_abstract thread_cloud thread_nocloud thread_nosearch */

	if ($thread_type != 'book') {
		return run('error/notfound', $lang);
	}

	$book_name = $thread_name;
	$book_title = $thread_title;
	$book_abstract = $thread_abstract;
	$book_cloud = $thread_cloud;
	$book_nocloud = $thread_nocloud;
	$book_nosearch = $thread_nosearch;

	$book_contents = array();
	$r = thread_get_contents($lang, $book_id);
	if ($r) {
		$book_url = url('book', $lang) . '/'. $book_name;
		foreach ($r as $c) {
			extract($c);	/* node_id node_name node_title node_number */
			$book_contents[] = array($node_name, $node_title, $book_url  . '/' . $node_name);
		}
	}

	$searchbox=false;
	if (!($book_nosearch and $book_nocloud)) {
		$search_input=$search_cloud=false;
		if (!$book_nosearch) {
			$search_input = true;
			$search_text = '';
			$search_url = url('search', $lang) . '/'. $book_name;
		}
		if (!$book_nocloud) {
			$search_cloud = build('cloud', $lang, $book_id, 60, true, true);
		}
		$searchbox = view('searchbox', $lang, compact('search_input', 'search_text', 'search_url', 'search_cloud'));
	}

	head('title', $book_title);
	head('description', $book_abstract);
	head('keywords', $book_cloud);

	$edit=user_has_role('writer') ? url('bookedit', $_SESSION['user']['locale']) . '/'. $book_id . '?' . 'clang=' . $lang : false;
	$validate=url('book', $lang) . '/'. $book_name;
	$banner = build('banner', $lang, compact('edit', 'validate'));

	$sidebar = $searchbox;

	$content = view('booksummary', $lang, compact('book_title', 'book_abstract', 'book_contents'));

	$output = layout('standard', compact('banner', 'sidebar', 'content'));

	return $output;
}

