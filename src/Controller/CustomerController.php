<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

 /**
 * @IsGranted("ROLE_ADMIN")
 */

class CustomerController extends AbstractController
{
    /**
     * @Route("/customer", name="customer_list")
     */
    public function listCustomer () {
        $customers = $this->getDoctrine()
                        ->getRepository(Customer::class)
                        ->findAll();
    
        return $this->render(
                        "customer/index.html.twig",
                        [
                            'customers' => $customers
                        ]
                     );
    }

    /**
     * @Route("/customer/detail/{id}", name="customer_detail")
     */
    public function detailCustomer ($id) {
        $customer = $this->getDoctrine()
                       ->getRepository(Customer::class)
                       ->find($id);
     /* SQL: "SELECT * FROM Customer WHERE id = '$id'" */

        if ($customer == null) {
            $this->addFlash("Error", "Customer ID in invalid");
            return $this->redirectToRoute("customer_list");
        }

        return $this->render(
                        "customer/detail.html.twig",
                        [
                          'customer' => $customer
                        ]
        );
    }
    /**
     * @Route("/customer/delete/{id}", name="customer_delete")
     */
    public function deleteCustomer ($id) {
        $customer = $this->getDoctrine()
                       ->getRepository(Customer::class)
                       ->find($id);
     /* SQL: "DELETE FROM Customer WHERE id = '$id'" */
                       
        if ($customer == null) {
            $this->addFlash("Error", "Customer ID in invalid");
            return $this->redirectToRoute("customer_list");
        }

        $manager = $this->getDoctrine()
                        ->getManager();
        $manager->remove($customer);
        $manager->flush();
        $this->addFlash("Success", "Customer has been deleted");
        return $this->redirectToRoute("customer_list");
    }

    /**
     * @Route("/customer/create", name="customer_create")
     */
    public function createCustomer (Request $request) {
        $customer = new Customer();
        $form = $this->createForm(CustomerFormType::class,$customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()
                            ->getManager();
            $manager->persist($customer);
            $manager->flush();
            $this->addFlash("Success","Add customer successfully !");
            return $this->redirectToRoute("customer_list");
        }

        return $this->render(
            "customer/create.html.twig",
            [
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @Route("/customer/update/{id}",  name="customer_update")
     */
    public function updateCustomer (Request $request, $id) {
        $customer = $this->getDoctrine()
                       ->getRepository(Customer::class)
                       ->find($id);

        $form = $this->createForm(CustomerFormType::class,$customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()
                            ->getManager();
            $manager->persist($customer);
            $manager->flush();
            $this->addFlash("Success","Update customer successfully !");
            return $this->redirectToRoute("customer_list");
        }

        return $this->render(
            "customer/update.html.twig",
            [
                "form" => $form->createView()
            ]
        );
    }
}