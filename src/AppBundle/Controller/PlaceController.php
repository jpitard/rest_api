<?php

namespace AppBundle\Controller;

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
     * @Rest\View()
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
     * @Rest\View()
     * @Rest\Get("/places/{id}")
     */
    public function getPlaceAction(Request $request){

        $place = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Place')
            ->find($request->get('id'));
        /* @var $places Place[] */

        if (empty($place)){
            return new JsonResponse(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
        }


//        $formatted = [
//            'id' => $place->getId(),
//            'name' => $place->getName(),
//            'address' => $place->getAddress(),
//
//        ];
//
//        return new JsonResponse($formatted);
        return $place;

    }
}
