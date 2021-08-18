<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ItemRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Cocur\Slugify\Slugify;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap.xml", name="sitemap")
     */
    public function sitemap(Request $request, ItemRepository $item_repo, SluggerInterface $slug): Response
    {
        $hostname = $request->getSchemeAndHttpHost();
        $slugify = new Slugify();
        // $items = $item_repo->findAll();
        $urls=[];
        foreach($item_repo->findAll() as $title ){
            $urls[] =$this->generateUrl('main',['title'=>$slugify->slugify($title->getTitle())]);
        }

        $response = new Response(
            $this->renderView('sitemap/index.html.twig',[
                'urls' => $urls,
                'hostname' => $hostname
            ]),200
        );
        $response->headers->set('Content-type','text/xml');
        return $response;
    }
}
