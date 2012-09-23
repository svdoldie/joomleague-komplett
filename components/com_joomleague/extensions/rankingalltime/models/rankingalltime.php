<?php defined( '_JEXEC' ) or die( 'Restricted access' );

/* JoomLeague League Management and Prediction Game for Joomla!
 * Copyright (C) 2007  Robert Moss
 *
 * Homepage: http://www.JoomLeague.net
 * Support: htt://www.JoomLeague.net/forum/
 *
 * This file is part of JoomLeague.
 *
 * JoomLeague is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * Please note that the GPL states that any headers in files and
 * Copyright notices as well as credits in headers, source files
 * and output (screens, prints, etc.) can not be removed.
 * You can extend them with your own credits, though...
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
*/

jimport( 'joomla.application.component.model' );

require_once( JLG_PATH_SITE . DS . 'models' . DS . 'project.php' );

$maxImportTime=480;

if ((int)ini_get('max_execution_time') < $maxImportTime){@set_time_limit($maxImportTime);}

class JoomleagueModelRankingalltime extends JoomleagueModelProject
{

var $teams = array();
var $_teams = array();
var $alltimepoints = '';

function getAllTimePoints()
  {
    $mainframe =& JFactory::getApplication();
  	$this->alltimepoints = JRequest::getVar( "points", 0 );
  	
  	if ( !$this->alltimepoints )
  	{
    // retrieve the value of the state variable. If no value is specified,
    // the specified default value will be returned.
    // function syntax is getUserState( $key, $default );
    $this->alltimepoints = $mainframe->getUserState( "com_joomleague.alltimetablepoints", '3,1,0' );
    }
    else
    {
    // store the variable that we would like to keep for next time
    // function syntax is setUserState( $key, $value );
    $mainframe->setUserState( "com_joomleague.alltimetablepoints", $this->alltimepoints );
    }
  	
    return $this->alltimepoints;
  }
  
function getAllProject()
  {
  $league = JRequest::getInt( "l", 0 );
  
  if (!$league)
  {
  $projekt = JRequest::getInt( "p", 0 );
  $query = 'select league_id 
  from #__joomleague_project
  where id = '.$projekt.' order by name ';
  $this->_db->setQuery($query);
	$league = $this->_db->loadResult();
	
  }
  
  $query = 'select id 
  from #__joomleague_project
  where league_id = '.$league.' order by name ';
  $this->_db->setQuery($query);
	//$result = $this->_db->loadObjectList();
	$result = $this->_db->loadResultArray();
	$this->project_ids = implode (",", $result);	
	$this->project_ids_array = $result;
  return $result;
    
  }

/*
	* get all teams from all projects
	*/
	function getAllTeams($project_ids)
	{
	
	$query = ' SELECT	tl.id AS projectteamid,	tl.division_id, '
			       . ' tl.standard_playground,	tl.admin,	tl.start_points, '
			       . 'tl.points_finally,tl.neg_points_finally,tl.matches_finally,tl.won_finally,tl.draws_finally,tl.lost_finally,tl.homegoals_finally,tl.guestgoals_finally,tl.diffgoals_finally,'
			       . ' tl.info,	tl.team_id,	tl.checked_out,	tl.checked_out_time, '
			       . ' tl.picture, tl.project_id, '
			       . ' t.id, t.name, t.short_name, t.middle_name,	t.notes, t.club_id, '
			       . ' u.username, u.email, '
			       . ' c.email as club_email, c.logo_small,	c.country, c.website, '
			       . ' d.name AS division_name,	d.shortname AS division_shortname, d.parent_id AS parent_division_id, '
			       . ' plg.name AS playground_name,	plg.short_name AS playground_short_name, '
				     . ' CASE WHEN CHAR_LENGTH( p.alias ) THEN CONCAT_WS( \':\', p.id, p.alias ) ELSE p.id END AS project_slug, '
				     . ' CASE WHEN CHAR_LENGTH( t.alias ) THEN CONCAT_WS( \':\', t.id, t.alias ) ELSE t.id END AS team_slug, '
				     . ' CASE WHEN CHAR_LENGTH( d.alias ) THEN CONCAT_WS( \':\', d.id, d.alias ) ELSE d.id END AS division_slug, '
				     . ' CASE WHEN CHAR_LENGTH( c.alias ) THEN CONCAT_WS( \':\', c.id, c.alias ) ELSE c.id END AS club_slug '
			       . ' FROM #__joomleague_project_team tl '
			       . ' LEFT JOIN #__joomleague_team t ON tl.team_id = t.id '
			       . ' LEFT JOIN #__users u ON tl.admin = u.id '
			       . ' LEFT JOIN #__joomleague_club c ON t.club_id = c.id '
			       . ' LEFT JOIN #__joomleague_division d ON d.id = tl.division_id '
			       . ' LEFT JOIN #__joomleague_playground plg ON plg.id = tl.standard_playground '
			       . ' LEFT JOIN #__joomleague_project AS p ON p.id = tl.project_id '
			       . ' WHERE tl.project_id IN ('.$project_ids.') group by tl.team_id'  ;

			$this->_db->setQuery($query);
			$this->_teams = $this->_db->loadObjectList();

if ( $this->debug_info )
{
/*
$this->pane =& JPane::getInstance('sliders');
echo $this->pane->startPane('pane');    
echo $this->pane->startPanel('modelsfunctiongetAllTeams','modelsfunctiongetAllTeams');      
$this->dump_header("models function getAllTeams");
$this->dump_variable("this->_teams", $this->_teams);
echo $this->pane->endPanel();
echo $this->pane->endPane();   
*/ 
}
		
		
		return $this->_teams;
  
  }
    
function getAllTeamsIndexedByPtid($project_ids)
	{
		$result = $this->getAllTeams($project_ids);
		//$teams = array();

/*
tl.points_finally
tl.neg_points_finally
tl.matches_finally
tl.won_finally
tl.draws_finally
tl.lost_finally
tl.homegoals_finally
tl.guestgoals_finally
tl.diffgoals_finally
*/

		if (count($result))
		{
			foreach($result as $r)
			{
				$this->teams[$r->team_id] = $r;
				$this->teams[$r->team_id]->cnt_matches = 0; 
        $this->teams[$r->team_id]->sum_points = $r->points_finally;
        $this->teams[$r->team_id]->neg_points = $r->neg_points_finally;
   
        $this->teams[$r->team_id]->cnt_won_home = 0;
        $this->teams[$r->team_id]->cnt_draw_home = 0;
        $this->teams[$r->team_id]->cnt_lost_home = 0;
   
        $this->teams[$r->team_id]->cnt_won = 0;
        $this->teams[$r->team_id]->cnt_draw = 0;
        $this->teams[$r->team_id]->cnt_lost = 0;
   
        $this->teams[$r->team_id]->sum_team1_result = 0;
        $this->teams[$r->team_id]->sum_team2_result = 0;
        $this->teams[$r->team_id]->sum_away_for = 0;
        $this->teams[$r->team_id]->diff_team_results = 0;
        
        $this->teams[$r->team_id]->round = 0;
        $this->teams[$r->team_id]->rank = 0;
        
			}
		}

/*		
echo 'models function getAllTeamsIndexedByPtid this->teams<pre>';
print_r($this->teams);
echo '</pre>';
*/
		
		return $this->teams;
	}
  
    
function getLeagueSeasons()
{
// league_id holen
$this->projectid = JRequest::getInt('p',0);
$league_id = JRequest::getInt('l',0);

/*
$query="SELECT pro.league_id 
from #__joomleague_project as pro
WHERE pro.id = ".$this->projectid;
$this->_db->setQuery($query);
$league_id = $this->_db->loadResult();
*/

//echo 'league_id -> '.$league_id.'<br>';
$query="SELECT pro.id,pro.name,s.name as seasonname,l.name as leaguename 
from #__joomleague_project as pro
inner join #__joomleague_season as s
on s.id = pro.season_id
inner join #__joomleague_league as l
on l.id = pro.league_id  
WHERE pro.published = 1 
and pro.league_id=".$league_id." order by s.name desc, pro.name asc";
$this->_db->setQuery($query);
$allseasons = $this->_db->loadObjectList();

// echo '<pre>';				
// print_r($allseasons);
// echo '</pre><br>';	
			
return $allseasons;				
}

function getLeagueName()
{
$this->projectid = JRequest::getInt('p',0);

$query="SELECT l.name 
from #__joomleague_project as pro
inner join #__joomleague_league as l
on l.id = pro.league_id 
WHERE pro.id = ".$this->projectid;
$this->_db->setQuery($query);
$league_name = $this->_db->loadResult();

return $league_name;

}

function prepareRankingAllTime($ranking)
  {
  
/*
echo 'models function prepareRankingAllTime this->alltimepoints<pre>';
print_r($this->alltimepoints);
echo '</pre>';  
*/

$arr = explode(",",$this->alltimepoints);
$win_points  = (isset($arr[0])) ? $arr[0] : 0;
$draw_points = (isset($arr[1])) ? $arr[1] : 0;
$loss_points = (isset($arr[2])) ? $arr[2] : 0;
			
			
/*
echo 'models function prepareRankingAllTime ranking<pre>';
print_r($ranking);
echo '</pre>';  
*/

foreach ( $ranking[0]  as $value )
{
$team_id = $value->_teamid;

// echo 'team_id '.$team_id.'<br>';
// echo 'this->teams[]->cnt_matches '.$this->teams[$team_id]->cnt_matches.'<br>';
// echo 'value->cnt_matches '.$value->cnt_matches.'<br>';

/*
tl.points_finally
tl.neg_points_finally
tl.matches_finally
tl.won_finally
tl.draws_finally
tl.lost_finally
tl.homegoals_finally
tl.guestgoals_finally
tl.diffgoals_finally
*/

$this->teams[$team_id]->cnt_matches += $value->cnt_matches;

if ( !$value->cnt_won && !$value->cnt_draw && !$value->cnt_lost )
{
if ( $win_points == 3 )
{
$this->teams[$team_id]->sum_points += ( $value->cnt_won * $win_points ) + ( $value->cnt_draw * $draw_points );
$this->teams[$team_id]->neg_points += 0;
}

if ( $win_points == 2 )
{
$this->teams[$team_id]->sum_points += ( $value->cnt_won * $win_points ) + ( $value->cnt_draw * $draw_points );
$this->teams[$team_id]->neg_points += ( $value->cnt_lost * $win_points ) + ( $value->cnt_draw * $draw_points );
}

}
else
{
$this->teams[$team_id]->sum_points += $value->sum_points;
$this->teams[$team_id]->neg_points += $value->neg_points;
}

$this->teams[$team_id]->cnt_won_home += $value->cnt_won_home;
$this->teams[$team_id]->cnt_draw_home += $value->cnt_draw_home;
$this->teams[$team_id]->cnt_lost_home += $value->cnt_lost_home;

$this->teams[$team_id]->cnt_won += $value->cnt_won;
$this->teams[$team_id]->cnt_draw += $value->cnt_draw;
$this->teams[$team_id]->cnt_lost += $value->cnt_lost;

$this->teams[$team_id]->sum_team1_result += $value->sum_team1_result;
$this->teams[$team_id]->sum_team2_result += $value->sum_team2_result;
$this->teams[$team_id]->sum_away_for += $value->sum_away_for;
$this->teams[$team_id]->diff_team_results += $value->diff_team_results;


}

  
  }
  
function getCurrentRanking()
{
$newranking = array();

/*
echo 'models function getCurrentRanking this->teams<pre>';
print_r($this->teams);
echo '</pre>';
*/

foreach ( $this->teams as $key )
{
$new = new JLGRankingalltimeTeam(0);
   $new->cnt_matches = $key->cnt_matches; 
   $new->sum_points = $key->sum_points;
   $new->neg_points = $key->neg_points;
   
   $new->cnt_won_home = $key->cnt_won_home;
   $new->cnt_draw_home = $key->cnt_draw_home;
   $new->cnt_lost_home = $key->cnt_lost_home;
   
   $new->cnt_won = $key->cnt_won;
   $new->cnt_draw = $key->cnt_draw;
   $new->cnt_lost = $key->cnt_lost;
   
   $new->sum_team1_result = $key->sum_team1_result;
   $new->sum_team2_result = $key->sum_team2_result;
   $new->sum_away_for = $key->sum_away_for;
   $new->diff_team_results = $key->diff_team_results;
   
   
   $new->_is_in_score = $key->_is_in_score;
   $new->_teamid = $key->team_id;
   $new->_name = $key->name;
   
   $new->_ptid = $key->projectteamid;
   $new->_pid = $key->project_id;

   $newranking[0][$key->team_id] = $new;

}

$this->_sortRanking($newranking[0]);

$oldpoints = 0;
  $rank = 0;
  foreach ($newranking[0] as $teamid => $row)
   {
   
//    echo 'rank -> '.$rank.'oldpoints -> '.$oldpoints.' teampoints -> '.$row->sum_points.'<br>';
   if ( $oldpoints == $row->sum_points )
   {
   $row->rank = $rank;
   $oldpoints = $row->sum_points;
   }
   else
   {
   $rank++;
   $row->rank = $rank;
   $oldpoints = $row->sum_points;
   
   }
   
   
   
   }

/*   
echo 'models function getCurrentRanking newranking<pre>';
print_r($newranking);
echo '</pre>';
*/
	
// return $this->teams;
return $newranking;

}

function playedCmp( &$a, &$b){
	  $res = $a->cnt_matches - $b->cnt_matches;
	  return $res;
	}	

function teamNameCmp( &$a, &$b){
	  return strcasecmp ($a->_name, $b->_name);
	}

