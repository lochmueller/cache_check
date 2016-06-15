<?php

use HDNET\Autoloader\Utility\ArrayUtility;
use HDNET\Autoloader\Utility\ModelUtility;
use HDNET\CacheCheck\Domain\Model\Log;

$base = ModelUtility::getTcaInformation(Log::class);

$custom = [];

return ArrayUtility::mergeRecursiveDistinct($base, $custom);
