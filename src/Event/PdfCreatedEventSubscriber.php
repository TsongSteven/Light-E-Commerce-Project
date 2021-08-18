<?php

namespace App\Event;

use App\Entity\Invoice;
use Twig\Environment;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Event\PdfPublishedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PdfCreatedEventSubscriber implements EventSubscriberInterface{
    private $twig;

    public function __construct(Environment $twig, EntityManagerInterface $em, ParameterBagInterface $param)
    {
        $this->twig = $twig;
        $this->em = $em;
        $this->param = $param;
    }

    public static function getSubscribedEvents(){

        return[
            PdfCreatedEvent::NAME => 'onPdfPublishedEvent'
        ];

    }
    public function onPdfPublishedEvent(PdfCreatedEvent $event){
        $order_id = $event->getCheckOutDetails()->getOrderId();
        //dd(substr($order_id, 6));
        // dump($event->getCheckOutDetails()->getProductName());
        // die;
        //dd(substr($order_id, 10)."_ap_invoice");
        $invoice = new Invoice();
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $html = $this->twig->render('main/email.html.twig', [
            'items' => $event->getCheckOutDetails(),
            'product_name' =>$event->getCheckOutDetails()->getProductName()
        ]);    
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();
        // $dompdf->stream("ap_invoice.pdf", [
        //     "Attachment" => true
        // ]);
        $filename = substr($order_id, 10)."_ap_invoice";            
        $publicDirectory = $this->param->get('kernel.project_dir').'/public/uploads/invoicepdf/';
        // // e.g /var/www/project/public/mypdf.pdf
        $pdfFilepath =  $publicDirectory .'/'.$filename.'.pdf'; 
        file_put_contents($pdfFilepath, $output);       
        $invoice->setInvoiceFile($filename);
        $this->em->persist($invoice);
        $this->em->flush();   
    }
}