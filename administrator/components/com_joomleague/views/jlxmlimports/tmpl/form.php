<?php defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
//$document =& JFactory::getDocument();
//$document->addScript( JURI::base() . 'components/com_joomleague/assets/js/JL_import.js' );

if (isset($this->xml) && is_array($this->xml))
{
	{
		//echo 'this<pre>'.print_r($this,true).'</pre>';
		if (array_key_exists('exportversion',$this->xml))
		{
			$exportversion =& $this->xml['exportversion'];
		}
		if (array_key_exists('project',$this->xml))
		{
			$proj =& $this->xml['project'];
		}
		if (array_key_exists('team',$this->xml))
		{
			$teams =& $this->xml['team'];
		}
		if (array_key_exists('club',$this->xml))
		{
			$clubs =& $this->xml['club'];
		}
		if (array_key_exists('playground',$this->xml))
		{
			$playgrounds =& $this->xml['playground'];
		}
		if (array_key_exists('league',$this->xml))
		{
			$league =& $this->xml['league'];
		}
		if (array_key_exists('season',$this->xml))
		{
			$season =& $this->xml['season'];
		}
		if (array_key_exists('sportstype',$this->xml))
		{
			$sportstype =& $this->xml['sportstype'];
		}
		if (array_key_exists('person',$this->xml))
		{
			$persons =& $this->xml['person'];
		}
		if (array_key_exists('event',$this->xml))
		{
			$events =& $this->xml['event'];
		}
		if (array_key_exists('position',$this->xml))
		{
			$positions =& $this->xml['position'];
		}
		if (array_key_exists('parentposition',$this->xml))
		{
			$parentpositions =& $this->xml['parentposition'];
		}
		if (array_key_exists('statistic',$this->xml))
		{
			$statistics =& $this->xml['statistic'];
		}
	}

	$xmlProjectImport=true;
	$xmlImportType='';
	if (!isset($proj))
	{
		$xmlProjectImport=false;
		if (isset($teams))
		{
			$xmlImportType='teams';	// There shouldn't be any problems with import of teams-xml-export files
			$xmlImportTitle='Standard XML-Import of JoomLeague Teams';
			$teamsClubs=$teams;
		}
		elseif (isset($clubs))
		{
			$xmlImportType='clubs';	// There shouldn't be any problems with import of clubs-xml-export files
			$xmlImportTitle='Standard XML-Import of JoomLeague Clubs';
			$teamsClubs=$clubs;
		}
		elseif (isset($events)) // There shouldn't be any problems with import of events-xml-export files
		{
			$xmlImportType='events';
			$xmlImportTitle='Standard XML-Import of JoomLeague Events';
		}
		elseif (isset($positions))	// There shouldn't be any problems with import of positions-xml-export files
		{							// maybe the positions export routine should also export position_eventtype and events
			$xmlImportType='positions';
			$xmlImportTitle='Standard XML-Import of JoomLeague Positions';
		}
		elseif (isset($parentpositions))	// There shouldn't be any problems with import of positions-xml-export files
		{									// maybe the positions export routine should also export position_eventtype and events
			$xmlImportType='positions';
			$xmlImportTitle='Standard XML-Import of JoomLeague Positions';
		}
		elseif (isset($persons))	// There shouldn't be any problems with import of persons-xml-export files
		{
			$xmlImportType='persons';
			$xmlImportTitle='Standard XML-Import of JoomLeague Persons';
		}
		elseif (isset($playgrounds))	// There shouldn't be any problems with import of statistics-xml-export files
		{
			$xmlImportType='playgrounds';
			$xmlImportTitle='Standard XML-Import of JoomLeague Playgrounds';
		}
		elseif (isset($statistics)) // There shouldn't be any problems with import of statistics-xml-export files
		{							// maybe the statistic export routine should also export position_statistic and positions
			$xmlImportType='statistics';
			$xmlImportTitle='Standard XML-Import of JoomLeague Statistics';
		}
		JError::raiseNotice(500,JText::_($xmlImportTitle));
	}
	else
	{
		$teamsClubs=$teams;
	}
	if (!empty($teamsClubs)){$teamsClubsCount=count($teamsClubs);}
?>
<script language="javascript" type="text/javascript"><!--


	function chkFormular()
	{
		return true;
		var message='';

		<?php
		if (($xmlProjectImport) || ($xmlImportType=='events') || ($xmlImportType=='positions'))
		{
			?>
			if (((document.adminForm.sportstype.selectedIndex=='0') && (document.adminForm.sportstypeNew.disabled) &&
				(!document.adminForm.newSportsTypeCheck.checked)) ||
				((document.adminForm.sportstypeNew.disabled==false) && (trim(document.adminForm.sportstypeNew.value)=='')))
			{
				message+="<?php echo JText::_('Sports type is missing!'); ?>\n";
			}
			<?php
			if ($xmlProjectImport)
			{
				?>
				if (trim(document.adminForm.name.value)=='')
				{
					message+="<?php echo JText::_('Please select name of this project!'); ?>\n";
				}
				if (((document.adminForm.league.selectedIndex=='0') && (document.adminForm.leagueNew.disabled)) ||
					((document.adminForm.leagueNew.disabled==false) && (trim(document.adminForm.leagueNew.value)=='')))
				{
					message+="<?php echo JText::_('League is missing!'); ?>\n";
				}
				if (((document.adminForm.season.selectedIndex=='0') && (document.adminForm.seasonNew.disabled)) ||
					((document.adminForm.seasonNew.disabled==false) && (trim(document.adminForm.seasonNew.value)=='')))
				{
					message+="<?php echo JText::_('Season is missing!'); ?>\n";
				}
				<?php
			}
		}
		?>
		<?php
		if (isset($teams) && count($teams) > 0)
		{
			// Perform some checks on teams?
		}
		?>
		<?php
		if (isset($clubs) && count($clubs) > 0)
		{
			// Perform some checks on clubs?
		}
		?>
		<?php
		if ((isset($playgrounds)) && (count($playgrounds) > 0))
		{
			for ($counter=0; $counter < count($playgrounds); $counter++)
			{
				?>
				if (((document.adminForm.choosePlayground_<?php echo $counter; ?>.checked==false) &&
					(document.adminForm.createPlayground_<?php echo $counter; ?>.checked==false)) ||
					((trim(document.adminForm.playgroundName_<?php echo $counter; ?>.value)=='') ||
					(trim(document.adminForm.playgroundShortname_<?php echo $counter; ?>.value)=='')))
				{
					message+='<?php echo JText::sprintf('No data selected for playground [%1$s]',addslashes($playgrounds[$counter]->name)); ?>\n';
				}
				<?php
			}
		}
		?>
		<?php
		if ((isset($events)) && (count($events) > 0))
		{
			for ($counter=0; $counter < count($events); $counter++)
			{
				?>
				if (((document.adminForm.chooseEvent_<?php echo $counter; ?>.checked==false) &&
					(document.adminForm.createEvent_<?php echo $counter; ?>.checked==false)) ||
					(trim(document.adminForm.eventName_<?php echo $counter; ?>.value)==''))
				{
					message+='<?php echo JText::sprintf('No data selected for event [%1$s]',addslashes($events[$counter]->name)); ?>\n';
				}
				<?php
			}
		}
		?>
		<?php
		if ((isset($parentpositions)) && (count($parentpositions) > 0))
		{
			for ($counter=0; $counter < count($parentpositions); $counter++)
			{
				?>
				if (((document.adminForm.chooseParentPosition_<?php echo $counter; ?>.checked==false) &&
					(document.adminForm.createParentPosition_<?php echo $counter; ?>.checked==false)) ||
					(trim(document.adminForm.parentPositionName_<?php echo $counter; ?>.value)==''))
				{
					message+='<?php echo JText::sprintf('No data selected for parentposition [%1$s]',addslashes($parentpositions[$counter]->name)); ?>\n';
				}
				<?php
			}
		}
		?>
		<?php
		if ((isset($positions)) && (count($positions) > 0))
		{
			for ($counter=0; $counter < count($positions); $counter++)
			{
				?>
				if (((document.adminForm.choosePosition_<?php echo $counter; ?>.checked==false) &&
					(document.adminForm.createPosition_<?php echo $counter; ?>.checked==false)) ||
					(trim(document.adminForm.positionName_<?php echo $counter; ?>.value)==''))
				{
					message+='<?php echo JText::sprintf('No data selected for position [%1$s]',addslashes($positions[$counter]->name)); ?>\n';
				}
				<?php
			}
		}
		?>
		<?php
		if ((isset($statistics)) && (count($statistics) > 0))
		{
			for ($counter=0; $counter < count($statistics); $counter++)
			{
				?>
				if (((document.adminForm.chooseStatistic_<?php echo $counter; ?>.checked==false) &&
					(document.adminForm.createStatistic_<?php echo $counter; ?>.checked==false)) ||
					(trim(document.adminForm.statisticName_<?php echo $counter; ?>.value)==''))
				{
					message+='<?php echo JText::sprintf('No data selected for statistic [%1$s]',addslashes($statistics[$counter]->name)); ?>\n';
				}
				<?php
			}
		}
		?>
		<?php
		if ((isset($persons)) && (count($persons) > 0))
		{
			for ($counter=0; $counter < count($persons); $counter++)
			{
				?>
				if(document.adminForm.choosePerson_<?php echo $counter; ?>.checked==false) {
					if ((document.adminForm.createPerson_<?php echo $counter; ?>.checked==false)||
						((trim(document.adminForm.personLastname_<?php echo $counter; ?>.value)=='') ||
						(trim(document.adminForm.personFirstname_<?php echo $counter; ?>.value)=='')))
					{
						message+='<?php echo JText::sprintf('No data selected for person [%1$s,%2$s]',addslashes($persons[$counter]->lastname),addslashes($persons[$counter]->firstname)); ?>\n';
					}
				}
				<?php
			}
		}
		?>
		if (message=='')
		{
			return true;
		}
		else
		{
		  alert("<?php echo JText::_('JL_ADMIN_XML_IMPORT_ERROR'); ?>\n\n"+message);
		  return false;
		}
	}

//--></script>
<?php
	
  $useExistingEntryColor = $this->import_existing_color;
	$useNewEntryColor = $this->import_new_entry_color;
	$useExistingClubEntryColor = $this->import_club_existing_color;
	
?>
	<div id='editcell'>
		<a name='page_top'></a>
		<table class='adminlist'>
			<thead><tr><th><?php echo JText::_('JL_ADMIN_XML_IMPORT_TABLE_TITLE_2'); ?></th></tr></thead>
			<tbody>
				<tr>
					<td style='text-align:center; '>
						<p style='text-align:center;'><b style='color:green; '><?php echo JText::sprintf('JL_ADMIN_XML_IMPORT_UPLOAD_SUCCESS','<i>'.$this->uploadArray['name'].'</i>'); ?></b></p>
						<?php
						if ($this->import_version!='OLD')
						{
							if (isset($exportversion->exportRoutine) &&
								strtotime($exportversion->exportRoutine) >= strtotime('2010-09-19 23:00:00'))
							{
								?>
								<p><?php
									echo JText::sprintf('This file was created using JoomLeague-Export-Routine dated: %1$s',$exportversion->exportRoutine).'<br />';
									echo JText::sprintf('Date and time of this file is: %1$s - %2$s',$exportversion->exportDate,$exportversion->exportTime).'<br />';
									echo JText::sprintf('The name of the Joomla-System where this file was created is: %1$s',$exportversion->exportSystem).'<br />';
									?></p><?php
							}
							else
							{
								?>
								<p><?php
									echo JText::_('This file was created by an older revision of JoomLeague 1.5.0a!').'<br />';
									echo JText::_('As we can not guarantee a correct processing the import routine will STOP here!!!');
									?></p></td></tr></tbody></table></div><?php
									return;
							}
						}
						?>
						<p><?php echo JText::_('JL_ADMIN_XML_IMPORT_CREATE_CLUBS_HINT'); ?></p>
					</td>
				</tr>
			</tbody>
		</table>
		<form name='adminForm' action='<?php echo $this->request_url; ?>' method='post' onsubmit='return chkFormular();' >
			<input type='hidden' name='importProject' value="<?php echo $xmlProjectImport; ?>" />
			<input type='hidden' name='importType' value="<?php echo $xmlImportType; ?>" />
			<input type='hidden' name='sent' value="2" id='sent' />
			<input type='hidden' name='controller' value="jlxmlimport" />
			<input type='hidden' name='task' value="insert" />
			<?php echo JHTML::_('form.token')."\n"; ?>
			<?php
			if (($xmlProjectImport) || ($xmlImportType=='events') || ($xmlImportType=='positions'))
			{
				?>
				<fieldset>
					<legend><strong><?php echo JText::_('JL_ADMIN_XML_IMPORT_GENERAL_DATA_LEGEND'); ?></strong></legend>
					<table class='adminlist'>
						<?php
						if (($xmlImportType!='events') && ($xmlImportType!='positions'))
						{
							?>
							<tr>
								<td style='background-color:#EEEEEE'><?php echo JText::_('JL_ADMIN_XML_IMPORT_PROJECT_NAME'); ?></td>
								<td style='background-color:#EEEEEE'>
									<input type='text' name='name' id='name' size='110' maxlength='100' value="<?php echo stripslashes(htmlspecialchars($proj->name)); ?>" />
								</td>
							</tr>
							<?php
						}
						?>
						<tr>
							<td style='background-color:#DDDDDD'><?php echo JText::_('JL_ADMIN_XML_IMPORT_SPORTSTYPE'); ?></td>
							<td style='background-color:#DDDDDD'>
								<?php
								$foundMatchingSportstype = false;
								$dSportsTypeName = "";
								if (isset($sportstype->name))
								{
									$dSportsTypeName=$sportstype->name;
								}
								if (count($this->sportstypes) > 0)
								{
									$options = "";
									foreach ($this->sportstypes AS $row)
									{
										$options .= '<option ';
										if (($row->name==$dSportsTypeName) ||
											($row->name==JText::_($dSportsTypeName)))
										{
											$foundMatchingSportstype = true;
											$options .= "selected='selected' ";
										}
										elseif (count($this->sportstypes)==1)
										{
											$options .= "selected='selected' ";
										}
										$options .= "value='$row->id;'>";
										$options .= JText::_($row->name);
										$options .= '</option>';
									}
									?>
									<select name='sportstype' id='sportstype' <?php if (!$foundMatchingSportstype){echo " disabled='disabled'";}?>>
										<option selected value="0"><?php echo JText::_('JL_ADMIN_XML_IMPORT_SPORTSTYPE_SELECT'); ?></option>
										<?php echo $options;?>
									</select>
									<br />
									<input	type='checkbox' name='newSportsTypeCheck' value="1"
											<?php if (!$foundMatchingSportstype){echo " checked='checked'";}?>
											onclick="
											if (this.checked) {
												document.adminForm.sportstype.disabled=true;
												document.adminForm.sportstypeNew.disabled=false;
											} else {
												document.adminForm.sportstype.disabled=false;
												document.adminForm.sportstypeNew.disabled=true;
											}" />
									<?php echo JText::_('JL_ADMIN_XML_IMPORT_CREATE_NEW'); ?>
									<input type='text' name='sportstypeNew' size='30' maxlength='25' id='sportstypeNew' 
											value="<?php echo stripslashes(htmlspecialchars(JText::_($dSportsTypeName))); ?>"
											<?php if ($foundMatchingSportstype){echo " disabled='disabled'";}?>/>
								<?php
								}
								else
								{
									?>
									<input type="hidden" name="newSportsTypeCheck" value="1" />
									<?php echo JText::_('JL_ADMIN_XML_IMPORT_CREATE_NEW'); ?>
									<input type='text' name='sportstypeNew' size='30' maxlength='25' id='sportstypeNew' 
											value="<?php echo stripslashes(htmlspecialchars(JText::_($dSportsTypeName))); ?>" />
									<?php
								}
								?>
							</td>
						</tr>
						<?php
						if (($xmlImportType!='events') && ($xmlImportType!='positions'))
						{
							?>
							<tr>
								<td style='background-color:#EEEEEE'><?php echo JText::_('JL_ADMIN_XML_IMPORT_LEAGUE'); ?></td>
								<td style='background-color:#EEEEEE'>
								<?php
								$foundMatchingLeague = false;
								$dLeagueName="";
								$leagueCountry="";
								if (isset($league->name))
								{
									$dLeagueName=$league->name;
									$leagueCountry=$league->country;
								}
								$dCountry=$leagueCountry;
								if (preg_match('=^[0-9]+$=',$dCountry))
								{
									$dCountry=$this->OldCountries[(int)$dCountry];
								}
								if (count($this->leagues) > 0)
								{
									$options = "";
									foreach ($this->leagues AS $row)
									{
										$options .= '<option ';
										if ($row->name==$dLeagueName)
										{
											$foundMatchingLeague = true;
											$options .= "selected='selected' ";
										}
										elseif (count($this->leagues)==1)
										{
											$options .= "selected='selected' ";
										}
										$options .= "value='$row->id;'>";
										$options .= $row->name;
										$options .= '</option>';
									}
									?>
									<select name='league' id='league' <?php if (!$foundMatchingLeague){echo " disabled='disabled'";}?>>
										<option selected value="0"><?php echo JText::_('JL_ADMIN_XML_IMPORT_LEAGUE_SELECT'); ?></option>
										<?php echo $options;?>
									</select>
									<br />
									<input	type='checkbox' name='newLeagueCheck' value="1"
											<?php if (!$foundMatchingLeague){echo " checked='checked'";}?>
											onclick="
											if (this.checked) {
												document.adminForm.league.disabled=true;
												document.adminForm.leagueNew.disabled=false;
											} else {
												document.adminForm.league.disabled=false;
												document.adminForm.leagueNew.disabled=true;
											}" />
									<?php echo JText::_('JL_ADMIN_XML_IMPORT_CREATE_NEW'); ?>
									<input type='text' name='leagueNew' size='90' maxlength='75' id='leagueNew' 
											value="<?php echo stripslashes(htmlspecialchars($dLeagueName)); ?>"
											<?php if ($foundMatchingLeague){echo " disabled='disabled'";}?>/>
									<?php
								}
								else
								{
									?>
									<input type="hidden" name="newLeagueCheck" value="1" />
									<?php echo JText::_('JL_ADMIN_XML_IMPORT_CREATE_NEW'); ?>
									<input type='text' name='leagueNew' size='90' maxlength='75' id='leagueNew' 
											value="<?php echo stripslashes(htmlspecialchars($dLeagueName)); ?>" />
									<?php
								}
								?>
								</td>
							</tr>
							<tr>
								<td style='background-color:#DDDDDD'><?php echo JText::_('JL_ADMIN_XML_IMPORT_SEASON'); ?></td>
								<td style='background-color:#DDDDDD'>
								<?php
								$foundMatchingSeason = false;
								$dSeasonName = "";
								if (isset($season->name))
								{
									$dSeasonName=$season->name;
								}
								if (count($this->seasons) > 0)
								{
									$options = "";
									foreach ($this->seasons AS $row)
									{
										$options .= '<option ';
										if ($row->name==$dSeasonName)
										{
											$foundMatchingSeason = true;
											$options .= "selected='selected' ";
										}
										elseif (count($this->seasons)==1)
										{
											$options .= "selected='selected' ";
										}
										$options .= "value='$row->id;'>";
										$options .= $row->name;
										$options .= '</option>';
									}
									?>
									<select name='season' id='season' <?php if (!$foundMatchingSeason){echo " disabled='disabled'";}?>>
										<option selected value="0"><?php echo JText::_('JL_ADMIN_XML_IMPORT_SEASON_SELECT'); ?></option>
										<?php echo $options;?>
									</select>
									<br />
									<input	type='checkbox' name='newSeasonCheck' value="1"
											<?php if (!$foundMatchingSeason){echo " checked='checked'";}?>
											onclick="
											if (this.checked) {
												document.adminForm.season.disabled=true;
												document.adminForm.seasonNew.disabled=false;
											} else {
												document.adminForm.season.disabled=false;
												document.adminForm.seasonNew.disabled=true;
											}" />
									<?php echo JText::_('JL_ADMIN_XML_IMPORT_CREATE_NEW'); ?>
									<input type='text' name='seasonNew' size='90' maxlength='75' id='seasonNew' 
											value="<?php echo stripslashes(htmlspecialchars($dSeasonName)); ?>"
											<?php if ($foundMatchingSeason){echo " disabled='disabled'";}?>/>
								<?php
								}
								else
								{
									?>
									<input type="hidden" name="newSeasonCheck" value="1" />
									<?php echo JText::_('JL_ADMIN_XML_IMPORT_CREATE_NEW'); ?>
									<input type='text' name='seasonNew' size='90' maxlength='75' id='seasonNew' 
											value="<?php echo stripslashes(htmlspecialchars($dSeasonName)); ?>" />
									<?php
								}
								?>
								</td>
							</tr>
							<tr>
								<td style='background-color:#EEEEEE'><?php echo JText::_('JL_ADMIN_XML_IMPORT_ADMIN'); ?></td>
								<td style='background-color:#EEEEEE'>
									<select name='admin' id='admin'>
										<?php
										foreach ($this->admins AS $row)
										{
											echo '<option ';
												if ($row->id==62){echo "selected='selected' ";}
												echo "value='$row->id;'>";
												echo $row->username;
											echo '</option>';
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td style='background-color:#DDDDDD'><?php echo JText::_('JL_ADMIN_XML_IMPORT_EDITOR'); ?></td>
								<td style='background-color:#DDDDDD'>
									<select name='editor' id='editor'>
										<?php
										foreach ($this->editors AS $row)
										{
											echo '<option ';
												if ($row->id==62){echo "selected='selected' ";}
												echo "value='$row->id;'>";
												echo $row->username;
											echo '</option>';
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td style='background-color:#EEEEEE'><?php echo JText::_('JL_ADMIN_XML_IMPORT_TEMPLATES'); ?></td>
								<td style='background-color:#EEEEEE'>
									<select name='copyTemplate' id='copyTemplate'>
										<option value="0" selected><?php echo JText::_('JL_ADMIN_XML_IMPORT_TEMPLATES_USEOWN'); ?></option>
										<?php
										foreach ($this->templates AS $row)
										{
											echo "<option value=\"$row->id\">$row->name</option>\n";
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td style='background-color:#DDDDDD'><?php echo JText::_('TimeOffset of this project'); ?></td>
								<td style='background-color:#DDDDDD'>
									<?php echo $this->lists['serveroffset'].'&nbsp;'; ?>
								</td>
							</tr>
							<tr>
								<td style='background-color:#EEEEEE'><?php echo JText::_('JL_ADMIN_XML_IMPORT_PUBLISH'); ?></td>
								<td style='background-color:#EEEEEE'>
									<input type='radio' name='publish' value="0" /><?php echo JText::_('JL_GLOBAL_NO'); ?>
									<input type='radio' name='publish' value="1" checked='checked' /><?php echo JText::_('JL_GLOBAL_YES'); ?>
								</td>
							</tr>
							<?php
						}
						?>
					</table>
				</fieldset>
				<p style='text-align:right;'><a href='#page_bottom'><?php echo JText::_('JL_ADMIN_XML_IMPORT_BOTTOM'); ?></a></p>
				<?php
			}
			?>

			<?php
			//===============================================================================================================
			//
			//                                             CLUB AND TEAM IMPORT
			//
			//===============================================================================================================
			if ((isset($clubs) && count($clubs) > 0) || (isset($teams) && count($teams) > 0))
			{
				?>
				<fieldset>
					<legend><strong><?php
						if (!empty($clubs) && !empty($teams))
						{
							echo JText::_('JL_ADMIN_XML_IMPORT_CLUBS_TEAMS_LEGEND');
						}
						elseif (!empty($clubs))
						{
							echo JText::_('JL_ADMIN_XML_IMPORT_CLUBS_LEGEND');
						}
						else
						{
							echo JText::_('JL_ADMIN_XML_IMPORT_TEAMS_LEGEND');
						}
						?></strong></legend>
					<table class='adminlist'>
						<thead>
							<tr>
								<th width='5%' nowrap='nowrap'><?php
									$checkCount=((isset($clubs) && count($clubs) > 0)) ? count($clubs) : count($teams);
									echo JText::_('JL_ADMIN_XML_IMPORT_ALL_NEW').'<br />';
									echo '<input type="checkbox" name="toggleTeamsClubs" value="" onclick="checkAllNewClubTeam('.$checkCount.')" />';
								?></th>
								<th width="20%"><?php echo JText::_('Club and Team import option'); ?></th>
								<th width="45%"><?php echo JText::_('Club and Team information'); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php
							// Some explanation on the used variables
							// $teamsClubs  : array of xml objects present in the xml import file (containing all fields of the club table)
							//                In case of a project import it is the list of teams, otherwise if clubs are defined in the
							//                xml import file then it contains the defined clubs, and if no clubs are defined but teams are
							//                then it will contain the team definition of the import file.
							// $clubs       : array of xml objects present in the xml import file (containing all fields of the club table)
							// $teams       : array of xml objects present in the xml import file (containing all fields of the team table)
							// $this->clubs : array of objects with id, name, standard playground and country that are present in the database
							// $this->teams : array of objects with id, name, club_id, short name, middle name and info that are present in the database

// $this->dump_variable("clubs", $clubs);
// $this->dump_variable("teams", $teams);
// $this->dump_variable("teamsClubs", $teamsClubs);
// $this->dump_variable("this->clubs", $this->clubs);
// $this->dump_variable("this->teams", $this->teams);

						$i=0;
						$color1="#DDDDDD";
						$color2="#EEEEEE";
						//foreach ($teams AS $key=> $team)
						foreach ($teamsClubs AS $key=> $teamClub)
						{
							$color = ($key % 2 == 1) ? $color1 : $color2;

							// Team info from the import file
							$importTeam_ID = (int)$teamClub->id;
							$importTeam_Name = (string)$teamClub->name;
							$importTeam_ShortName = (string)$teamClub->short_name;
							$importTeam_MiddleName = (string)$teamClub->middle_name;
							$importTeam_Info = (string)(($this->import_version=='OLD') ? $teamClub->description : $teamClub->info);
							$importTeam_ClubID = (int)$teamClub->club_id;
//$this->dump_variable("importTeam_Name", $importTeam_Name);
//$this->dump_variable("importTeam_ClubID", $importTeam_ClubID);

							// Find club name via the club ID specified for the team in the import file
							$importTeam_ClubName = "";
							$importTeam_ClubCountry = "";
							if (count($clubs) > 0)
							{
								foreach ($clubs AS $club)
								{
									if ((int)$club->id == $importTeam_ClubID)
									{
										$importTeam_ClubName = (string)$club->name;
										$importTeam_ClubCountry = (string)$club->country;
										if (preg_match('=^[0-9]+$=',$importTeam_ClubCountry))
										{
											$importTeam_ClubCountry = $this->OldCountries[(int)$importTeam_ClubCountry];
										}
										break;
									}
								}
							}
//$this->dump_variable("importTeam_ClubName", $importTeam_ClubName);
//$this->dump_variable("importTeam_ClubCountry", $importTeam_ClubCountry);

							// Find in database a team with the name equal to that of the imported team
							$matchingTeam_ID = 0;
							$matchingTeam_ClubID = 0;
							if (count($this->teams) > 0)
							{
								foreach ($this->teams AS $team)
								{
// 									if ((strtolower($importTeam_Name) == strtolower($team->name)) &&
// 									    (strtolower($importTeam_ShortName) == strtolower($team->short_name)) &&
// 									    (strtolower($importTeam_MiddleName) == strtolower($team->middle_name)) &&
// 									    (strtolower($importTeam_Info) == strtolower($team->info)))
// 									{
                    if 
                    ( 
                    ( strtolower($importTeam_Name) == strtolower($team->name) ) &&
									  ( strtolower($importTeam_Info) == strtolower($team->info) ||
									  preg_match("/".strtolower($team->info)."/i", strtolower($importTeam_Info) )
                    
                    ) 
                    )
									{
										$matchingTeam_ID = $team->id;
										$matchingTeam_ClubID = $team->club_id;
										break;
									}
								}
							}
//$this->dump_variable("matchingTeam_ID", $matchingTeam_ID);
//$this->dump_variable("matchingTeam_ClubID", $matchingTeam_ClubID);

							// Find in database a club with the name equal to that of the imported club
							$matchingClub_ID = 0;
							$matchingClub_PlaygroundID = 0;
							$matchingClub_Country = 0;
							if (count($this->clubs))
							{
								foreach ($this->clubs as $club)
								{
									if (strtolower($importTeam_ClubName) == strtolower($club->name))
									{
										$matchingClub_ID = $club->id;
										$matchingClub_PlaygroundID = $club->standard_playground;
										$matchingClub_Country = $club->country;
										break;
									}
								}
							}
//$this->dump_variable("matchingClub_ID", $matchingClub_ID);

							$foundMatchingClubAndTeam = (($matchingClub_ID != 0) && ($matchingTeam_ClubID == $matchingClub_ID));
							$foundMatchingClub = ($matchingClub_ID != 0);
							if (!$foundMatchingClubAndTeam)
							{
								$matchingTeam_ID = 0;
								$matchingTeam_ClubID = 0;
							}

//$this->dump_variable("foundMatchingClubAndTeam", $foundMatchingClubAndTeam);
//$this->dump_variable("foundMatchingClub", $foundMatchingClub);
							?>
							<tr>
								<td nowrap='nowrap' style='text-align:center; vertical-align:middle; background-color:<?php echo $color; ?>'>
									&nbsp;
								</td>
								<?php 
                $color = $useNewEntryColor;
                if ( $foundMatchingClubAndTeam )
							  {
							  $color = $useExistingEntryColor;
							  }
							  elseif ( $foundMatchingClub )
							  {
							  $color = $useExistingClubEntryColor;
							  }
                
                //$color = $foundMatchingClubAndTeam ? $useExistingEntryColor: $useNewEntryColor; 
                
                ?>
								<td style='line-height:200%; text-align:left; vertical-align:middle; background-color:<?php echo $color; ?>' id='tetd<?php echo $key; ?>'>
									<?php 
										if ($foundMatchingClubAndTeam)
										{
											$checked1 = "checked='checked'";
											$checked2 = "";
											$checked3 = "";
										}
										elseif ($foundMatchingClub)
										{
											$checked1 = "";
											$checked2 = "checked='checked'";
											$checked3 = "";
										}
										else
										{
											$checked1 = "";
											$checked2 = "";
											$checked3 = "checked='checked'";
										}
									?>
									<!--
										Clicking on the first radio button will show a selector with "club / team" info in a popup window.
										The club_id, team_id and location of the database will be coupled to the textual entries.
									-->
									<input type='radio' name='cto_<?php echo $i; ?>' id='ecet_<?php echo $i; ?>' value='1' <?php echo $checked1; ?>
									<?php
										if ((count($this->clubs) > 0) && (count($this->teams) > 0))
										{
										  if ( $foundMatchingClub )
							        {
							        echo "onclick='javascript:openSelectWindow(";
											echo $matchingClub_ID;
											echo ",".$key;
											echo ',"selector"';
											echo ",this";
											echo ",9";		// Selection type (club&team)
											echo ")' ";
							        }
							        else
							        {
											echo "onclick='javascript:openSelectWindow(";
											echo $matchingTeam_ID;
											echo ",".$key;
											echo ',"selector"';
											echo ",this";
											echo ",9";		// Selection type (club&team)
											echo ")' ";
											}
										}
										else
										{
											// If there are no clubs/teams to select from, restore the radiobutton to new club/new team
											echo "onclick='javascript:alertAndRestore($key, 10, \"".JText::_('JL_ADMIN_XML_IMPORT_NO_CLUBS_TEAMS_IN_DB')."\")' ";
										}
									?>
									>
										<?php echo JText::_('JL_ADMIN_XML_IMPORT_EXISTING_CLUB_AND_TEAM'); ?>
									</input><br>
									<!--
										Clicking on the second radiobutton will show a selector selector with "club" info in a popup window.
										The club_id and location of the database will be coupled to the textual entries.
									-->
									<input type='radio' name='cto_<?php echo $i; ?>' id='ecnt_<?php echo $i; ?>' value='2' <?php echo $checked2; ?>
									<?php
										if (count($this->clubs) > 0)
										{
											echo "onclick='javascript:openSelectWindow(";
											echo $matchingClub_ID;
											echo ",".$key;
											echo ',"selector"';
											echo ",this";
											echo ",10";		// Selection type (club)
											echo ")' ";
										}
										else
										{
											// If there are no clubs to select from, restore the radiobutton to new club/new team
											echo "onclick='javascript:alertAndRestore($key, 10, \"".JText::_('JL_ADMIN_XML_IMPORT_NO_CLUBS_IN_DB')."\")' ";
										}
									?>
									>
										<?php echo JText::_('JL_ADMIN_XML_IMPORT_EXISTING_CLUB_NEW_TEAM'); ?>
									</input><br>
									<!--
										Clicking the third radiobutton will result in the club and  team being taken over from the import file.
									-->
									<input type='radio' name='cto_<?php echo $i; ?>' id='ncnt_<?php echo $i; ?>' value='3' <?php echo $checked3; ?>
									<?php
										echo "onclick='javascript:resetToImportValues(";
										echo $key;
										echo ",10";		// Selection type (club)
										echo ")' ";
									?>
									>
										<?php echo JText::_('JL_ADMIN_XML_IMPORT_NEW_CLUB_NEW_TEAM'); ?>
									</input><br>
								</td>
								<?php
									if ($matchingClub_ID == 0)
									{
										$ic_disabled = "";						// imported club
										$dc_disabled = " disabled='disabled'";	// database club
									}
									else
									{
										$ic_disabled = " disabled='disabled'";
										$dc_disabled = "";
									}
									if ($matchingTeam_ID == 0)
									{
										$it_disabled = "";						// imported team
										$dt_disabled = " disabled='disabled'";	// database team
									}
									else
									{
										$it_disabled = " disabled='disabled'";
										$dt_disabled = "";
									}
								?>
								<td style='text-align:left; vertical-align:top; background-color:<?php echo $color; ?>'>
									<table cellpadding='0' cellspacing='0' style='border-color:<?php echo $color; ?>; border-width:0; border-style:none'>
										<thead>
											<tr style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>; border-style:none'>
												<th style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>; width:75px'>
													&nbsp;
												</th>
												<th style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>'>
													<?php echo JText::_('Import File');?>
												</th>
												<th style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>; width:30px; text-align:center'>
													&rarr;
												</th>
												<th style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>'>
													<?php echo JText::_('Database');?>
												</th>
											</tr>
										</thead>
										<tbody>
											<tr style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>; border-style:none'>
												<td style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>'>
													<b><?php echo JText::_('JL_ADMIN_XML_IMPORT_CLUBNAME');?></b>
													<input type='<?php echo $this->input_type;?>' name='matching_ClubID_<?php echo $key;?>' value='<?php echo $matchingClub_ID;?>'/>
													<input type='<?php echo $this->input_type;?>' name='clubID_<?php echo $key;?>' value='<?php echo $importTeam_ClubID;?>' <?php echo $ic_disabled;?>/>
													<input type='<?php echo $this->input_type;?>' name='clubFileID_<?php echo $key;?>' value='<?php echo $importTeam_ClubID;?>' <?php echo $ic_disabled;?>/>
													<input type='<?php echo $this->input_type;?>' name='clubName_<?php echo $key; ?>' size='60' maxlength='100' 
															value='<?php echo stripslashes(htmlspecialchars($importTeam_ClubName));?>' <?php echo $ic_disabled;?>/>
													<input type='<?php echo $this->input_type;?>' name='clubCountry_<?php echo $key; ?>' size='60' maxlength='100' 
															value='<?php echo stripslashes(htmlspecialchars($importTeam_ClubCountry));?>' <?php echo $ic_disabled;?>/>

													<input type='<?php echo $this->input_type;?>' name='dbClubID_<?php echo $key;?>' value='<?php echo $matchingClub_ID;?>' <?php echo $dc_disabled;?>/>
													<input type='<?php echo $this->input_type;?>' name='dbClubPlaygroundID_<?php echo $key;?>' 
															value='<?php echo $matchingClub_PlaygroundID;?>' <?php echo $dc_disabled;?>/>
												</td>
												<td style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>'>
													<input type='text' name='impClubName_<?php echo $key;?>' value='<?php echo $importTeam_ClubName;?>' size='30' maxlength='45' disabled='disabled'>
												</td>
												<td style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>'>
													&nbsp;
												</td>
												<td style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>'>
													<input type='text' name='dbClubName_<?php echo $key;?>' size='30' maxlength='45' disabled='disabled'
															value='<?php echo stripslashes(htmlspecialchars($importTeam_ClubName));?>' />
												</td>
											</tr>
											<tr style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>; border-style:none'>
												<td style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>'>
													<b><?php echo JText::_('JL_ADMIN_XML_IMPORT_CLUBCOUNTRY');?></b>
												</td>
												<td style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>'>
													<input type='text' name='impClubCountry_<?php echo $key;?>' value='<?php echo $importTeam_ClubCountry;?>' size='30' maxlength='45' disabled='disabled'>
												</td>
												<td style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>'>
													&nbsp;
												</td>
												<td style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>'>
													<input type='text' name='dbClubCountry_<?php echo $key;?>' value='<?php echo $importTeam_ClubCountry; ?>' size='30' maxlength='45' disabled='disabled'>
												</td>
											</tr>
											<tr style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>; border-style:none'>
												<td style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>'>
													<input type='<?php echo $this->input_type;?>' name='matching_TeamID_<?php echo $key;?>' value='<?php echo $matchingTeam_ID;?>' <?php echo $it_disabled;?>/>
													<input type='<?php echo $this->input_type;?>' name='teamID_<?php echo $key;?>' value='<?php echo $importTeam_ID;?>' <?php echo $it_disabled;?>/>
													<input type='<?php echo $this->input_type;?>' name='teamFileID_<?php echo $key;?>' value='<?php echo $importTeam_ID;?>' <?php echo $it_disabled;?>/>
													<input type='<?php echo $this->input_type;?>' name='teamName_<?php echo $key; ?>' size='60' maxlength='100' 
															value='<?php echo stripslashes(htmlspecialchars($importTeam_Name));?>' <?php echo $it_disabled;?>/>
													<input type='<?php echo $this->input_type;?>' name='teamShortname_<?php echo $key; ?>' size='60' maxlength='100' 
															value='<?php echo stripslashes(htmlspecialchars($importTeam_ShortName));?>' <?php echo $it_disabled;?>/>
													<input type='<?php echo $this->input_type;?>' name='teamMiddleName_<?php echo $key; ?>' size='60' maxlength='100' 
															value='<?php echo stripslashes(htmlspecialchars($importTeam_MiddleName));?>' <?php echo $it_disabled;?>/>
													<input type='<?php echo $this->input_type;?>' name='teamInfo_<?php echo $key; ?>' size='60' maxlength='255' 
															value='<?php echo stripslashes(htmlspecialchars($importTeam_Info));?>' <?php echo $it_disabled;?>/>
													<input type='<?php echo $this->input_type;?>' name='dbTeamID_<?php echo $key;?>' value='<?php echo $matchingTeam_ID;?>' <?php echo $dt_disabled;?>/>
													<b><?php echo JText::_('JL_ADMIN_XML_IMPORT_TEAMNAME');?></b>
												</td>
												<td style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>'>
													<input type='text' name='impTeamName_<?php echo $key;?>' value='<?php echo $importTeam_Name;?>' size='30' maxlength='45' disabled='disabled'/>
												</td>
												<td style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>'>
													&nbsp;
												</td>
												<td style='background-color:<?php echo $color; ?>; border-color:<?php echo $color; ?>'>
													<input type='text' name='dbTeamName_<?php echo $key;?>' value='<?php echo $importTeam_Name; ?>' size='30' maxlength='45' disabled='disabled'/>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<?php
							$i++;
						}
						?>
						</tbody>
					</table>
				</fieldset>
				<p style='text-align:right;'><a href='#page_top'><?php echo JText::_('JL_ADMIN_XML_IMPORT_TOP'); ?></a></p>
				<?php
			}
			?>

			<?php
			//===============================================================================================================
			//
			//                                             PLAYGROUND IMPORT
			//
			//===============================================================================================================
			if (isset($playgrounds) && (is_array($playgrounds) && count($playgrounds) > 0))
			{
				?>
				<fieldset>
					<legend><strong><?php echo JText::_('Playground Assignment'); ?></strong></legend>
					<table class='adminlist'>
						<thead>
							<tr>
								<th width='5%' nowrap='nowrap'><?php
									echo JText::_('JL_ADMIN_XML_IMPORT_ALL_NEW');
									echo '<br />';
									echo '<input type="checkbox" name="togglePlaygrounds" value="" onclick="checkAllPlaygrounds('.count($playgrounds).')" />';
								?></th>
								<th><?php echo JText::_('Playground'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$color1="#DDDDDD";
							$color2="#EEEEEE";
							foreach ($playgrounds AS $key=> $playground)
							{
								if ($key%2==1){$color=$color1;}else{$color=$color2;}
								?>
								<tr>
									<td style='text-align:center; vertical-align:middle; background-color:<?php echo $color; ?>'>
										<input type='checkbox' value="<?php echo $key; ?>" name='plid[]' id='pl<?php echo $i; ?>' onchange='testPlaygroundData(this,<?php echo $key; ?>)' />
									</td>
									<?php
									// Playground column starts here
									$color=$useNewEntryColor;
									$foundMatchingPlayground=0;
									$foundMatchingPlaygroundName='';
									$foundMatchingPlaygroundShortname='';
									$playgroundClubID=0;

									if (count($this->playgrounds) > 0)
									{
										foreach ($this->playgrounds AS $row1)
										{
											if	(strtolower($playground->name)==strtolower($row1->name))
											{
												$color=$useExistingEntryColor;
												$foundMatchingPlayground=$row1->id;
												$foundMatchingPlaygroundName=$row1->name;
												$foundMatchingPlaygroundShortname=$row1->short_name;
												$playgroundClubID=$row1->club_id;
												break;
											}
										}
									}
									?>
									<td width='45%' style='text-align:left; background-color:<?php echo $color; ?>' id='pltd<?php echo $key; ?>'>
										<?php
										if ($foundMatchingPlayground)
										{
											$checked="checked='checked' ";
											$disabled='';
										}
										else
										{
											$checked='';
											$disabled="disabled='disabled' ";
										}
										echo "<input type='checkbox' name='choosePlayground_$key' $checked";
										echo "onclick='if(this.checked)
												{
													document.adminForm.selectPlayground_$key.checked=false;
													document.adminForm.createPlayground_$key.checked=false;
													document.adminForm.playgroundName_$key.disabled=true;
													document.adminForm.playgroundShortname_$key.disabled=true;
												}
												else
												{
												}' $disabled ";
										echo "/>&nbsp;";
										$output1="<input type='text' name='dbPlaygroundName_$key' size='45' maxlength='45' value=\"".stripslashes(htmlspecialchars($foundMatchingPlaygroundName))."\" style='font-weight: bold;' disabled='disabled' />";
										$output2="<input type='text' name='dbPaygroundShortname_$key' size='20' maxlength='15' value=\"".stripslashes(htmlspecialchars($foundMatchingPlaygroundShortname))."\" style='font-weight: bold;' disabled='disabled' />";
										echo JText::sprintf('Use existing %1$s - %2$s from Database',$output1,$output2);
										echo "<input type='$this->input_type' name='dbPlaygroundClubID_$key' value=\"$playgroundClubID\" $disabled />";
										echo "<input type='$this->input_type' name='dbPlaygroundID_$key' value=\"$foundMatchingPlayground\" $disabled />";
										echo '<br />';

										if (count($this->playgrounds) > 0)
										{
											echo "<input type='checkbox' name='selectPlayground_$key' ";
											echo "onclick='javascript:openSelectWindow(";
											echo $foundMatchingPlayground;
											echo ",".$key;
											echo ',"selector"';
											echo ",this";
											echo ",4";
											echo ")' ";
											echo "/>&nbsp;";
											echo JText::_('Assign other Playground from Database');
											echo '<br />';
										}
										else
										{
											echo "<input type='$this->input_type' name='selectPlayground_$key' />";
										}

										if ($foundMatchingPlayground)
										{
											$checked='';
											$disabled="disabled'disabled' ";
										}
										else
										{
											$checked="checked='checked' ";
											$disabled='';
										}
										echo "<input type='checkbox' name='createPlayground_$key' $checked ";
										echo "onclick='if(this.checked)
															{
																document.adminForm.choosePlayground_$key.checked=false;
																document.adminForm.selectPlayground_$key.checked=false;
																document.adminForm.playgroundName_$key.disabled=false;
																document.adminForm.playgroundShortname_$key.disabled=false;
															}
															else
															{
																document.adminForm.playgroundName_$key.disabled=true;
																document.adminForm.playgroundShortname_$key.disabled=true;
													}' ";
										echo "/>&nbsp;";
										echo JText::_('Create new Playground');
										?>
										<br />
										<table cellspacing='0' cellpadding='0'>
											<tr>
												<td>
													<?php echo '<b>'.JText::_('Playgroundname').'</b>'; ?><br >
													<input type='<?php echo $this->input_type;?>' name='playgroundID_<?php echo $key; ?>' value="<?php echo $key; ?>" <?php echo $disabled; ?> />
													<input type='text' name='playgroundName_<?php echo $key; ?>' size='45' maxlength='45' value="<?php echo stripslashes(htmlspecialchars($playground->name)); ?>" <?php echo $disabled; ?> />
												</td>
												<td>
													<?php echo '<b>'.JText::_('Shortname').'</b>'; ?><br />
													<input type='text' name='playgroundShortname_<?php echo $key; ?>' size='20' maxlength='15' value="<?php echo stripslashes(htmlspecialchars($playground->short_name)); ?>" <?php echo $disabled; ?> />
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<?php
								$i++;
							}
							?>
						</tbody>
					</table>
				</fieldset>
				<p style='text-align:right;'><a href='#page_top'><?php echo JText::_('JL_ADMIN_XML_IMPORT_TOP'); ?></a></p>
				<?php
			}
			?>

			<?php
			//===============================================================================================================
			//
			//                                             EVENT IMPORT
			//
			//===============================================================================================================
			if (isset($events) && (is_array($events) && count($events) > 0))
			{
				?>
				<fieldset>
					<legend><strong><?php echo JText::_('Event Assignment'); ?></strong></legend>
					<table class='adminlist'>
						<thead>
							<tr>
								<th width='5%' nowrap='nowrap'><?php
									echo JText::_('JL_ADMIN_XML_IMPORT_ALL_NEW');
									echo '<br />';
									echo '<input type="checkbox" name="toggleEvents" value="" onclick="checkAllEvents('.count($events).')" />';
								?></th>
								<th><?php echo JText::_('Event'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$color1="#DDDDDD";
							$color2="#EEEEEE";
							foreach ($events AS $key=> $event)
							{
								if ($key%2==1){$color=$color1;}else{$color=$color2;}
								?>
								<tr>
									<td style='text-align:center; vertical-align:middle; background-color:<?php echo $color; ?>'>
										<input type='checkbox' value="<?php echo $key; ?>" name='evid[]' id='ev<?php echo $i; ?>' onchange='testEventsData(this,<?php echo $key; ?>)' />
									</td>
									<?php
									// Event column starts here
									$color=$useNewEntryColor;
									$foundMatchingEvent=0;
									$foundMatchingEventName='';

									if (count($this->events) > 0)
									{
										foreach ($this->events AS $row1)
										{
											if ((strtolower($event->name)==strtolower($row1->name)) ||
												(strtolower(JText::_($event->name))==strtolower(JText::_($row1->name))))
											{
												$color=$useExistingEntryColor;
												$foundMatchingEvent=$row1->id;
												$foundMatchingEventName=$row1->name;
												break;
											}
										}
									}
									?>
									<td width='45%' style='text-align:left; background-color:<?php echo $color; ?>' id='evtd<?php echo $key; ?>'>
										<?php
										if ($foundMatchingEvent)
										{
											$checked="checked='checked' ";
											$disabled='';
										}
										else
										{
											$checked='';
											$disabled="disabled='disabled' ";
										}
										echo "<input type='checkbox' name='chooseEvent_$key' $checked";
										echo "onclick='if(this.checked)
												{
													document.adminForm.selectEvent_$key.checked=false;
													document.adminForm.createEvent_$key.checked=false;
													document.adminForm.eventName_$key.disabled=true;
												}
												else
												{
												}' $disabled ";
										echo "/>&nbsp;";
										$output1="<input type='text' name='dbEventName_$key' size='45' maxlength='75' value=\"".stripslashes(htmlspecialchars(JText::_($foundMatchingEventName)))."\" style='font-weight: bold;' disabled='disabled' />";
										echo JText::sprintf('Use existing %1$s from Database',$output1);
										echo "<input type='hidden' name='dbEventID_$key' value=\"$foundMatchingEvent\" $disabled />";
										echo '<br />';

										if (count($this->events) > 0)
										{
											echo "<input type='checkbox' name='selectEvent_$key' ";
											echo "onclick='javascript:openSelectWindow(";
											echo $foundMatchingEvent;
											echo ",".$key;
											echo ',"selector"';
											echo ",this";
											echo ",5";
											echo ")' ";
											echo "/>&nbsp;";
											echo JText::_('Assign other Event from Database');
											echo '<br />';
										}
										else
										{
											echo "<input type='hidden' name='selectEvent_$key' />";
										}

										if ($foundMatchingEvent)
										{
											$checked='';
											$disabled="disabled'disabled' ";
										}
										else
										{
											$checked="checked='checked' ";
											$disabled='';
										}
										echo "<input type='checkbox' name='createEvent_$key' $checked ";
										echo "onclick='if(this.checked)
												{
													document.adminForm.chooseEvent_$key.checked=false;
													document.adminForm.selectEvent_$key.checked=false;
													document.adminForm.eventName_$key.disabled=false;
												}
												else
												{
													document.adminForm.eventName_$key.disabled=true;
												}' ";
										echo "/>&nbsp;";
										echo JText::_('Create new Event');
										?>
										<br />
										<table cellspacing='0' cellpadding='0'>
											<tr>
												<td>
													<?php echo '<b>'.JText::_('Eventname').'</b>'; ?><br />
													<input type='hidden' name='eventID_<?php echo $key; ?>' value="<?php echo $key; ?>" <?php echo $disabled; ?> />
													<input type='text' name='eventName_<?php echo $key; ?>' size='75' maxlength='75' value="<?php echo stripslashes(htmlspecialchars($event->name)); ?>" <?php echo $disabled; ?> />
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<?php
								$i++;
							}
							?>
						</tbody>
					</table>
				</fieldset>
				<p style='text-align:right;'><a href='#page_top'><?php echo JText::_('JL_ADMIN_XML_IMPORT_TOP'); ?></a></p>
				<?php
			}
			?>
			<?php
			//===============================================================================================================
			//
			//                                             PARENT POSITION IMPORT
			//
			//===============================================================================================================
			if (isset($parentpositions) && (is_array($parentpositions) && count($parentpositions) > 0))
			{
				?>
				<fieldset>
					<legend><strong><?php echo JText::_('Parent-Position Assignment'); ?></strong></legend>
					<table class='adminlist'>
						<thead>
							<tr>
								<th width='5%' nowrap='nowrap'><?php
									echo JText::_('JL_ADMIN_XML_IMPORT_ALL_NEW');
									echo '<br />';
									echo '<input type="checkbox" name="toggleParentPositions" value="" onclick="checkAllParentPositions('.count($parentpositions).')" />';
								?></th>
								<th><?php echo JText::_('Parent-Position'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$color1="#DDDDDD";
							$color2="#EEEEEE";
							foreach ($parentpositions AS $key=> $parentposition)
							{
								if ($key%2==1){$color=$color1;}else{$color=$color2;}
								?>
								<tr>
									<td style='text-align:center; vertical-align:middle; background-color:<?php echo $color; ?>'>
										<input type='checkbox' value="<?php echo $key; ?>" name='ppid[]' id='pp<?php echo $i; ?>' onchange='testParentPositionsData(this,<?php echo $key; ?>)' />
									</td>
									<?php
									// ParentPosition column starts here
									$color=$useNewEntryColor;
									$foundMatchingParentPosition=0;
									$foundMatchingParentPositionName='';

									if (count($this->parentpositions) > 0)
									{
										foreach ($this->parentpositions AS $row1)
										{
											if ((strtolower($parentposition->name)==strtolower($row1->name)))
											{
												$color=$useExistingEntryColor;
												$foundMatchingParentPosition=$row1->id;
												$foundMatchingParentPositionName=$row1->name;
												break;
											}
										}
									}
									?>
									<td width='45%' style='text-align:left; background-color:<?php echo $color; ?>' id='potd<?php echo $key; ?>'>
										<?php
										if ($foundMatchingParentPosition)
										{
											$checked="checked='checked' ";
											$disabled='';
										}
										else
										{
											$checked='';
											$disabled="disabled='disabled' ";
										}
										echo "<input type='checkbox' name='chooseParentPosition_$key' $checked";
										echo "onclick='if(this.checked)
												{
													document.adminForm.selectParentPosition_$key.checked=false;
													document.adminForm.createParentPosition_$key.checked=false;
													document.adminForm.parentPositionName_$key.disabled=true;
												}
												else
												{
												}' $disabled ";
										echo "/>&nbsp;";
										$output1="<input type='text' name='dbParentPositionName_$key' size='45' maxlength='75' value=\"".stripslashes(htmlspecialchars(JText::_($foundMatchingParentPositionName)))."\" style='font-weight: bold;' disabled='disabled' />";
										echo JText::sprintf('Use existing %1$s from Database',$output1);
										echo "<input type='hidden' name='dbParentPositionID_$key' value=\"$foundMatchingParentPosition\" $disabled />";
										echo '<br />';

										if (count($this->parentpositions) > 0)
										{
											echo "<input type='checkbox' name='selectParentPosition_$key' ";
											echo "onclick='javascript:openSelectWindow(";
											echo $foundMatchingParentPosition;
											echo ",".$key;
											echo ',"selector"';
											echo ",this";
											echo ",7";
											echo ")' ";
											echo "/>&nbsp;";
											echo JText::_('Assign other Parent-Position from Database');
											echo '<br />';
										}
										else
										{
											echo "<input type='hidden' name='selectParentPosition_$key' />";
										}

										if ($foundMatchingParentPosition)
										{
											$checked='';
											$disabled="disabled'disabled' ";
										}
										else
										{
											$checked="checked='checked' ";
											$disabled='';
										}
										echo "<input type='checkbox' name='createParentPosition_$key' $checked ";
										echo "onclick='if(this.checked)
												{
													document.adminForm.chooseParentPosition_$key.checked=false;
													document.adminForm.selectParentPosition_$key.checked=false;
													document.adminForm.parentPositionName_$key.disabled=false;
												}
												else
												{
													document.adminForm.parentPositionName_$key.disabled=true;
												}' ";
										echo "/>&nbsp;";
										echo JText::_('Create new Parent-Position');
										?>
										<br />
										<table cellspacing='0' cellpadding='0'>
											<tr>
												<td>
													<?php echo '<b>'.JText::_('Parent-Positionname').'</b>'; ?><br />
													<input type='hidden' name='parentPositionID_<?php echo $key; ?>' value="<?php echo $key; ?>" <?php echo $disabled; ?> />
													<input type='text' name='parentPositionName_<?php echo $key; ?>' size='75' maxlength='75' value="<?php echo stripslashes(htmlspecialchars($parentposition->name)); ?>" <?php echo $disabled; ?> />
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<?php
								$i++;
							}
							?>
						</tbody>
					</table>
				</fieldset>
				<p style='text-align:right;'><a href='#page_top'><?php echo JText::_('JL_ADMIN_XML_IMPORT_TOP'); ?></a></p>
				<?php
			}
			?>
			<?php
			//===============================================================================================================
			//
			//                                             POSITION IMPORT
			//
			//===============================================================================================================
			if (isset($positions) && (is_array($positions) && count($positions) > 0))
			{
				?>
				<fieldset>
					<legend><strong><?php echo JText::_('Position Assignment'); ?></strong></legend>
					<table class='adminlist'>
						<thead>
							<tr>
								<th width='5%' nowrap='nowrap'><?php
									echo JText::_('JL_ADMIN_XML_IMPORT_ALL_NEW');
									echo '<br />';
									echo '<input type="checkbox" name="togglePositions" value="" onclick="checkAllPositions('.count($positions).')" />';
								?></th>
								<th><?php echo JText::_('Position'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$color1="#DDDDDD";
							$color2="#EEEEEE";
							foreach ($positions AS $key=> $position)
							{
								if ($key%2==1){$color=$color1;}else{$color=$color2;}
								?>
								<tr>
									<td style='text-align:center; vertical-align:middle; background-color:<?php echo $color; ?>'>
										<input type='checkbox' value="<?php echo $key; ?>" name='poid[]' id='po<?php echo $i; ?>' onchange='testPositionsData(this,<?php echo $key; ?>)' />
									</td>
									<?php
									// Position column starts here
									$color=$useNewEntryColor;
									$foundMatchingPosition=0;
									$foundMatchingPositionName='';

									if (count($this->positions) > 0)
									{
										foreach ($this->positions AS $row1)
										{
											if ((strtolower($position->name)==strtolower($row1->name)))
											{
												$color=$useExistingEntryColor;
												$foundMatchingPosition=$row1->id;
												$foundMatchingPositionName=$row1->name;
												break;
											}
										}
									}
									?>
									<td width='45%' style='text-align:left; background-color:<?php echo $color; ?>' id='potd<?php echo $key; ?>'>
										<?php
										if ($foundMatchingPosition)
										{
											$checked="checked='checked' ";
											$disabled='';
										}
										else
										{
											$checked='';
											$disabled="disabled='disabled' ";
										}
										echo "<input type='checkbox' name='choosePosition_$key' $checked";
										echo "onclick='if(this.checked)
												{
													document.adminForm.selectPosition_$key.checked=false;
													document.adminForm.createPosition_$key.checked=false;
													document.adminForm.positionName_$key.disabled=true;
												}
												else
												{
												}' $disabled ";
										echo "/>&nbsp;";
										$output1="<input type='text' name='dbPositionName_$key' size='45' maxlength='75' value=\"".stripslashes(htmlspecialchars(JText::_($foundMatchingPositionName)))."\" style='font-weight: bold;' disabled='disabled' />";
										echo JText::sprintf('Use existing %1$s from Database',$output1);
										echo "<input type='hidden' name='dbPositionID_$key' value=\"$foundMatchingPosition\" $disabled />";
										echo '<br />';

										if (count($this->positions) > 0)
										{
											echo "<input type='checkbox' name='selectPosition_$key' ";
											echo "onclick='javascript:openSelectWindow(";
											echo $foundMatchingPosition;
											echo ",".$key;
											echo ',"selector"';
											echo ",this";
											echo ",6";
											echo ")' ";
											echo "/>&nbsp;";
											echo JText::_('Assign other Position from Database');
											echo '<br />';
										}
										else
										{
											echo "<input type='hidden' name='selectPosition_$key' />";
										}

										if ($foundMatchingPosition)
										{
											$checked='';
											$disabled="disabled'disabled' ";
										}
										else
										{
											$checked="checked='checked' ";
											$disabled='';
										}
										echo "<input type='checkbox' name='createPosition_$key' $checked ";
										echo "onclick='if(this.checked)
												{
													document.adminForm.choosePosition_$key.checked=false;
													document.adminForm.selectPosition_$key.checked=false;
													document.adminForm.positionName_$key.disabled=false;
												}
												else
												{
													document.adminForm.positionName_$key.disabled=true;
												}' ";
										echo "/>&nbsp;";
										echo JText::_('Create new Position');
										?>
										<br />
										<table cellspacing='0' cellpadding='0'>
											<tr>
												<td>
													<?php echo '<b>'.JText::_('Positionname').'</b>'; ?><br />
													<input type='hidden' name='positionID_<?php echo $key; ?>' value="<?php echo $key; ?>" <?php echo $disabled; ?> />
													<input type='text' name='positionName_<?php echo $key; ?>' size='75' maxlength='75' value="<?php echo stripslashes(htmlspecialchars($position->name)); ?>" <?php echo $disabled; ?> />
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<?php
								$i++;
							}
							?>
						</tbody>
					</table>
				</fieldset>
				<p style='text-align:right;'><a href='#page_top'><?php echo JText::_('JL_ADMIN_XML_IMPORT_TOP'); ?></a></p>
				<?php
			}
			?>

			<?php
			//===============================================================================================================
			//
			//                                             STATISTIC IMPORT
			//
			//===============================================================================================================
			if (isset($statistics) && (is_array($statistics) && count($statistics) > 0))
			{
				?>
				<fieldset>
					<legend><strong><?php echo JText::_('Statistics Assignment'); ?></strong></legend>
					<table class='adminlist'>
						<thead>
							<tr>
								<th width='5%' nowrap='nowrap'><?php
									echo JText::_('JL_ADMIN_XML_IMPORT_ALL_NEW');
									echo '<br />';
									echo '<input type="checkbox" name="toggleStatistics" value="" onclick="checkAllStatistics('.count($statistics).')" />';
								?></th>
								<th><?php echo JText::_('Statistic'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$color1="#DDDDDD";
							$color2="#EEEEEE";
							foreach ($statistics AS $key=> $statistic)
							{
								if ($key%2==1){$color=$color1;}else{$color=$color2;}
								?>
								<tr>
									<td style='text-align:center; vertical-align:middle; background-color:<?php echo $color; ?>'>
										<input type='checkbox' value="<?php echo $key; ?>" name='stid[]' id='st<?php echo $i; ?>' onchange='testStatisticsData(this,<?php echo $key; ?>)' />
									</td>
									<?php
									// Statistic column starts here
									$color=$useNewEntryColor;
									$foundMatchingStatistic=0;
									$foundMatchingStatisticName='';
									$foundMatchingStatisticShort='';
									$foundMatchingStatisticClass='';
									$foundMatchingStatisticNote='';

									if (count($this->statistics) > 0)
									{
										foreach ($this->statistics AS $row1)
										{
											if ((strtolower($statistic->name)==strtolower($row1->name))
												&& (strtolower($statistic->class)==strtolower($row1->class))
												// && (strtolower($statistic->params)==strtolower($row1->params))
												// && (strtolower($statistic->baseparams)==strtolower($row1->baseparams))
												)
											{
												$color=$useExistingEntryColor;
												$foundMatchingStatistic=$row1->id;
												$foundMatchingStatisticName=$row1->name;
												$foundMatchingStatisticShort=$row1->short;
												$foundMatchingStatisticClass=$row1->class;
												$foundMatchingStatisticNote=$row1->note;
												break;
											}
										}
									}
									?>
									<td width='45%' style='text-align:left; background-color:<?php echo $color; ?>' id='potd<?php echo $key; ?>'>
										<?php
										if ($foundMatchingStatistic)
										{
											$checked="checked='checked' ";
											$disabled='';
										}
										else
										{
											$checked='';
											$disabled="disabled='disabled' ";
										}
										echo "<input type='checkbox' name='chooseStatistic_$key' $checked";
										echo "onclick='if(this.checked)
												{
													document.adminForm.selectStatistic_$key.checked=false;
													document.adminForm.createStatistic_$key.checked=false;
													document.adminForm.statisticName_$key.disabled=true;
												}
												else
												{
												}' $disabled ";
										echo "/>&nbsp;";
										$output1="<input type='text' name='dbStatisticName_$key' size='45' maxlength='75' value=\"".stripslashes(htmlspecialchars($foundMatchingStatisticName))."\" style='font-weight: bold;' disabled='disabled' />";
										$output2="<input type='text' name='dbStatisticShort_$key' size='15' maxlength='10' value=\"".stripslashes(htmlspecialchars($foundMatchingStatisticShort))."\" style='font-weight: bold;' disabled='disabled' />";
										$output3="<input type='text' name='dbStatisticClass_$key' size='15' maxlength='50' value=\"".stripslashes(htmlspecialchars($foundMatchingStatisticClass))."\" style='font-weight: bold;' disabled='disabled' />";
										$output4="<input type='text' name='dbStatisticNote_$key' size='15' maxlength='100' value=\"".stripslashes(htmlspecialchars($foundMatchingStatisticNote))."\" style='font-weight: bold;' disabled='disabled' />";
										echo JText::sprintf('Use existing %1$s - %2$s - %3$s - %4$s from Database',$output1,$output2,$output3,$output4);
										echo "<input type='hidden' name='dbStatisticID_$key' value=\"$foundMatchingStatistic\" $disabled />";
										echo '<br />';

										if (count($this->statistics) > 0)
										{
											echo "<input type='checkbox' name='selectStatistic_$key' ";
											echo "onclick='javascript:openSelectWindow(";
											echo $foundMatchingStatistic;
											echo ",".$key;
											echo ',"selector"';
											echo ",this";
											echo ",8";
											echo ")' ";
											echo "/>&nbsp;";
											echo JText::_('Assign other Statistic from Database');
											echo '<br />';
										}
										else
										{
											echo "<input type='hidden' name='selectStatistic_$key' />";
										}

										if ($foundMatchingStatistic)
										{
											$checked='';
											$disabled="disabled'disabled' ";
										}
										else
										{
											$checked="checked='checked' ";
											$disabled='';
										}
										echo "<input type='checkbox' name='createStatistic_$key' $checked ";
										echo "onclick='if(this.checked)
												{
													document.adminForm.chooseStatistic_$key.checked=false;
													document.adminForm.selectStatistic_$key.checked=false;
													document.adminForm.statisticName_$key.disabled=false;
												}
												else
												{
													document.adminForm.statisticName_$key.disabled=true;
												}' ";
										echo "/>&nbsp;";
										echo JText::_('Create new Statistic');
										?>
										<br />
										<table cellspacing='0' cellpadding='0'>
											<tr>
												<td>
													<?php echo '<b>'.JText::_('Statisticname').'</b>'; ?><br />
													<input type='hidden' name='statisticID_<?php echo $key; ?>' value="<?php echo $key; ?>" <?php echo $disabled; ?> />
													<input type='text' name='statisticName_<?php echo $key; ?>' size='100' maxlength='75' value="<?php echo stripslashes(htmlspecialchars($statistic->name)); ?>" <?php echo $disabled; ?> />
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<?php
								$i++;
							}
							?>
						</tbody>
					</table>
				</fieldset>
				<p style='text-align:right;'><a href='#page_top'><?php echo JText::_('JL_ADMIN_XML_IMPORT_TOP'); ?></a></p>
				<?php
			}
			?>

			<?php
			//===============================================================================================================
			//
			//                                             PERSON IMPORT
			//
			//===============================================================================================================
			if (isset($persons) && (is_array($persons) && count($persons) > 0))
			{
				?>
				<fieldset>
					<legend><strong><?php echo JText::_('JL_ADMIN_XML_IMPORT_PERSON_LEGEND'); ?></strong></legend>
					<table class='adminlist'>
						<thead>
							<tr>
								<th width='5%' nowrap='nowrap'><?php
									echo JText::_('JL_ADMIN_XML_IMPORT_ALL_NEW');
									echo '<br />';
									echo '<input type="checkbox" name="togglePersons" value="" onclick="checkAllPersons('.count($persons).')" />';
								?></th>
								<th><?php echo JText::_('JL_ADMIN_XML_IMPORT_PERSON_DATA'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$color1="#DDDDDD";
							$color2="#EEEEEE";
							foreach ($persons AS $key=> $person)
							{
								if ($key%2==1){$color=$color1;}else{$color=$color2;}
								?>
								<tr>
									<td style='text-align:center; vertical-align:middle; background-color:<?php echo $color; ?>'>
										<input type='checkbox' value="<?php echo $key; ?>" name='peid[]' id='pe<?php echo $i; ?>' onchange='testPersonsData(this,<?php echo $key; ?>)' />
									</td>
									<?php
									// Person column starts here
									$color=$useNewEntryColor;
									$foundMatchingPerson=0;
									$foundMatchingPersonName='';
									$foundMatchingPersonLastname='';
									$foundMatchingPersonFirstname='';
									$foundMatchingPersonNickname='';
									$foundMatchingPersonBirthday='';

									if (count($this->persons) > 0)
									{
										foreach ($this->persons AS $row1)
										{
											if	((strtolower($person->lastname)==strtolower($row1->lastname))
												&& (strtolower($person->firstname)==strtolower($row1->firstname))
												&&(strtolower($person->nickname)==strtolower($row1->nickname))
												&&($person->birthday==$row1->birthday)
												)
											{
												$color=$useExistingEntryColor;
												$foundMatchingPerson=$row1->id;
												$foundMatchingPersonLastname=$row1->lastname;
												$foundMatchingPersonFirstname=$row1->firstname;
												$foundMatchingPersonNickname=$row1->nickname;
												$foundMatchingPersonBirthday=$row1->birthday;
												break;
											}
										}
									}
									?>
									<td width='45%' style='text-align:left; background-color:<?php echo $color; ?>' id='prtd<?php echo $key; ?>'>
										<?php
										if ($foundMatchingPerson)
										{
											$checked="checked='checked' ";
											$disabled='';
										}
										else
										{
											$checked='';
											$disabled="disabled='disabled' ";
										}
										echo "<input type='checkbox' name='choosePerson_$key' $checked";
										echo "onclick='if(this.checked)
												{
													document.adminForm.selectPerson_$key.checked=false;
													document.adminForm.createPerson_$key.checked=false;
													document.adminForm.personLastname_$key.disabled=true;
													document.adminForm.personFirstname_$key.disabled=true;
													document.adminForm.personNickname_$key.disabled=true;
													document.adminForm.personBirthday_$key.disabled=true;
												}
												else
												{
												}' $disabled ";
										echo "/>&nbsp;";
										$output1="<input type='text' name='dbPersonLastname_$key' size='30' maxlength='45' value=\"".stripslashes(htmlspecialchars($foundMatchingPersonLastname))."\" style='font-weight: bold;' disabled='disabled' />";
										$output2="<input type='text' name='dbPersonFirstname_$key' size='30' maxlength='45' value=\"".stripslashes(htmlspecialchars($foundMatchingPersonFirstname))."\" style='font-weight: bold;' disabled='disabled' />";
										$output3="<input type='text' name='dbPersonNickname_$key' size='30' maxlength='45' value=\"".stripslashes(htmlspecialchars($foundMatchingPersonNickname))."\" style='font-weight: bold;' disabled='disabled' />";
										$output4="<input type='text' name='dbPersonBirthday_$key' value=\"$foundMatchingPersonBirthday\" maxlength='10' size='11' style='font-weight: bold;' disabled='disabled' />";
										echo JText::sprintf('JL_ADMIN_XML_IMPORT_USE_PERSON',$output1,$output2,$output3,$output4);
										echo "<input type='hidden' name='dbPersonID_$key' value=\"$foundMatchingPerson\" $disabled />";
										echo '<br />';

										if (count($this->persons) > 0)
										{
											echo "<input type='checkbox' name='selectPerson_$key' ";
											echo "onclick='javascript:openSelectWindow(";
											echo $foundMatchingPerson;
											echo ",".$key;
											echo ',"selector"';
											echo ",this";
											echo ",3";
											echo ")' ";
											echo "/>&nbsp;";
											echo JText::_('JL_ADMIN_XML_IMPORT_ASSIGN_PERSON');
											echo '<br />';
										}
										else
										{
											echo "<input type='hidden' name='selectPerson_$key' />";
										}

										if ($foundMatchingPerson)
										{
											$checked='';
											$disabled="disabled'disabled' ";
										}
										else
										{
											$checked="checked='checked' ";
											$disabled='';
										}
										echo "<input type='checkbox' name='createPerson_$key' $checked ";
										echo "onclick='if(this.checked)
															{
																document.adminForm.choosePerson_$key.checked=false;
																document.adminForm.selectPerson_$key.checked=false;
																document.adminForm.personLastname_$key.disabled=false;
																document.adminForm.personFirstname_$key.disabled=false;
																document.adminForm.personNickname_$key.disabled=false;
																document.adminForm.personBirthday_$key.disabled=false;
															}
															else
															{
																document.adminForm.personLastname_$key.disabled=true;
																document.adminForm.personFirstname_$key.disabled=true;
																document.adminForm.personNickname_$key.disabled=true;
																document.adminForm.personBirthday_$key.disabled=true;
													}' ";
										echo "/>&nbsp;";
										echo JText::_('JL_ADMIN_XML_IMPORT_CREATE_PERSON');
										?>
										<br />
										<table cellspacing='0' cellpadding='0'>
											<tr>
												<td><?php echo '<b>'.JText::_('JL_ADMIN_XML_IMPORT_LNAME').'</b>'; ?><br />
													<input type='hidden' name='personID_<?php echo $key; ?>' value="<?php echo $key; ?>" <?php echo $disabled; ?> />
													<input type='text' name='personLastname_<?php echo $key; ?>' size='30' maxlength='45' value="<?php echo stripslashes(htmlspecialchars($person->lastname)); ?>" <?php echo $disabled; ?> />
												</td>
												<td><?php echo '<b>'.JText::_('JL_ADMIN_XML_IMPORT_FNAME').'</b>'; ?><br />
													<input type='text' name='personFirstname_<?php echo $key; ?>' size='30' maxlength='45' value="<?php echo stripslashes(htmlspecialchars($person->firstname)); ?>" <?php echo $disabled; ?> />
												</td>
												<td><?php echo '<b>'.JText::_('JL_ADMIN_XML_IMPORT_NNAME').'</b>'; ?><br />
													<input type='text' name='personNickname_<?php echo $key; ?>' size='30' maxlength='45' value="<?php echo stripslashes(htmlspecialchars($person->nickname)); ?>" <?php echo $disabled; ?> />
												</td>
												<td><?php echo '<b>'.JText::_('JL_ADMIN_XML_IMPORT_BIRTHDAY').'</b>'; ?><br />
													<input type='text' name='personBirthday_<?php echo $key; ?>' maxlength='10' size='11' value="<?php echo $person->birthday; ?>" <?php echo $disabled; ?> />
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<?php
								$i++;
							}
							?>
						</tbody>
					</table>
				</fieldset>
				<p style='text-align:right;'><a href='#page_top'><?php echo JText::_('JL_ADMIN_XML_IMPORT_TOP'); ?></a></p>
				<?php
			}
			?>
		</form>
	</div>
	<?php
	if (JComponentHelper::getParams('com_joomleague')->get('show_debug_info',0))
	{
		echo '<center><hr>';
			echo JText::sprintf('Memory Limit is %1$s',ini_get('memory_limit')).'<br />';
			echo JText::sprintf('Memory Peak Usage was %1$s Bytes',number_format(memory_get_peak_usage(true),0,'','.')).'<br />';
			echo JText::sprintf('Time Limit is %1$s seconds',ini_get('max_execution_time')).'<br />';
			$mtime=microtime();
			$mtime=explode(" ",$mtime);
			$mtime=$mtime[1] + $mtime[0];
			$endtime=$mtime;
			$totaltime=($endtime - $this->starttime);
			echo JText::sprintf('This page was created in %1$s seconds',$totaltime);
		echo '<hr></center>';
	}
}
?>