<?php
/**
 * Class description
 *
 * @author Julian Seitz <julian.seitz@hdnet.de
 */

$EM_CONF[$_EXTKEY] = array(
	'title'          => 'Cache-Check',
	'description'    => 'Cache-Check. Provides functionality to analyze and compare installed caches.',
	'category'       => 'module',
	'version'        => '0.0.1',
	'shy'            => 1,
	'dependencies'   => '',
	'state'          => 'alpha',
	'author'         => 'Julian Seitz',
	'author_email'   => 'julian.seitz@hdnet.de',
	'author_company' => 'hdnet.de',
	'constraints'    => array(
		'depends'   => array(
			'typo3' => '6.2.4-0.0.0',
		),
		'conflicts' => array(),
		'suggests'  => array(),
	),
	'suggests'       => array(),
);