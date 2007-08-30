<?php
$renderer->display('pets/topnav.tpl');

switch($_REQUEST['state'])
{
    default:
    {
        $PET_LIST = array();
        foreach($User->grabPets() as $pet)
        {
            $PET_LIST[] = array(
                'id' => $pet->getUserPetId(),
                'name' => $pet->getPetName(),
                'image' => $pet->getImageUrl(),
                'active' => $pet->getUserPetId() == $User->getActiveUserPetId() ? true : false,
                'fade' => $_SESSION['hilight_pet_id'] == $pet->getUserPetId() ? true : false,
            );
        } // end pet loop

        unset($_SESSION['hilight_pet_id']);
        $renderer->assign('pets',$PET_LIST);
        $renderer->display('pets/overview.tpl');

        break;
    } // end default

    case 'active':
    {
        $ERRORS = array();
        $pet_id = stripinput($_REQUEST['pet_id']);
        
        $pet = new Pet($db);
        $pet = $pet->findOneByUserPetId($pet_id);
        
        if($pet == null)
        {
            $ERRORS[] = 'Pet not found.';
        }
        else
        {
            if($pet->getUserId() != $User->getUserId())
            {
                $ERRORS[] = 'That is not your pet!';
            } // end not your pet
        } // end got pet

        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        }
        else
        {
            $pet->makeActive();
            $_SESSION['hilight_pet_id'] = $pet->getUserPetId();
            redirect('pets');
        }

        break;
    } // end case active
} // end state switch
?>