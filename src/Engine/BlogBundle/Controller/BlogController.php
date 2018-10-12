<?php

namespace Engine\BlogBundle\Controller;

use Doctrine\ORM\EntityManager;
use Engine\BlogBundle\Entity\Blog;
use Engine\BlogBundle\Entity\BlogCategories;
use Engine\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/blog/")
 */
class BlogController extends Controller
{

    /**
     * @Route("", name="blog_index", defaults={"title":"Blog", "id":null})
     * @param Request $request
     * @param         $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(BlogCategories::class)->findAll();
        $articles = $em->getRepository(Blog::class)->findBy(array("active" => 1),array("date"=>"DESC"));
        $authors = [];
        $tags=[];
        foreach ($articles as $article) {
            $article->setContent(strip_tags($article->getContent(),""));
            $author = $em->getRepository(User::class)->find(["id"=>$article->getAuthorId()]);
            array_push($authors, $author->getUsername());
            foreach ($article->getCategories() as $category) {
                array_push($tags, array($category["id"], $category["name"]));
            }
        }
        $tags = array_unique($tags, SORT_REGULAR);
        $allTags = [];
        foreach ($tags as $key=>$tag) {
            array_push($allTags,$tag[1]);
        }
        $categoriesNames = [];
        foreach ($categories as $key => $category) {
            $categoriesNames[$key]["id"] = $category->getId();
            $categoriesNames[$key]["name"] = $category->getCategoryName();
            if(!in_array($category->getCategoryName(),$allTags)) {
                $categoriesNames[$key]["disabled"] = true;
            }
            else {
                $categoriesNames[$key]["disabled"] = false;
            }
        }
        return $this->render('@Blog/blog.html.twig', array(
            "categories" => $categoriesNames,
            "articles" => $articles,
            "authors" => $authors));
    }

    /**
     * @Route("{id}", name="blog", defaults={"title":"Blog", "id":null})
     * @param Request $request
     * @param         $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function blogAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $article =  $em->getRepository("BlogBundle:Blog")->find(["id"=>$id]);
        if(!$article) {
            throw new NotFoundHttpException();
        }
        $categories = $article->getCategories();

        return $this->render('@Blog/blogarticle.html.twig',["article" => $article, "categories" => $categories]);
    }

}