<?php

namespace AppBundle\Controller;

//use Pimcore\Bundle\DataHubBundle\Configuration\Workspace\Document;

//use Pimcore\Bundle\DataHubBundle\GraphQL\ClassificationstoreFeatureQueryTypeGenerator\InputQuantityValue;
use Codeception\Command\Build;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\Document\Listing;
use Pimcore\Model\DataObject\Folder;
use Pimcore\Model\Document;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Pimcore\Db;
use Pimcore\Model\DataObject\TestClass;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Service;
use Pimcore\Model\DataObject\Data\QuantityValue;
use Pimcore\Model\DataObject\Data\InputQuantityValue;
use Pimcore\Model\Asset;
use Pimcore\Model\Asset\Image;
use Pimcore\Model\DataObject\Data\ImageGallery;
use Pimcore\Model\DataObject\Data\Hotspotimage;
use Pimcore\Model\DataObject\VacationRequest;
use Pimcore\Model\DataObject\Data\ElementMetadata;
use Pimcore\Model\DataObject\Data\BlockElement;
use Pimcore\Model\DataObject\Fieldcollection;
use Carbon\Carbon;
use CustomerManagementFrameworkBundle\DataTransformer\Zip\Ru;
use Pimcore;
use Pimcore\Bundle\DataHubBundle\Configuration\Workspace\DataObject;
use Pimcore\Http\RequestHelper;
use Pimcore\Model\DataObject\Configuration;
use Pimcore\Model\DataObject\Fieldcollection\Data\TestFieldCollection;
use Pimcore\Model\WebsiteSetting;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Pimcore\Model\DataObject\Order;
use Pimcore\Navigation\Builder;
use Pimcore\Templating\Helper;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RequestContext;
use Pimcore\Templating\Helper\Navigation;
use Symfony\Component\Security\Core\Security;
use Pimcore\Model\DataObject\Customer;

class MyController extends FrontendController
{

    public function onKernelController(FilterControllerEvent $event)
    {
        parent::onKernelController($event);
        $this->setViewAutoRender($event->getRequest(), true, 'twig');
    }

    public function testAction()
    {
        return $this->render('My/default.html.twig', ['hello' => $this->document->getTitle()]);
    }

    /**
     * @Route("/default")
     */
    public function defaultAction(Request $request)
    {
        return $this->render('My/default.html.twig', ['hello' => 'hello']);
        dd($request);
    }

    /**
     * @Route("/getDocs")
     */
    public function getDocumentsAction()
    {
        $listing = new Listing();
        $listing->setCondition("id IN (SELECT cid FROM properties WHERE tranname='my' AND ctype='document')");

        return $this->render('My/getDocuments.html.twig', ['listing' => $listing]);

    }


    public function changeTitleAction()
    {
        $this->document->setTitle('This is the change');
        dd($this->document);
    }

    /**
     * @Route("/delete-document")
     */
    public function deleteDocumentAction()
    {
        $document = \Pimcore\Model\Document::getById(207);
        //dd($document);
        if ($document) {
            $document->delete();
        }

        $page = new \Pimcore\Model\Document\Page();
        $page->setKey('ivan');
        $page->setParentId(205);

        $page->save();

        return $this->redirect('/en/default');
    }


