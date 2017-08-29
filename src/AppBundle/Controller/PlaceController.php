<?php

namespace AppBundle\Controller;

use AppBundle\Form\PlaceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Place;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class PlaceController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Get("/places")
     */
    public function getPlacesAction(Request $request){

        $places = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Place')
            ->findAll();
        /* @var $places Place[] */

//        $formatted = [];
//        foreach ($places as $place) {
//            $formatted[] = [
//                'id' => $place->getId(),
//                'name' => $place->getName(),
//                'address' => $place->getAddress(),
//            ];
//        }
//
//      //  die(dump($formatted));
//
//        return new JsonResponse($formatted);
        return $places;

    }

    /**
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Get("/places/{id}")
     */
    public function getPlaceAction(Request $request){

        $place = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Place')
            ->find($request->get('id'));
        /* @var $places Place[] */

        if (empty($place)){
            //return new JsonResponse(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
            return \FOS\RestBundle\View\View::create(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
        }

        return $place;

    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"place"})
     * @Rest\Post("/places")
     */
    public function postPlacesAction(Request $request)
    {
        $place = new Place();

        $form = $this->createForm(PlaceType::class, $place);
        $form->submit($request->request->all());

        if ($form->isValid()){

            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($place);
            $em->flush();

            return $place;

        }else{

            return $form;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT, serializerGroups={"place"})
     * @Rest\Delete("/places/{id}")
     */
    public function removePlaceAction(Request $request){
        $em = $this->get('doctrine.orm.entity_manager');
        $place = $em->getRepository('AppBundle:Place')
            ->find($request->get('id'));

        if (!$place) {
            return;
        }

        foreach ($place->getPrices() as $price) {
            $em->remove($price);
        }
        $em->remove($place);
        $em->flush();



    }

    /**
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Put("/places/{id}")
     */
    public function updatePlaceAction(Request $request){

        return $this->updatePlace($request, true);
    }

    /**
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Patch("/places/{id}")
     */
    public function patchPlaceAction(Request $request)
    {
        return $this->updatePlace($request, false);
    }

    public  function updatePlace(Request $request, $clearMissing){
        $em =  $this->get('doctrine.orm.entity_manager');
        $place = $em->getRepository('AppBundle:Place')->find($request->get('id'));

        if (empty($place)){
            return \FOS\RestBundle\View\View::create(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
        }

        $form =  $this->createForm(PlaceType::class, $place);
        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()){

            $em->persist($place);
            $em->flush();

            return $place;

        }else{

            return $form;
        }


    }




}
