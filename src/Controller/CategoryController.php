<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function PHPUnit\Framework\throwException;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category_list")
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
                        ->getRepository(Category::class)
                        ->findAll();

                        return $this->render(
                            "category/index.html.twig",
                            [
                                'categories' => $categories
                            ]
                         );
    }
    

    /**
     * @Route("/category/create", name="category_create")
     */
    public function createCategory (Request $request) {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class,$category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()
                            ->getManager();
            $manager->persist($category);
            $manager->flush();
            $this->addFlash("Success","Add category successfully !");
            return $this->redirectToRoute("category_list");
        }

        return $this->render(
            "category/create.html.twig",
            [
                "form" => $form->createView()
            ]
        );
    }


    /**
     * @Route("/category/detail/{id}", name="category_detail")
     */
    public function detailCategory ($id) {
        $category = $this->getDoctrine()
                       ->getRepository(Category::class)
                       ->find($id);
     /* SQL: "SELECT * FROM Customer WHERE id = '$id'" */

        if ($category == null) {
            $this->addFlash("Error", "Category ID in invalid");
            return $this->redirectToRoute("category_list");
        }

        return $this->render(
                        "category/detail.html.twig",
                        [
                          'category' => $category
                        ]
        );
    }

    /**
     * @Route("/category/delete/{id}", name="category_delete")
     */
    public function deleteCategory ($id) {
        $category = $this->getDoctrine()
                       ->getRepository(Category::class)
                       ->find($id);
     /* SQL: "DELETE FROM Customer WHERE id = '$id'" */
                       
        if ($category == null) {
            $this->addFlash("Error", "Category ID in invalid");
            return $this->redirectToRoute("category_list");
        }

        $manager = $this->getDoctrine()
                        ->getManager();
        $manager->remove($category);
        $manager->flush();
        $this->addFlash("Success", "Category has been removed");
        return $this->redirectToRoute("category_list");
    }
}
