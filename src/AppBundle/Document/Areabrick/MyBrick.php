<?php

namespace AppBundle\Document\Areabrick;


use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

use Pimcore\Model\Document\Editable\Area\Info;

class MyBrick extends AbstractTemplateAreabrick
{
    public function getName()
    {
        return 'MyBrick';
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
        
    }
}