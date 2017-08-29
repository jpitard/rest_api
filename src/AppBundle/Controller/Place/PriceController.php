<?php

namespace AppBundle\Controller\Place;

use AppBundle\Form\PriceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Place;
use AppBundle\Entity\Price;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class PriceController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"price"})
     * @Rest\Get("/places/{id}/prices")
     */
    public function getPricesAction(Request $request)
    {
        $place = $this->get('doctrine.orm.entity_manager')
            ->getRepositoty('AppBundle:Place')
            ->find($request->get('id'));

        if (empty($place)){
            return $this->placeNotFound();
        }
        return $place->getPrices();

    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"price"} )
     * @Rest\Post("/places/{id}/prices")
     */
    public function postPricesAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $place = $em
            ->getRepository('AppBundle:Place')
            ->find($request->get('id'));

        if (empty($place)){
            return $this->placeNotFound();
        }

        $price = new Price();
//
        $price->setPlace($place);
        //return $price;

        $form =  $this->createForm(PriceType::class, $price);


       // return $request->request;
        $form->submit($request->request->all());

        if ($form->isValid()){

           $em->persist($price);
           $em->flush();
           return $price;
        }else{
            return $form;
        }

    }

    private function placeNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
    }
}
