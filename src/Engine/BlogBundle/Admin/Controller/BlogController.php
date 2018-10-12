<?php

namespace Engine\BlogBundle\Admin\Controller;

use Doctrine\ORM\EntityManager;
use Engine\BlogBundle\Form\Type\Admin\BlogEditType;
use Engine\BlogBundle\Form\Type\Admin\CategoryEditType;
use Engine\BlogBundle\Entity\Blog;
use Engine\BlogBundle\Entity\BlogCategories;
use Engine\UserBundle\Entity\Group;
use Engine\UserBundle\Entity\Permission;
use Engine\UserBundle\Entity\User;
use Engine\UserBundle\Form\Type\Admin\GroupEditType;
use Engine\UserBundle\Form\Type\Admin\UserEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/admin/blog")
 */
class BlogController extends Controller{

    /**
     * @Route("/", name="blog_admin_index")
     */
    public function indexAction(Request $request) {
        $this->getAdmin($request);
        /** @var EntityManager $em */
        $em      = $this->get('doctrine')->getManager();
        return $this->render('@Blog/admin/bloglist.html.twig') ;
    }


    /**
     * @Route("/list", name="blog_admin_list")
     * 
     * @return JsonResponse
     */
    public function listAction(Request $request) {
        $this->getAdmin($request);
        /** @var EntityManager $em */
        $em      = $this->get('doctrine')->getManager();
        $allBlog = $em->getRepository('BlogBundle:Blog')->findAll();
        $data = [];
        foreach ($allBlog as $blog){
            $data[] = [
                $blog->getId(),
                $blog->getTitle(),
                $blog->getDate()->format('m/d/Y'),
            ];
        }
        return new JsonResponse(['data' => $data]);
    }

    /**
     * @Route("/edit/{id}", name="blog_admin_edit")
     */
    public function editAction(Request $request, $id = 0){
        $this->getAdmin($request);
        return $this->blogEditForm($request, $id);
    }

    /**
     * @Route("/create", name="blog_admin_create")
     */
    public function createAction(Request $request){
        $this->getAdmin($request);
        return $this->blogEditForm($request);
    }

    /**
     * @Route("/delete/{id}", name="blog_admin_delete")
     * @param Request $request
     * @param int     $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id = 0){
        $this->getAdmin($request);
        $em = $this->get('doctrine')->getManager();
        $session = $request->getSession();
        /* @var $session Session */
        