	function wonCmp( &$a, &$b){
	  $res = $a->cnt_won - $b->cnt_won;
	  return $res;
	}

	function drawCmp( &$a, &$b){
	  $res = ($a->cnt_draw - $b->cnt_draw);
	  return $res;
	}

	function lossCmp( &$a, &$b){
	  $res = ($a->cnt_lost - $b->cnt_lost);
	  return $res;
	}
	
	function winpctCmp( &$a, &$b){
	  $pct_a = $a->cnt_won/($a->cnt_won+$a->cnt_lost+$a->cnt_draw);
	  $pct_b = $b->cnt_won/($b->cnt_won+$b->cnt_lost+$b->cnt_draw);
	  $res =($pct_a < $pct_b);
	  return $res;
	}

	function quotCmp( &$a, &$b){
	  $pct_a = $a->cnt_won/($a->cnt_won+$a->cnt_lost+$a->cnt_draw);
	  $pct_b = $b->cnt_won/($b->cnt_won+$b->cnt_lost+$b->cnt_draw);
	  $res =($pct_a < $pct_b);
	  return $res;
	}

	function goalspCmp( &$a, &$b){
	  $res = ($a->sum_team1_result - $b->sum_team1_result);
	  return $res;
	}

	function goalsforCmp( &$a, &$b){
	  $res = ($a->sum_team1_result - $b->sum_team1_result);
	  return $res;
	}

