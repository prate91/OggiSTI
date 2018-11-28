<?php

Class Event{
    
    private $id, $date, $itaTitle, $engTitle, $itaAbstract, $engAbstract, $itaDescription, $engDescription, $textReferences, $image, $imageCaption, $keywords, $editors, $state, $reviser1, $reviser2, $comment;

    function __contruct(){

    }

    function getId(){
        return $this->id;
    }

    function getDate(){
        return $this->date;
    }

    function getItaTitle(){
        return $this->itaTitle;
    }

    function getEngTitle(){
        return $this->engTitle;
    }


    function getItaAbstract(){
        return $this->itaAbstract;
    }

    function getEngAbstract(){
        return $this->engAbstract;
    }

    function getItaDescription(){
        return $this->itaDescription;
    }

    function getEngDescription(){
        return $this->engDescription;
    }
   
}


?>