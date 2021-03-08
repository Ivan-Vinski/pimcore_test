<?php

namespace AppBundle\Services;

use Pimcore\Model\DataObject\ClassDefinition\DynamicOptionsProvider\SelectOptionsProviderInterface;
use Pimcore\Model\DataObject\Configuration;

class OptionsProviderService implements SelectOptionsProviderInterface
{
	/**
	 * @param array $context
	 * @param Data $fieldDefinition
	 * @return array
	 */
	public function getOptions($context, $fieldDefinition) : array
	{
		$conf = \Pimcore\Model\WebsiteSetting::getByName('configuration')->getData();
		foreach ($conf->getSelectOptions() as $selectOption) {
			$result[] = [
				'key' => $selectOption['selectOptionKey']->getData(),
				'value' => $selectOption['selectOptionValue']->getData()
			];
		}

		return $result;
	}

	public function getDefaultValue($context, $fieldDefinition)
	{
		return $fieldDefinition->getDefaultValue();
	}

	public function hasStaticOptions($context, $fieldDefinition)
	{
		return true;
	}
}
