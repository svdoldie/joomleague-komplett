
		<fieldset class="adminform">
			<legend>
				<?php
				echo JText::_( $this->teams->team1 );
				?>
			</legend>
	<table>
		<thead>
			<tr>
				<th style="text-align:left; "></th>
				<th style="text-align:left; width: 100pt; "><?php echo JText::_( 'JL_ADMIN_MATCH_EEBB_PERSON' ); ?></th>
				<?php
				foreach ( $this->events as $ev)
				{
					?>
					<th style="text-align:center; ">
					<?php
					if ( JFile::exists( JPATH_SITE.DS.$ev->icon ) )
					{
						$imageTitle = JText::sprintf( '%1$s', JText::_( $ev->text ) );
						$iconFileName = $ev->icon;
						echo JHTML::_( 'image', $iconFileName, $imageTitle, 'title= "' . $imageTitle . '"' );
					} else {
						echo JText::_( $ev->text ) ;
					}
					?>
					</th>
					<?php
				}
				?>
			</tr>
		</thead>
		<tbody>
			<?php
			$model =& $this->getModel();
			$tehp = 0;
			for( $i=0 , $n = count( $this->homeRoster ); $i < $n; $i++ )
			{
					$row =& $this->homeRoster[$i];
					if($row->value == 0) continue;
					?>
					<tr id="row<?php echo $i;?>">
						<td style="text-align:left; ">
						<input type="hidden" name="player_id_h_<?php echo $i;?>" value="<?php echo $row->value;?>" />
						<input type="hidden" name="team_id_h_<?php echo $i;?>" value="<?php echo $row->projectteam_id;?>" />
						<input type="checkbox" id="cb_h<?php echo $i;?>" name="cid_h<?php echo $i;?>" value="cb_h" onclick="isChecked(this.checked);"/>
						</td>
						<td style="text-align:left; ">
						<?php echo JoomleagueHelper::formatName(null, $row->firstname, $row->nickname, $row->lastname, 0) ?>
						</td>
						<?php
						//total events home player
						$tehp = 0;
						foreach ( $this->events as $ev)
						{
							$tehp++;	
							$this->assignRef( 'evbb', $model->getPlayerEventsbb( $row->value, $ev->value ) );	
							?>
							<td style="text-align:center; ">
							<input type="hidden" name="event_type_id_h_<?php echo $i.'_'.$tehp;?>" value="<?php echo $ev->value;?>" />
							<input type="hidden" name="event_id_h_<?php echo $i.'_'.$tehp;?>" value="<?php echo $this->evbb[0]->id;?>" />
							<input type="text" size="3" class="inputbox" name="event_sum_h_<?php echo $i.'_'.$tehp; ?>" value="<?php echo ( ($this->evbb[0]->event_sum > 0) ? $this->evbb[0]->event_sum : '' ); ?>" onchange="document.getElementById('cb_h<?php echo $i;?>').checked=true" />
						<?php
						}
						?>
					</tr>
					<?php
			}
			?>
			<input type="hidden" name="total_h_players" value="<?php echo $i;?>" />
			<input type="hidden" name="tehp" value="<?php echo $tehp;?>" />
		</tbody>
	</table>
		</fieldset>
