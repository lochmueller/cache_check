<?php

/**
 * Base TCA generation for the model HDNET\\CacheCheck\\Domain\\Model\\Log
 */

$base = \HDNET\Autoloader\Utility\ModelUtility::getTcaInformation('HDNET\\CacheCheck\\Domain\\Model\\Log');

$custom = array();

return \HDNET\Autoloader\Utility\ArrayUtility::mergeRecursiveDistinct($base, $custom);