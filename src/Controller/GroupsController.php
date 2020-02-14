<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\User;
use App\View\View;
use App\Model\GroupModel;
use App\Model\UserModel;
use App\Model\TicketModel;
use App\Model\MemberModel;

class GroupsController
{
    private $view;
    private $groupModel;
    private $memberModel;
    private $ticketModel;
    private $userModel;

    public function __construct()
    {
        $this->view = new View();
        $this->groupModel = new GroupModel();
        $this->memberModel = new MemberModel();
        $this->ticketModel = new TicketModel();
        $this->userModel = new UserModel();
    }

    // DISPLAY PAGE - My Groups
    public function myGroupsPage()
    {
        $result = $this->groupModel->getMyGroups();
        $this->view->render("mygroups", ['results' => $result]);
    }

    // DISPLAY PAGE - Shared Groups
    public function sharedGroupsPage()
    {
        $result = $this->groupModel->getSharedGroups();
        $finalArray = array();
        foreach ($result as $key) {
            $finalArray = array_merge($finalArray, $this->groupModel->getGroupDetails(intval($key['group_id'])));
        }
        $this->view->render("sharedgroups", ['results' => $finalArray]);
    }


    // DISPLAY PAGE - Group Details
    public function groupDetailsPage()
    {
        if (isset($_GET['id'])) {
            $groupResult = $this->groupModel->getGroupDetails(intval($_GET['id']));
            foreach ($groupResult as $group) {
                $ticketResults = $this->ticketModel->getTicketsWithGroupId($group->getId());
            }
            $this->view->render("groupdetails", ['groupresults' => $groupResult, 'ticketresults' => $ticketResults]);
        } else {
            echo "Missing ID";
            exit();
        }
    }

    public function groupMembersPage()
    {
        if (isset($_GET['groupid'])) {
            $groupMembers = $this->memberModel->getGroupMembers(intval($_GET['groupid']));
            $memberDetailsResults = array();
            foreach ($groupMembers as $member) {
                $memberDetailsResults = array_merge($memberDetailsResults, $this->userModel->getUserById(intval($member->user_id)));
            }
            $this->view->render("groupmembers", ['memberresults' => $memberDetailsResults, 'groupid' => intval($_GET['groupid'])]);
        } else {
            echo "Missing Group ID";
            exit();
        }
    }

    // DISPLAY PAGE - Ticket Details
    public function myGroupMembersPage()
    {
        $result = $this->groupModel->getMyGroupMembers();
        $this->view->render("mygroups", ['results' => $result]);
    }

    public function globalGroupsPage()
    {
        $finalArray = array();
        $finalTable[] = array();


        $myGroups = $this->groupModel->getMyGroups();
        foreach ($myGroups as $myGroup) {
            array_push($finalArray, $myGroup->getId());
        }

        $sharedGroups = $this->groupModel->getSharedGroups();
        foreach ($sharedGroups as $sharedGroup) {
            array_push($finalArray, $sharedGroup['group_id']);
        }

        foreach ($finalArray as $id) {
            // var_dump($id);
            // var_dump($this->groupModel->getGroupDetails(intval($id)));
            $finalTable = array_merge($finalTable, $this->groupModel->getGroupDetails(intval($id)));
        }

        // var_dump($finalArray);
        var_dump($finalTable);


        /*
                foreach ($finalArray as $groupId) {
                    $newArray = array();
                    $temp = $this->groupModel->getGroupDetails(intval($groupId));
                    $groupArray = $temp[0];
                    array_push($newArray, array(
                            "id" => $groupArray->getId(),
                            "group_admin" => $groupArray->getGroup_admin(),
                            "creation_date" => $groupArray->getCreation_date(),
                            "group_name" => $groupArray->getGroup_name(),
                            "group_description" => $groupArray->getGroup_description(),
                            "group_status" => $groupArray->getGroup_status())

                    );
                    $globalGroupDetailArray[] = array_merge($newArray);

                }
                var_dump($globalGroupDetailArray);
        */

        $this->view->render("globalgroups", ['results' => $finalTable]);

    }

    public function createGroupPage()
    {
        $this->view->render("creategroup", []);
    }

    public function createGroupFunction()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["Title"]) and isset($_POST["Description"])) {
            $this->groupModel->createNewGroup();
            header('Location: ../index.php?action=mygroups');
            exit();
        }
    }

    public function removeMemberFromGroupFunction()
    {
        if (isset($_GET['groupid']) AND isset($_GET['userid'])) {
            $this->groupModel->removememberfromgroupfunction();
            header('Location: ../index.php?action=groupmembers&groupid=' . $_GET['groupid']);
            exit();
        }
    }

}
