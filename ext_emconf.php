<?php
/**
 * Class description
 *
 * @author Julian Seitz <julian.seitz@hdnet.de
 */

$EM_CONF[$_EXTKEY] = [
    'title'          => 'Cache-Check',
    'description'    => 'Check the caches of the TYPO3 caching framework. Provides functionality to analyze and compare installed caches.',
    'category'       => 'module',
    'version'        => '0.5.0',
    'state'          => 'beta',
    'author'         => 'Julian Seitz, Tim Lochmüller',
    'author_email'   => 'julian.seitz@hdnet.de, tim.lochmueller@hdnet.de',
    'author_company' => 'hdnet.de',
    'constraints'    => [
        'depends'   => [
            'typo3'      => '6.2.0-6.2.99',
            'autoloader' => '1.5.6-1.5.99',
        ],
        'conflicts' => [],
        'suggests'  => [],
    ],
    'suggests'       => [],
];
