<?php

namespace AppBundle\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use Pimcore\Model\Document\Editable\Area\Info;
use Pimcore\Model\DataObject\Folder;
use Pimcore\Model\Document\Listing;
use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\Concrete;

class ProductListBrick extends AbstractTemplateAreabrick
{
    public function getName()
    {
        return 'Product list';
    }

    public function getDescription()
    {
        //return 'Embed contents from other URL (websites) via iframe';
    }

    public function getTemplateLocation()
    {
        return static::TEMPLATE_LOCATION_GLOBAL;
    }

    public function getTemplateSuffix()
    {
        return static::TEMPLATE_SUFFIX_TWIG;
    }

    public function action(Info $info)
    {
        $currentIndex = $info->getEditable()->currentIndex['key'];
        $editables = $info->getDocument()->getEditables();
        //dd($editables);
        $relation = $editables["myAreaBlock:$currentIndex.folders"];

        foreach ($relation->elementIds as $folderElement) {
            $folderIds[] = $folderElement['id'];
        }


        //dump($folderIds);

        $list = new \Pimcore\Model\DataObject\Product\Listing();

        $list->setCondition('o_parentId IN (?)', $folderIds);

        dd($list->getObjects());

        dump($relation);
        //dd($relation->getDao());

        $folders = $relation->getElements();
        /*
        foreach ($folders as $folder) {
            $products = $folder->getChildren();
            $final[$folder->getKey()] = $products;
        }

        //dump($final);
        //dump($products);
        //dump($folders[0]->getChildren());


        dump($folders);

        $folder = Folder::getById($relation->elementIds[0]['id']);
        $list = new \Pimcore\Model\DataObject\Listing\Concrete;
        $list->setC

        //dd($list);
        //$folder->setCondition("o_id = 1147");

        //dd($folder->getList());

        */

        //$info->getView()->folders = $folders;
        //$info->view->folders = $folders;

        //$info->final = $final;
    }
}
