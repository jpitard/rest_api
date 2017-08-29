<?php

namespace AppBundle\Controller;

use AppBundle\Form\UserType;
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
            //return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
            return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
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


    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/users")
     */
    public function postPlacesAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->submit($request->request->all());

        if ($form->isValid()){

            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($user);
            $em->flush();

            return $user;

        }else{

            return $form;
        }

    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/users/{id}")
     */
    public function removePlaceAction(Request $request){
        $em = $this->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('AppBundle:User')
            ->find($request->get('id'));

        if ($user){
            $em->remove($user);
            $em->flush();
        }

    }

    /**
     * @Rest\View()
     * @Rest\Put("/users/{id}")
     */
    public function updateUserAction(Request $request){

        return $this->updateUser($request, true);
    }

    /**
     * @Rest\View()
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction(Request $request)
    {
        return $this->updateUser($request, false);
    }

    public  function updateUser(Request $request, $clearMissing){
        $em =  $this->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('AppBundle:User')->find($request->get('id'));

        if (empty($user)){
            return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $form =  $this->createForm(UserType::class, $user);
        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()){

            $em->persist($user);
            $em->flush();

            return $user;

        }else{

            return $form;
        }


    }
}
