<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Place;
use Symfony\Component\HttpFoundation\Response;

class PlaceController extends Controller
{

    /**
     * @Route("/places", name="places_list")
     * @Method({"GET"})
     */
    public function getPlacesAction(Request $request){

        $places = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Place')
            ->findAll();
        /* @var $places Place[] */

        $formatted = [];
        foreach ($places as $place) {
            $formatted[] = [
                'id' => $place->getId(),
                'name' => $place->getName(),
                'address' => $place->getAddress(),
            ];
        }

      //  die(dump($formatted));

        return new JsonResponse($formatted);

    }

    /**
     * @Route("/places/{place_id}", name="places_one")
     * @Method({"GET"})
     */
    public function getPlaceAction(Request $request){

        $place = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Place')
            ->find($request->get('place_id'));
        /* @var $places Place[] */

        if (empty($place)){
            return new JsonResponse(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
        }


        $formatted = [
            'id' => $place->getId(),
            'name' => $place->getName(),
            'address' => $place->getAddress(),

        ];

        return new JsonResponse($formatted);

    }
}