	function goalsagainstCmp( &$a, &$b){
	  $res = ($a->sum_team2_result - $b->sum_team2_result);
	  return $res;
	}	
	
	function legsdiffCmp( &$a, &$b){
	  $res = ($a->diff_team_legs - $b->diff_team_legs);
	  return $res;
	}

	function legsratioCmp( &$a, &$b){
	  $res = ($a->legsRatio - $b->legsRatio);
	  return $res;
	}
	
	function diffCmp( &$a, &$b){
	  $res = ($a->diff_team_results - $b->diff_team_results);
	  return $res;
	}

	function pointsCmp( &$a, &$b){
	  $res = ($a->getPoints() - $b->getPoints());
	  return $res;
	}
		
	function startCmp( &$a, &$b){
	  $res = ($a->team->start_points * $b->team->start_points);
	  return $res;
	}
	
	function bonusCmp( &$a, &$b){
	  $res = ($a->bonus_points - $b->bonus_points);
	  return $res;
	}

	function negpointsCmp( &$a, &$b){
	  $res = ($a->neg_points - $b->neg_points);
	  return $res;
	}	

	function pointsratioCmp( &$a, &$b){
	  $res = ($a->pointsRatio - $b->pointsRatio);
	  return $res;
	}
  	
function _sortRanking(&$ranking)
	{
	
// 	echo '_sortRanking vor der sortierung ranking <pre>';
// 	print_r($ranking);
// 	echo '</pre><br>';
	
		$order     = JRequest::getVar( 'order', '' );
		$order_dir = JRequest::getVar( 'dir', 'DESC' );

    if ( !$order )
    {
    $order = 'points';
    }
//     if ( !$order_dir)
//     {
//     $order_dir = 'DESC';
//     }
		switch ($order)
		{
			case 'played':
			uasort( $ranking, array("JoomleagueModelRankingalltime","playedCmp" ));
			break;				
			case 'name':
			uasort( $ranking, array("JoomleagueModelRankingalltime","teamNameCmp" ));
			break;
			case 'rank':
			break;
			case 'won':
			uasort( $ranking, array("JoomleagueModelRankingalltime","wonCmp" ));
			break;
			case 'draw':
			uasort( $ranking, array("JoomleagueModelRankingalltime","drawCmp" ));
			break;
			case 'loss':
			uasort( $ranking, array("JoomleagueModelRankingalltime","lossCmp" ));
			break;
			case 'winpct':
			uasort( $ranking, array("JoomleagueModelRankingalltime","winpctCmp" ));
			break;
			case 'quot':
			uasort( $ranking, array("JoomleagueModelRankingalltime","quotCmp" ));
			break;
			case 'goalsp':
			uasort( $ranking, array("JoomleagueModelRankingalltime","goalspCmp" ));
			break;
			case 'goalsfor':
			uasort( $ranking, array("JoomleagueModelRankingalltime","goalsforCmp" ));
			break;
			case 'goalsagainst':
			uasort( $ranking, array("JoomleagueModelRankingalltime","goalsagainstCmp" ));
			break;
			case 'legsdiff':
			uasort( $ranking, array("JoomleagueModelRankingalltime","legsdiffCmp" ));
			break;
			case 'legsratio':
			uasort( $ranking, array("JoomleagueModelRankingalltime","legsratioCmp" ));
			break;				
			case 'diff':
			uasort( $ranking, array("JoomleagueModelRankingalltime","diffCmp" ));
			break;
			case 'points':
			uasort( $ranking, array("JoomleagueModelRankingalltime","pointsCmp" ));
			break;
			case 'start':
			uasort( $ranking, array("JoomleagueModelRankingalltime","startCmp" ));
			break;
			case 'bonus':
			uasort( $ranking, array("JoomleagueModelRankingalltime","bonusCmp" ));
			break;
			case 'negpoints':
			uasort( $ranking, array("JoomleagueModelRankingalltime","negpointsCmp" ));
			break;
			case 'pointsratio':
			uasort( $ranking, array("JoomleagueModelRankingalltime","pointsratioCmp" ));
			break;			

			default:
				if (method_exists($this, $order.'Cmp')) {
					uasort( $ranking, array($this, $order.'Cmp'));
				}
				break;
		}
		if ($order_dir == 'DESC')
		{
			$ranking = array_reverse( $ranking, true );
		}
		
// 	echo '_sortRanking nach der sortierung ranking <pre>';
// 	print_r($ranking);
// 	echo '</pre><br>';
  		            
		return true;
	}
	

  

}

