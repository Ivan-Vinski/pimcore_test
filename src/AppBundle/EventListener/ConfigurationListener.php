<?php

namespace AppBundle\EventListener;

use Pimcore\Model\Element\ValidationException;
use Pimcore\Event\Model\ElementEventInterface;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Event\Model\AssetEvent;
use Pimcore\Event\Model\DocumentEvent;
use Pimcore\Model\DataObject\Configuration;
use Pimcore\Model\DataObject\Order;
use Pimcore\Model\DataObject\Listing;

class ConfigurationListener 
{
	public function onConfigurationCreation(ElementEventInterface $e)
	{
		if ($e->getElement() instanceof Configuration) {
			$listing = new Configuration\Listing();
			if ($listing->getObjects()) {
				throw new ValidationException('Object of type Configuration exists');
			}
		}
	}

}
