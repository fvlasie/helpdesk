<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

@session_start() ;
include "./modules/" . $_SESSION[$guid]["module"] . "/moduleFunctions.php" ;


if (isActionAccessible($guid, $connection2, "/modules/Help Desk/issues_createTechnician.php")==FALSE) {
  //Acess denied
  print "<div class='error'>" ;
  print _("You do not have access to this action.") ;
  print "</div>" ;
}
else {
  //Proceed!
  print "<div class='trail'>" ;
  print "<div class='trailHead'><a href='" . $_SESSION[$guid]["absoluteURL"] . "'>" . _("Home") . "</a> > <a href='" . $_SESSION[$guid]["absoluteURL"] . "/index.php?q=/modules/" . getModuleName($_GET["q"]) . "/" . getModuleEntry($_GET["q"], $connection2, $guid) . "'>" . _(getModuleName($_GET["q"])) . "</a> > </div><div class='trailEnd'>" . _('Assign Issue') . "</div>" ;
  print "</div>" ;

  try {
    $data=array();
    $sql="SELECT gibbonPerson.* FROM gibbonPerson LEFT JOIN helpDeskTechnicians ON (gibbonPerson.gibbonPersonID!=helpDeskTechnicians.gibbonPersonID) WHERE status='Full' AND NOT gibbonPerson.gibbonPersonID=helpDeskTechnicians.gibbonPersonID";
    $result=$connection2->prepare($sql);
    $result->execute($data);
  } catch(PDOException $e) {
    print $e;
  }

  ?>

  <form method="post" action="<?php print $_SESSION[$guid]["absoluteURL"] . "	/modules/" . $_SESSION[$guid]["module"] . "/issues_createTechnicianProcess.php" ?>">
    <table class='smallIntBorder' cellspacing='0' style="width: 100%">
      <tr>
        <td>
          <?php print "<b>". _('Person') ." *</b><br/>";?>
          <span style=\"font-size: 90%\"><i></i></span>
        </td>
        <td class="right">
          <select name='person' id='person' style='width:302px'>
            <?php
            print "<option value=''>Please select...</option>" ;
            foreach($result as $option) {
              print "<option value='" . $option['gibbonPersonID'] . "'>". formatName($option['title'],$option['preferredName'],$option['surname'], "Student", FALSE, FALSE) ."</option>" ;
            }
            ?>
          </select>
          <script type="text/javascript">
            var name=new LiveValidation('person');
            name.add(Validate.Presence);
          </script>
        </td>
      </tr>
      <tr>
        <td>
          <span style="font-size: 90%"><i>* <?php print _("denotes a required field") ; ?></i></span>
        </td>
        <td class="right">
          <input type="hidden" name="address" value="<?php print $_SESSION[$guid]["address"] ?>">
          <input type="submit" value="<?php print _("Submit") ; ?>">
        </td>
      </tr>
    </table>
  </form>
  <?php
  }
?>