/**
 * Ranking team class
 * Support class for ranking helper
 */
class JLGRankingalltimeTeam
{

// new for use_finally
	var $_use_finally = 0;
	var $_points_finally = 0;
	var $_neg_points_finally = 0;   
	var $_matches_finally = 0;
	var $_won_finally = 0;
	var $_draws_finally = 0;
	var $_lost_finally = 0;
	var $_homegoals_finally = 0;
	var $_guestgoals_finally = 0;
	var $_diffgoals_finally = 0;

	// new for is_in_score 
	var $_is_in_score = 0;

	/**
	 * project team id
	 * @var int
	 */
	var $_ptid = 0;
	/**
	 * team id
	 * @var int
	 */
	var $_teamid = 0;
	/**
	 * division id
	 * @var int
	 */
	var $_divisionid = 0;
	/**
	 * start point / penalty
	 * @var int
	 */
	var $_startpoints = 0;
	/**
	 * team name
	 * @var string
	 */
	var $_name = null;

	var $cnt_matches   = 0;
	var $cnt_won       = 0;
	var $cnt_draw      = 0;
	var $cnt_lost      = 0;
	var $cnt_won_home  = 0;
	var $cnt_draw_home = 0;
	var $cnt_lost_home = 0;
	var $sum_points    = 0;
	var $neg_points    = 0;
	var $bonus_points  = 0;
	var $sum_team1_result = 0;
	var $sum_team2_result = 0;
	var $sum_away_for   = 0;
	var $sum_team1_legs = 0;
	var $sum_team2_legs = 0;
	var $diff_team_results = 0;
	var $diff_team_legs = 0;
	var $round          = 0;
	var $rank           = 0;

/**
	 * contructor requires ptid
	 * @param int $ptid
	 */
	function JLGRankingTeam($ptid)
	{
		$this->setPtid($ptid);
	}

// new for is_in_score
	function setis_in_score($val)
	{
		$this->_is_in_score = (int) $val;
	}
	
// new for use finally
	function setuse_finally($val)
	{
		$this->_use_finally = (int) $val;
	}
	function setpoints_finally($val)
	{
		$this->_points_finally = (int) $val;
	}
	function setneg_points_finally($val)
	{
		$this->_neg_points_finally = (int) $val;
	}
	function setmatches_finally($val)
	{
		$this->_matches_finally = (int) $val;
	}
	function setwon_finally($val)
	{
		$this->_won_finally = (int) $val;
	}
	function setdraws_finally($val)
	{
		$this->_draws_finally = (int) $val;
	}
	function setlost_finally($val)
	{
		$this->_lost_finally = (int) $val;
	}
	function sethomegoals_finally($val)
	{
		$this->_homegoals_finally = (int) $val;
	}
	function setguestgoals_finally($val)
	{
		$this->_guestgoals_finally = (int) $val;
	}
	function setdiffgoals_finally($val)
	{
		$this->_diffgoals_finally = (int) $val;
	}

