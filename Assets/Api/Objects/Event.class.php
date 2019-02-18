<?php

require_once __DIR__ . '/../../../../../Config/Database.class.php';


/**
 * Undocumented class
 */
class Event
{
    private $id, $date, $itaTitle, $engTitle, $itaAbstract, $engAbstract, $itaDescription, $engDescription, $textReferences, $image, $icon, $imageCaption, $keywords, $editors, $state, $reviser1, $reviser2, $comment, $saved, $fb;

    /**
     * Correct the format of the date to YYYY-MM-DD to DD/MM/YYYY
     *
     * @param string $oldDate
     * @return void
     */
    private function correctDate($oldDate)
    {
        $date = date('d-m-Y', strtotime($oldDate));
        $dateCorr = str_replace('-', '/', $date);
        return $dateCorr;
    }

    /**
     * Read the event information from api
     *
     * @return void
     */
    public function read()
    {
        require_once __DIR__ . '/../Select/extractEvent.php';
        $data = ob_get_contents();
        ob_end_clean();
        $event = json_decode($data);
        $this->id = $event[0]->Id;
        $this->date = $this->correctDate($event[0]->Date);
        $this->itaTitle = $event[0]->ItaTitle;
        $this->engTitle = $event[0]->EngTitle;
        $this->itaAbstract = $event[0]->ItaAbstract;
        $this->engAbstract = $event[0]->EngAbstract;
        $this->image = $event[0]->Image;
        $this->icon = $event[0]->Icon;
        $this->imageCaption = $event[0]->ImageCaption;
        $this->itaDescription = $event[0]->ItaDescription;
        $this->engDescription = $event[0]->EngDescription;
        $this->textReferences = $event[0]->TextReferences;
        $this->keywords = $event[0]->Keywords;
        $this->editors = $event[0]->Editors;
        $this->reviser1 = $event[0]->Reviser_1;
        $this->reviser2 = $event[0]->Reviser_2;
        $this->state = $event[0]->State;
        if (isset($event[0]->Saved)) {
            $this->saved = $event[0]->Saved;
        }
        $this->views = $event[0]->Views;
        $this->comment = $event[0]->Comment;
        if (isset($event[0]->Fb)) {
            $this->fb = intval($event[0]->Fb);
        }
    }

    function readPublished($result)
    {
        if (true == $result['success']) {
            foreach ($result['rows'] as $row) {
                $this->id = $row['Id'];
                $this->date = $this->correctDate($row["Date"]);
                $this->itaTitle = $row["ItaTitle"];
                $this->engTitle = $row["EngTitle"];
                $this->itaAbstract = $row["ItaAbstract"];
                $this->engAbstract = $row["EngAbstract"];
                $this->image = $row["Image"];
                $this->icon = $row["Icon"];
                $this->imageCaption = $row["ImageCaption"];
                $this->itaDescription = $row["ItaDescription"];
                $this->engDescription = $row["EngDescription"];
                $this->textReferences = $row["TextReferences"];
                $this->keywords = $row["Keywords"];
                $this->editors = $row["Editors"];
                $this->reviser1 = $row["Reviser_1"];
                $this->reviser2 = $row["Reviser_2"];
                $this->state = $row["State"];
                $this->views = $row["Views"];
                $this->comment = $row["Comment"];
                $this->fb = intval($row["Fb"]);
            }
        }
    }

    function readEditing($result)
    {
        if (true == $result['success']) {
            foreach ($result['rows'] as $row) {
                $this->id = $row['Id'];
                $this->date = $this->correctDate($row["Date"]);
                $this->itaTitle = $row["ItaTitle"];
                $this->engTitle = $row["EngTitle"];
                $this->itaAbstract = $row["ItaAbstract"];
                $this->engAbstract = $row["EngAbstract"];
                $this->image = $row["Image"];
                $this->icon = $row["Icon"];
                $this->imageCaption = $row["ImageCaption"];
                $this->itaDescription = $row["ItaDescription"];
                $this->engDescription = $row["EngDescription"];
                $this->textReferences = $row["TextReferences"];
                $this->keywords = $row["Keywords"];
                $this->editors = $row["Editors"];
                $this->reviser1 = $row["Reviser_1"];
                $this->reviser2 = $row["Reviser_2"];
                $this->state = $row["State"];
                $this->saved = $row["Saved"];
                $this->views = $row["Views"];
                $this->comment = $row["Comment"];
            }
        }
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getItaTitle()
    {
        return $this->itaTitle;
    }

    public function getEngTitle()
    {
        return $this->engTitle;
    }

    public function getItaAbstract()
    {
        return $this->itaAbstract;
    }

    public function getEngAbstract()
    {
        return $this->engAbstract;
    }

    public function getItaDescription()
    {
        return $this->itaDescription;
    }

    public function getEngDescription()
    {
        return $this->engDescription;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function getImageCaption()
    {
        return $this->imageCaption;
    }

    public function getTextReferences()
    {
        return $this->textReferences;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function getEditors()
    {
        return $this->editors;
    }

    public function getReviser1()
    {
        return $this->reviser1;
    }

    public function getReviser2()
    {
        return $this->reviser2;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getSaved()
    {
        return $this->saved;
    }

    public function getViews()
    {
        return $this->views;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getFb()
    {
        return $this->fb;
    }
}


?>