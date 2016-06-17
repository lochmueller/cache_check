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
    'version'        => '1.0.0',
    'state'          => 'beta',
    'author'         => 'Julian Seitz, Tim Lochmüller',
    'author_email'   => 'julian.seitz@hdnet.de, tim.lochmueller@hdnet.de',
    'author_company' => 'hdnet.de',
    'constraints'    => [
        'depends'   => [
            'php'      => '5.5.0-0.0.0',
            'typo3'      => '6.2.0-8.1.99',
            'autoloader' => '2.0.0-0.0.0',
        ],
        'conflicts' => [],
        'suggests'  => [],
    ],
    'suggests'       => [],
];
