<?php
/**
 * Convert the cache name into the model
 *
 * @package CacheCheck\Property\TypeConverter
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Property\TypeConverter;

use TYPO3\CMS\Extbase\Property\TypeConverter\AbstractTypeConverter;

/**
 * Convert the cache name into the model
 *
 * @author Tim Lochmüller
 */
class CacheConverter extends AbstractTypeConverter {

	/**
	 * Cache repository
	 *
	 * @var \HDNET\CacheCheck\Domain\Repository\CacheRepository
	 * @inject
	 */
	protected $cacheRepository;

	/**
	 * The source types this converter can convert.
	 *
	 * @var array<string>
	 */
	protected $sourceTypes = array('string');

	/**
	 * The target type this converter can convert to.
	 *
	 * @var string
	 */
	protected $targetType = 'HDNET\\CacheCheck\\Domain\\Model\\Cache';

	/**
	 * This implementation always returns TRUE for this method.
	 *
	 * @param mixed  $source     the source data
	 * @param string $targetType the type to convert to.
	 *
	 * @return boolean TRUE if this TypeConverter can convert from $source to $targetType, FALSE otherwise.
	 */
	public function canConvertFrom($source, $targetType) {
		return $this->cacheRepository->findByName($source) !== NULL;
	}

	/**
	 * Actually convert from $source to $targetType, taking into account the fully
	 * built $convertedChildProperties and $configuration.
	 *
	 * The return value can be one of three types:
	 * - an arbitrary object, or a simple type (which has been created while mapping).
	 *   This is the normal case.
	 * - NULL, indicating that this object should *not* be mapped (i.e. a "File Upload" Converter could return NULL if no file has been uploaded, and a silent failure should occur.
	 * - An instance of \TYPO3\CMS\Extbase\Error\Error -- This will be a user-visible error message later on.
	 * Furthermore, it should throw an Exception if an unexpected failure (like a security error) occurred or a configuration issue happened.
	 *
	 * @param mixed                                                             $source
	 * @param string                                                            $targetType
	 * @param array                                                             $convertedChildProperties
	 * @param \TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface $configuration
	 *
	 * @return mixed|\TYPO3\CMS\Extbase\Error\Error the target type, or an error object if a user-error occurred
	 * @throws \TYPO3\CMS\Extbase\Property\Exception\TypeConverterException thrown in case a developer error occurred
	 * @api
	 */
	public function convertFrom($source, $targetType, array $convertedChildProperties = array(), \TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface $configuration = NULL) {
		return $this->cacheRepository->findByName($source);
	}
}
