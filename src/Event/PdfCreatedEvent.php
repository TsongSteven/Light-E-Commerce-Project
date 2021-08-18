<?php
namespace App\Event;
use App\Entity\CheckOutDetails;
use Symfony\Contracts\EventDispatcher\Event;

class PdfCreatedEvent extends Event{
    public const NAME = 'checkOutDetails.send';
    private  $checkOutDetails;
    public function __construct(CheckOutDetails $checkOutDetails){
        $this->checkOutDetails = $checkOutDetails;
    }

    public function getCheckOutDetails(){
        return $this->checkOutDetails;
    }
}