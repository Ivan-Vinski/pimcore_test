<?php

namespace AppBundle\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;

class VacationRequestController extends FrontendController
{
    public function defaultAction (Request $request)
    {
    }
    
    public function VacationRequestAction(Request $request)
    {
        return $this->render('/VacationRequest/vacationRequest.html.php');
    }
}