<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/users")
     */
    public function getUsersAction(Request $request){

        $users = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->findAll();

//        $formatted = [];
//        foreach ($users as $user) {
//            $formatted[] = [
//                'id' => $user->getId(),
//                'firstname' => $user->getFirstname(),
//                'lastname' => $user->getLastname(),
//                'email' => $user->getEmail(),
//            ];
//        }
//
//        //  die(dump($formatted));
//
//        return new JsonResponse($formatted);

        return $users;

    }

    /**
     * @Rest\View()
     * @Rest\Get("/users/{id}")
     */
    public function getUserAction(Request $request){

        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->get('user_id'));
        /* @var $places Place[] */

        if (empty($user)){
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

//        $formatted = [
//            'id' => $user->getId(),
//            'firstname' => $user->getFirstname(),
//            'lastname' => $user->getLastname(),
//            'email' => $user->getEmail(),
//
//        ];

        //return new JsonResponse($formatted);

        return $user;

    }
}