	/**
	 * set project team id
	 * @param int ptid
	 */
	function setPtid($ptid)
	{
		$this->_ptid = (int) $ptid;
	}

	/**
	 * set team id
	 * @param int id
	 */
	function setTeamid($id)
	{
		$this->_teamid = (int) $id;
	}

	/**
	 * returns project team id
	 * @return int id
	 */
	function getPtid()
	{
		return $this->_ptid;
	}

	/**
	 * returns team id
	 * @return int id
	 */
	function getTeamid()
	{
		return $this->_teamid;
	}

	/**
	 * set team division id
	 * @param int val
	 */
	function setDivisionid($val)
	{
		$this->_divisionid = (int) $val;
	}

	/**
	 * return team division id
	 * @return int id
	 */
	function getDivisionid()
	{
		return $this->_divisionid;
	}

	/**
	 * set team start points
	 * @param int val
	 */
	function setStartpoints($val)
	{
		$this->_startpoints = $val;
	}
	
	/**
	 * set team neg points
	 * @param int val
	 */
	function setNegpoints($val)
	{
		$this->neg_points = $val;
	}
	
	/**
	 * set team name
	 * @param string val
	 */
	function setName($val)
	{
		$this->_name = $val;
	}

	/**
	 * return winning percentage
	 *
	 * @return float
	 */
	function winPct()
	{
		if ( $this->cnt_won + $this->cnt_lost + $this->cnt_draw == 0 )
		{
			return 0;
		}
		else
		{
			return ($this->cnt_won/($this->cnt_won+$this->cnt_lost+$this->cnt_draw))*100;
		}
	}