        $news = $em->getRepository("BlogBundle:Blog")->find(['id'=>$id]);
        if(!$news){
            $session->getFlashBag()->add('error', 'News with id ' . $id . ' not found!');
            return $this->redirectToRoute("blog_admin_index");
        }
        try{
            $message = $message = "Article \"{$news->getTitle()}\" was successfully removed";
            
            $em->remove($news);
            $em->flush();
            
            $session->getFlashBag()->add("success", $message);
            return $this->redirectToRoute("blog_admin_index");
        }
        catch (Exception $exception){
            $session->getFlashBag()->add('error', 'Something went wrong: '.$exception->getMessage());
            return $this->redirectToRoute("blog_admin_index");
        }
    }

    /**
     * @Route("/category/create", name="blog_category_admin_create")
     */
    public function createCategoryAction(Request $request){
        $this->getAdmin($request);
        return $this->categoryEditForm($request);
    }

    /**
     * @Route("/upload_blog_image", name="upload_blog_image")
     */
    public function uploadBlogImageAction(Request $request){
        $error = false;
        $prefix = new \DateTime();
        $prefix = $prefix->getTimestamp();
        if(isset($_FILES["upload"])) {
            if($_FILES["upload"]["type"] === "image/png" || $_FILES["upload"]["type"] === "image/jpeg") {
                if (move_uploaded_file($_FILES["upload"]["tmp_name"], $this->getParameter('upload_dir') . "/"
                    . $prefix . str_replace(' ', '', $_FILES["upload"]["name"]))) {
                    $message = "File uploaded.";
                }
            }
            else {
                $error = true;
                $message = "Wrong image format.";
            }
        }
        $callback = $_REQUEST['CKEditorFuncNum'];
        if(!$error) {
            echo '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("' . $callback . '", "' . $_SERVER["HTTP_ORIGIN"] . "/uploads/" . $prefix . str_replace(' ', '', $_FILES["upload"]["name"])
                . '", "' . $message . '" );</script>';
        }
        else {
            echo '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("' . $callback . '", "' . $_SERVER["HTTP_ORIGIN"] . "/uploads/" . $prefix . str_replace(' ', '', $_FILES["upload"]["name"])
                . '", "' . $message . '" );</script>';
        }
        die;
    }

    /**
     * @Route("/category/edit/{id}", name="blog_category_admin_edit")
     */
    public function editCategoryAction(Request $request, $id){
        $this->getAdmin($request);
        return $this->categoryEditForm($request, $id);
    }

    /**
     * @Route("/category/delete/{id}", name="blog_category_admin_delete")
     */
    public function deleteCategoryAction(Request $request, $id){
        $this->getAdmin($request);
        /** @var EntityManager $em */
        $em = $this->get('doctrine')->getManager();
        $session = $request->getSession();
        /* @var $session Session */

        $category = $em->getRepository("BlogBundle:BlogCategories")->find(['id'=>$id]);
        if(!$category){
            $session->getFlashBag()->add('error', 'Category with id ' . $id . ' not found!');
            return $this->redirectToRoute("blog_admin_index");
        }
        try{
            $message = $message = "News \"{$category->getCategoryName()}\" was successfully removed";

            $em->remove($category);
            $em->flush();

            $session->getFlashBag()->add("success", $message);
            return $this->redirectToRoute("blog_category_admin_list");
        }
        catch (Exception $exception){
            $session->getFlashBag()->add('error', 'Something went wrong: '.$exception->getMessage());
            return $this->redirectToRoute("blog_category_admin_list");
        }
    }

    /**
     * @Route("/category", name="blog_category_admin_list")
     */
    public function categoryListAction(Request $request) {
        $this->getAdmin($request);
        /** @var EntityManager $em */
        $em = $this->get('doctrine')->getManager();
        $categories = $em->getRepository('BlogBundle:BlogCategories')->findAll();

        return $this->render('@Blog/admin/categorylist.html.twig', ['categories' => $categories]);
    }


    private function blogEditForm(Request $request, $id = null) {
        $authorID = $this->getUser()->getId();
        if($id && $id === "upload_blog_image") {
            $this->uploadBlogImageAction($request);
        }
        $this->getAdmin($request);
        /** @var EntityManager $em */
        $em         = $this->get('doctrine')->getManager();
        $session    = $request->getSession();
        $blog       = null;
        $selectedId = null;
        if ($id) {
            $blog = $em->getRepository('BlogBundle:Blog')->find(['id'=>$id]);
            $selectedId = $id;
            if (!$blog) {
                /* @var $session Session */
                $session->getFlashBag()->add('error', 'News with id ' . $id . ' not found!');
                return $this->redirectToRoute("blog_admin_index");
            }
            $authorID = $blog->getAuthorId();
        } else {
            $blog = new Blog();
        }

        $oldImage =$blog->getImage();
        if($selectedId) {
            $form = $this->createForm(BlogEditType::class, $blog, ["entity_manager" => $this->getDoctrine()->getManager(),
                "selected_id" => $selectedId]);
        }
        else {
            $form = $this->createForm(BlogEditType::class, $blog, ["entity_manager" => $this->getDoctrine()->getManager()]);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categ = new \Doctrine\Common\Collections\ArrayCollection();
            foreach($request->request->get("blog_edit")["categories"] as $key=>$cat) {
                $categ->add($em->getRepository("BlogBundle:BlogCategories")->findBy(array("id"=>$cat))[0]);
            }
            $blog->setCategories($categ);
            /** @var $file \Symfony\Component\HttpFoundation\File\UploadedFile */
            $file = $blog->getImage();
            if($file){
                $fileName = $file->getClientOriginalName();
                $prefix = new \DateTime();
                $prefix = $prefix->getTimestamp();
                $blog->setImage($prefix . str_replace(' ', '', $fileName));
                $file->move(
                    $this->getParameter('upload_dir'),
                    $prefix . str_replace(' ', '', $fileName)
                );
            }
            else{
                $blog->setImage($oldImage);
            }
            $blog->setAuthorId($authorID);
            $blog->setUpdateDate(new \DateTime(date('Y-m-d H:i:s')));
            try {
                $message = $message = "Post \"{$blog->getTitle()}\" successfully ";
                $message .= $id ? "edited!" : "created!";
                $em->persist($blog);
                $em->flush();
                /* @var $session Session */
                $session->getFlashBag()->add("success", $message);

                return $this->redirectToRoute("blog_admin_index");
            }
            catch (Exception $exception){
                $session = $request->getSession();
                /* @var $session Session */
                $session->getFlashBag()->add('error', 'Something went wrong: '.$exception->getMessage());
            }
        }
        if($blog->getUpdateDate()) {
            $updateDate = $blog->getUpdateDate()->format("d") .
                "/" . $blog->getUpdateDate()->format("m") .
                "/" . $blog->getUpdateDate()->format("Y");
        }
        else {
            $updateDate = "";
        }
        return $this->render('@Blog/admin/blogedit.html.twig', ['form' => $form->createView(), "updateDate" => $updateDate]);
    }


    private function categoryEditForm(Request $request, $id = null) {
        $this->getAdmin($request);
        $em = $this->get('doctrine')->getManager();
        $session = $request->getSession();
        $categories = null;

        if ($id) {
            $categories = $em->getRepository('BlogBundle:BlogCategories')->find(['id'=>$id]);

            if (!$categories) {
                /* @var $session Session */
                $session->getFlashBag()->add('error', 'Category with ID ' . $id . ' is not found!');
                return $this->redirectToRoute("blog_category_admin_list");
            }
        } else {
            $categories = new BlogCategories();
        }

        $form = $this->createForm(CategoryEditType::class, $categories);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        try {
            $message = $message = "Category \"{$categories->getCategoryName()}\" was ";
            $message .= $id ?  "updated!" : "created!";

            $em->persist($categories);
            $em->flush();

            /* @var $session Session */
            $session->getFlashBag()->add("success", $message);

            return $this->redirectToRoute("blog_category_admin_list");
        }
        catch (Exception $exception){
            $session = $request->getSession();
            /* @var $session Session */
            $session->getFlashBag()->add('error', 'Something went wrong: '.$exception->getMessage());
        }
        }

        return $this->render('@Blog/admin/categoryedit.html.twig', ['form' => $form->createView()]);
    }

    public function getAdmin(Request $request) {
        $session = $request->getSession();
        /* @var $session Session */
        if(in_array("ROLE_ADMIN",$this->getUser()->getRoles()) ||
            in_array("ROLE_SUPER_ADMIN",$this->getUser()->getRoles())) {
            $session->getFlashBag()->add("adminAccess", true);
            //$adminAccess = true;
        }
        else {
            $session->getFlashBag()->add("adminAccess", false);
            //$adminAccess = false;
        }
    }

}