    /**
     * @Route("/test1")
     */
    public function testClassesAction()
    {
        // Create object with "new"
        //dd(Service::getValidKey('/rand*$%^', 'object'));
        $testObj0 = new TestClass;
        //dd($testObj0);

        // Create object with "create()"
        $testObj1 = TestClass::create([
            'key' => 'testObject2',
            'input' => 'input',
            'input1' => 'input1',
            'input2' => 2,
            'parentId' => 1133,
            'published' => true
        ]);

        //$testObj1->save();
        //dd($testObj1);

        // Get object with "getById()"
        $testObj2 = TestClass::getById(1137);
        //dd($testObj2);

        $testObjListing = TestClass::getByInput1('input1');

        $testObjects = $testObjListing->getObjects();

        //dd($testObjects);

        /** @TestClass  $testObj */
        $testObj = $testObjects[0];
        //dd($testObj);

        $testObj->getInput();

        $testObj->setInput('I inserted into input type <br>');
        echo $testObj->getInput();

        $testObj->setWYSIWYG('What you see is what you get <br>');
        echo $testObj->getWYSIWYG();
        $testObj->setWYSIWYG('Changed what you see, changed what you get <br>');
        echo $testObj->getWYSIWYG();


        $testObj->setPassword('pass');
        echo $testObj->getPassword();

        $inputQuantityValue = new InputQuantityValue(2.3, 1);
        $testObj->setInputQuantityValue($inputQuantityValue);

        $testObj->setSlider(3);

        $quantityValue = new QuantityValue(2.3, 1);
        //dd($quantityValue->__toString());
        $testObj->setQuantityValue($quantityValue);

        $date = new Carbon('now');
        $testObj->setDate($date);
        echo '<br>'.$testObj->getDate();


        // MANY TO ONE
        $vr = new VacationRequest;
        $vr->setParentId(1131);
        $vr->setKey('vr2');
        $vr->setPublished(true);
        $vr->setRandom(false);
        $vr->setTotalDays(2132);
        $vr->setStartDate($date);
        $vr->setEndDate($date);
        //$vr->save();

        $testObj->setManyToOne($vr);
        //dd($testObj->getManyToOne());


        // MANY TO MANY
        $vacations = $testObj->getManyToMany();

        //dd($vacations);


        // Dohvati sve objekte klase "TestClass" koji su vezani na objekt s o_id = 1141
        $relationId = 1141;
        $list = new \Pimcore\Model\DataObject\TestClass\Listing();
        $list->setCondition("manyToMany like '%,".$relationId.",%'");
        $list->setCondition("manyToMany like '%,1141,%'");
        //dd($list->load());

        //$list->setCondition("manyToManyGeneral like '%,object|".$relationId.",%' and slider < 30");

        // dohvati sve vacatione od testa
        $vacations = $testObj->getManyToMany();
        //dd($vacationListing);

        // ADVANCED MANY TO MANY
        $vacations = $testObj->getAdvancedManyToMany();
        //dd($vacations);



        // test = $testListing->load();
        //$testId = 1137;
        $vacationListing = new \Pimcore\Model\DataObject\VacationRequest\Listing();
        //$vacationListing->setCondition("reverseManyToMany like '%,".$userId.",%'");
        $vacationListing->setCondition("manyToMany like '%,1140,%'");
        //dd($vacationListing->load());


        $testObj->setTranslatableInput("le car", "fr");
        //dd($testObj->getTranslatableInput("fr"));

        //dd($testObj);
        //dd($testObj->getFullName());
        $fullName = $testObj->getFullName();
        //dd($fullName);
        $blockElement1 = new BlockElement("firstname", "firstname", "Anakin");
        $blockElement2 = new BlockElement("lastname", "lastname", "Skywalker");

        array_push($fullName, ['firstname' => $blockElement1, 'lastname' => $blockElement2]);
        $testObj->setFullName($fullName);
        //dd($firstName);
        //dd($firstName[0]['firstname']->getData());

        //$testObj->save();
        //dd($testObj);


        $item = new TestFieldCollection();
        $item->setFieldCollectionInput("hey ho johny boy");

        $fieldCollection = $testObj->getFieldCollection();
        $fieldCollection->add($item);

        $testObj->setFieldCollection($fieldCollection);
        $testObj->save();
        dd($fieldCollection);

        $newAsset = new Asset();
        $newAsset->setFilename("myImage.jpeg");


        //$hotspotImage = new Hotspotimage();
        $imageGallery = $testObj->getImageGallery();

        dd($imageGallery);


        $testObj->save();


        dd($testObj);


        //return $this->render('My/testClasses.html.twig', ['name' => $vacationRequestObject->getSlug()]);
    }

    /**
     * @Route ("/testConfig")
     */
    public function testConfigAction()
    {
        $conf = \Pimcore\Model\WebsiteSetting::getByName('configuration');
        dd($conf->getData());

        $configuration = WebsiteSetting::getByName('configuration')->getData();
        dd($configuration->getOptions(null, null));


    }

    /**
     * @Route ("/asset")
     */
    public function assetAction(Request $request)
    {
        //dd($request);

        $formBuilder = $this->createFormBuilder();
        $formBuilder->add('title', TextType::class)
            ->add('asset', FileType::class)
            ->add('submit', SubmitType::class);

        $form = $formBuilder->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form->getData());

            $newAsset = new \Pimcore\Model\Asset();
            $newAsset->setFilename("myFilename.jpeg");

            $content = file_get_contents($form['asset']->getData()->getRealPath());
            $newAsset->setData($content);
            $newAsset->setParentId(416);
            $newAsset->setMimetype('jpeg');
            $newAsset->save();

            $image = Image::getByPath('/Testing/myFilename.jpeg');
            //dd($image);

            $testObj = TestClass::getById(1137);
            $testObj->setMyImage($image);
            //dd($testObj);
            $testObj->save();
        }

        $testObj = TestClass::getById(1137);
        $image = $testObj->getMyImage();
        //dd($image->getThumbnail('myImageThumbnail'));

        $this->view->form = $form->createView();
        return $this->render('My/asset.html.twig', [
            'form' => $form->createView(),
            'image' => $image
        ]);

        //dd($form);
    }


    /**
     * @Route("editables")
     */
    public function editablesAction()
    {
        $listing = new \Pimcore\Model\DataObject\Product\Listing();

        dd($listing->getData());
        $product = new Product();
        dd($product);
    }

    public function renderletAction()
    {

    }


    public function navTestAction(Request $request, Builder $builder)
    {

        //$navigation = $builder->getNavigation($this->document, Document::getByPath('/en'));
        //dd($navigation);`
        //$this->view->navigation = $navigation;
    }

    public function mailAction()
    {

    }

    /**
     * @Route("sendMail/{product_id}")
     */
    public function sendMailAction(Request $request)
    {
        $user = $this->getUser();
        $product = Product::getById($request->get('product_id'));

        $mail = new Pimcore\Mail();
        $mail->setDocument(Document::getByPath('/emails/testMail'));
        $mail->setTo($user->getEmail());

        $mail->setParams([
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'product_name' => $product->getName(),
            'product_price' => $product->getPrice(),
            'product' => $request->get('product_id')
        ]);

        $mail->send();

        return new JsonResponse(true);
    }


}
