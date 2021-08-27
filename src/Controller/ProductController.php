<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use function PHPUnit\Framework\throwException;
 /**
 * @IsGranted("ROLE_USER")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product_list")
     */
    public function listProduct() 
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render(
            'product/index.html.twig', 
            [
            'products' => $products,
            ]
        );
    }

    /**
     * @Route("/product/detail/{id}", name="product_detail")
     */
    public function detailProduct($id) 
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        return $this->render(
            'product/detail.html.twig', 
            [
            'product' => $product,
            ]
        );
    }

    /**
     * @Route("/product/delete/{id}", name="product_delete")
     */
    public function deleteProduct($id) {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        if ($product == null) {
            $this->addFlash("Error","Invalid product ID");
            return $this->redirectToRoute("product_list");
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($product);
        $manager->flush();

        $this->addFlash("Success","Delete product succeed !");
        return $this->redirectToRoute('product_list');
    }

    /**
     * @Route("/product/create", name="product_create")
     */
    public function createProduct(Request $request) {
        $product = new Product();
        $form = $this->createForm(ProductFormType::class,$product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $product->getImage();

            $fileName = md5(uniqid());
            $fileExtension = $image->getExtension();
            $imageName = $fileName . '.' . $fileExtension;
            //Move
            try {
                $image->move(
                    $this->getParameter('product_image'), $imageName
                );
            }
            catch(FileException $e)
            {
                throwException($e);
            }

            //Set iamgeName to database
            $product->setImage($imageName);
            $manager = $this->getDoctrine()
                            ->getManager();
            $manager->persist($product);
            $manager->flush();
            $this->addFlash("Success","Create product successfully !");
            return $this->redirectToRoute("product_list");
        }

        return $this->render(
            "product/create.html.twig",
            [
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @Route("/product/update/{id}", name="product_update")
     */
    
    public function updateProduct(Request $request, $id) {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductFormType::class,$product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['image']->getData();
            if($uploadedFile != null){
                $image = $product->getImage();

            $fileName = md5(uniqid());
            $fileExtension = $image->getExtension(); //Lay dung duoi file
            $imageName = $fileName . '.' . $fileExtension;
            //Move: chuyen file ve thu muc
            try {
                $image->move(
                    $this->getParameter('product_image'), $imageName
                );
            }
            catch(FileException $e)
            {
                throwException($e);
            }

            //Set iamgeName to database
            $product->setImage($imageName);
        }
            $manager = $this->getDoctrine()
                            ->getManager();
            $manager->persist($product);
            $manager->flush();
            $this->addFlash("Success","Update product successfully !");
            return $this->redirectToRoute("product_list");
            
        }    

        return $this->render(
            "product/update.html.twig",
            [
                "form" => $form->createView()
            ]
        );
    }
}
