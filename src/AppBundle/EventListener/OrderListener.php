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

class OrderListener 
{
	public function onOrderCreation(ElementEventInterface $e)
	{
		if ($e->getElement() instanceof Order) {
			$newOrder = $e->getElement();
			
			$listing = new Order\Listing();
			$listing->setOrderKey('o_id');
			$listing->setOrder('desc');
			$listing->setLimit(1);
			$lastOrder = $listing->getObjects()[0];

			if (!$lastOrder) {
				$newOrder->setKey(date('Y').'-00001');
				return;
			}
			
			$lastKey = $lastOrder->getKey();
			$numberPart = (explode('-', $lastKey))[1];
			$newNumberPart = intval($numberPart) + 1;
			$newNumberPart = str_pad($newNumberPart, 6 - strlen($newNumberPart), '0', STR_PAD_LEFT);
			$newOrder->setKey(date('Y')."-$newNumberPart");
		}
	}

	public function preOrderUpdate(ElementEventInterface $e)
	{
		if ($e->getElement() instanceof Order) {
			$order = $e->getElement();
			$order->setDependent($order->getPrimary() / 5);
			//$order->save();
		}
	}

}