	/**
	 * return scoring average
	 *
	 * @return float
	 */
	function goalAvg()
	{
		if ($this->sum_team2_result == 0)
		{
			return $this->sum_team1_result/1;
		}
		else
		{
			return $this->sum_team1_result/$this->sum_team2_result;
		}
	}

	/**
	 * return scoring percentage
	 *
	 * @return float
	 */
	function goalPct()
	{
		$result = $this->goalAvg()*100;
		return $result;
	}


	/**
	 * return leg ratio
	 *
	 * @return float
	 */
	function legsRatio()
	{
		if ($this->sum_team2_legs == 0)
		{
			return $this->sum_team1_legs/1;
		}
		else
		{
			return $this->sum_team1_legs/$this->sum_team2_legs;
		}
	}

	/**
	 * return points ratio
	 *
	 * @return float
	 */
	function pointsRatio()
	{
		if ($this->neg_points == 0)
		{
			// we do not include start points
			return $this->getPoints(false)/1;
		}
		else
		{
			// we do not include start points
			return $this->getPoints(false)/$this->neg_points;
		}
	}

	/**
	 * return points quot
	 *
	 * @return float
	 */
	function pointsQuot()
	{
		if ( $this->cnt_matches == 0 )
		{
			// we do not include start points
			return $this->getPoints(false)/1;
		}
		else
		{
			// we do not include start points
			return $this->getPoints(false) / $this->cnt_matches;
		}
	}


	function getName()
	{
		return $this->_name;
	}

	/**
	 * return points total
	 *
	 * @param boolean include start points, default true
	 */
	function getPoints($include_start = true)
	{
		if ($include_start) {
			return $this->sum_points + $this->_startpoints;
		}
		else {
			return $this->sum_points;
		}
	}


	
}
	
?>