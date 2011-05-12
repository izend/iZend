<?php

/**
 *
 * @copyright  2010-2011 izend.org
 * @version    1
 * @link       http://www.izend.org
 */

function install($lang) {
	head('title', translate('install:title', $lang));
	head('description', false);
	head('keywords', false);
	head('robots', 'noindex, nofollow');

	$languages='install';
	$contact=true;
	$banner = build('banner', $lang, compact('languages', 'contact'));

	$configure = build('configure', $lang);

	$content = view('install', $lang, compact('configure'));

	$output = layout('standard', compact('banner', 'content'));

	return $output;
}

