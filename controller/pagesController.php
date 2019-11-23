<?php

require 'controller/sessionTestController.php';
require 'controller/contentController/MyTicketsController.php';


class pagesController
{



    // PAGE - INDEX Graphics PAGE
    public function Index()
    {
        $view = file_get_contents('view/frontend/_layout.html');
        if (isset($_SESSION["user"])) {
            $view = $this->SessionTestForUserMenu($view);
            $view = $this->ReplaceContent($view, "index");
            $view = $this->ReplaceTotals($view);
        } else {
            $view = $this->SessionTestForUserMenu($view);
            $view = $this->ReplaceContent($view, "demo_index");
            $view = $this->ReplaceTotals($view);
        }

        echo $view;
    }

    // PAGE - My Active Tickets Page
    public function MyActiveTickets()
    {
        $view = file_get_contents('view/frontend/_layout.html');

        $view = $this->SessionTestForUserMenu($view);
        $view = $this->ReplaceContent($view, "myactivetickets");
        $view = $this->ReplaceTotals($view);
        $status = "open";
        $myTicketsController = new MyTicketsController;
        $view = $myTicketsController->ReplaceTicketList($view, $status);
        echo $view;
    }



    // PAGE - My Active Tickets Page
    public function MyClosedTickets()
    {
        $view = file_get_contents('view/frontend/_layout.html');

        $view = $this->SessionTestForUserMenu($view);
        $view = $this->ReplaceContent($view, "myclosedtickets");
        $view = $this->ReplaceTotals($view);
        $status = "close";
        $myTicketsController = new MyTicketsController;
        $view = $myTicketsController->ReplaceTicketList($view, $status);
        echo $view;
    }

    // PAGE - Ticket Details
    public function TicketDetails($ticketid)
    {
        $view = file_get_contents('view/frontend/_layout.html');
        $view = $this->SessionTestForUserMenu($view);
        $view = $this->ReplaceContent($view, "ticketdetails");
        $MyTicketsControllert = new MyTicketsController();
        $view = str_replace("{INTERVENTION_LIST}", $MyTicketsControllert->ReplaceInterventionList($ticketid, null), $view);
        $view = $this->ReplaceTotals($view);
        // $myTicketsController = new MyTicketsController;
        // $view = $myTicketsController->ReplaceTicketList($view);
        echo $view;
    }

    // PAGE NEW TICKET
    public function NewTicket()
    {
        $view = file_get_contents('view/frontend/_layout.html');
        $view = $this->SessionTestForUserMenu($view);
        $view = $this->ReplaceContent($view, "newticket");
        $view = $this->ReplaceTotals($view);
        echo $view;
    }


    // PAGE - User Profile Page
    public function UserProfile()
    {
        $view = file_get_contents('view/frontend/_layout.html');
        $view = $this->SessionTestForUserMenu($view);
        $view = $this->ReplaceContent($view, "userprofile");
        $view = $this->ReplaceTotals($view);
        $user = new User(null, null, null, null, null, null, null);
        $PhotoName = $user->getPhotoIfExist($_SESSION['user']->getId());
        if (strlen ($PhotoName[0])!= 0) {
            $PhotoCode = file_get_contents('view/backend/profile_photo_code.html');            
            $view = str_replace("{USER_PHOTO}", $PhotoCode, $view);
            $view = str_replace("{PHOTO_NAME}", $PhotoName[0], $view);
            echo $view;
        } else {
            $PhotoCode = file_get_contents('view/backend/profile_no_photo_code.html');
            $view = str_replace("{USER_PHOTO}", $PhotoCode, $view);
            echo $view;
        }
       
       

        
    }

    // PAGE - User Settings Page
    public function UserSettings()
    {
        $view = file_get_contents('view/frontend/_layout.html');
        $view = $this->SessionTestForUserMenu($view);
        $view = $this->ReplaceContent($view, "usersettings");
        $view = $this->ReplaceTotals($view);
        echo $view;
    }

    // PAGE - User Settings Page
    public function UserActivityLog()
    {
        $view = file_get_contents('view/frontend/_layout.html');
        $view = $this->SessionTestForUserMenu($view);
        $view = $this->ReplaceContent($view, "useractivitylog");
        $view = $this->ReplaceTotals($view);
        echo $view;
    }

    // PAGE - LOGIN
    public function login()
    {
        $view = file_get_contents('view/frontend/_layout.html');
        $view = $this->SessionTestForUserMenu($view);
        $view = $this->ReplaceContent($view, "login");
        $view = $this->ReplaceTotals($view);
        echo $view;
    }

    // PAGE - REGISTER
    public function register()
    {
        $view = file_get_contents('view/frontend/_layout.html');
        $view = $this->SessionTestForUserMenu($view);
        $view = $this->ReplaceContent($view, "register");
        $view = $this->ReplaceTotals($view);
        echo $view;
    }



    //
    //
    // TEST FOR SESSION
    public function SessionTestForUserMenu($view)
    {
        $sessionTestController = new SessionTestController;
        $view = $sessionTestController->replaceMenuIfSessionIsOpen($view);
        return $view;
    }

    // REPLACE CONTENT
    public function ReplaceContent($view, $pagetoopen)
    {
        $view = str_replace("{CONTENT}", file_get_contents('view/frontend/' . $pagetoopen . '.html'), $view);
        return $view;
    }

    // REPLACE INDEX CARDS
    public function ReplaceTotals($view)
    {

        // IF SESSION IS OPEN - REPLACE WITH REAL CONTENT
        if (isset($_SESSION["user"])) {
            $ticket = new Tickets(null, null, null, null, null);
            $status = "open";
            $result = $ticket->getMyTickets($status); // FROM MODEL
            $view = str_replace("{TOTAL_TICKETS_CARD_DEFAULT_CODE}", file_get_contents('view/backend/total_tickets_card_default_code.html'), $view);
            $view = str_replace("{TOTAL_TICKETS_OPEN}", count($result), $view);
        } else {
            $view = str_replace("{TOTAL_TICKETS_CARD_DEFAULT_CODE}", file_get_contents('view/backend/DEMO_total_tickets_card_default_code.html'), $view);
            $view = str_replace("{TOTAL_TICKETS_OPEN}", "14", $view);
        }


        // IF SESSION IS NOT OPEN - REPLACE WITH DEMO CONTENT

        return $view;
    }
}
