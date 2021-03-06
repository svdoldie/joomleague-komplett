<?php
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'pagination.php');

jimport('joomla.application.component.view');

require_once (JLG_PATH_ADMIN .DS.'models'.DS.'divisions.php');

class JoomleagueViewRanking extends JLGView {
	
	function display($tpl = null) 
	{
		// Get a refrence of the page instance in joomla
		$document = & JFactory :: getDocument();
		$uri = & JFactory :: getURI();

		$model = & $this->getModel();
		$config = $model->getTemplateConfig($this->getName());
		$project = $model->getProject();
		$rounds = JoomleagueHelper::getRoundsOptions($project->id);
			
		$model->setProjectId($project->id);
		
		$this->assignRef('project', $project);
		$this->assignRef('overallconfig', $model->getOverallConfig());
		$this->assignRef('tableconfig', $config);
		$this->assignRef('config', $config);
    $this->assignRef( 'mapconfig',		$model->getMapConfig() ); // Loads the project-template -settings for the GoogleMap
    
		$model->computeRanking();

    $paramsdata	= $this->project->extended;
		$paramsdefs	= JLG_PATH_ADMIN . DS . 'assets' . DS . 'extended' . DS . 'project.xml';
		$extended	= new JLGExtraParams( $paramsdata, $paramsdefs );

    if ( ($this->overallconfig['show_project_rssfeed']) == 1 )
	  {
    $mod_name               = "mod_jw_srfr"; 
    $this->params =	$extended;
    $rssfeedlink = $this->params->get('project_league_rss_feed_link');
    
    //echo 'rssfeed<br><pre>'.print_r($rssfeedlink,true).'</pre><br>';
    
    if ( $rssfeedlink )
    {
    $srfrFeedsArray 							= explode("\n",$rssfeedlink);
    $perFeedItems 								= $this->overallconfig['perFeedItems'];
    $totalFeedItems 							= $this->overallconfig['totalFeedItems'];
    $feedTimeout									= $this->overallconfig['feedTimeout'];
    $this->assignRef( 'feedTitle' , $this->overallconfig['feedTitle'] );
    $this->assignRef( 'feedFavicon' , $this->overallconfig['feedFavicon'] );
    $this->assignRef( 'feedItemTitle' , $this->overallconfig['feedItemTitle'] );
    $this->assignRef( 'feedItemDate' , $this->overallconfig['feedItemDate'] );
    $feedItemDateFormat						= $this->overallconfig['feedItemDateFormat'];
    $this->assignRef( 'feedItemDescription' , $this->overallconfig['feedItemDescription'] );
    $feedItemDescriptionWordlimit	= $this->overallconfig['feedItemDescriptionWordlimit'];
    $feedItemImageHandling				= $this->overallconfig['feedItemImageHandling'];
    $feedItemImageResizeWidth			= $this->overallconfig['feedItemImageResizeWidth'];
    $feedItemImageResampleQuality	= $this->overallconfig['feedItemImageResampleQuality'];
    $this->assignRef( 'feedItemReadMore' , $this->overallconfig['feedItemReadMore'] );
    
    $this->assignRef( 'feedsBlockPreText' ,	$this->overallconfig['feedsBlockPreText'] );
    $this->assignRef( 'feedsBlockPostText' , $this->overallconfig['feedsBlockPostText'] );
    $this->assignRef( 'feedsBlockPostLink' , $this->overallconfig['feedsBlockPostLink'] );
    $feedsBlockPostLinkURL				= $this->overallconfig['feedsBlockPostLinkURL'];
    $feedsBlockPostLinkTitle			= $this->overallconfig['feedsBlockPostLinkTitle'];
    $srfrCacheTime								= $this->overallconfig['srfrCacheTime'];
    $cacheLocation								= 'cache'.DS.$mod_name;
    $this->assignRef( 'rssfeedoutput',SimpleRssFeedReaderHelper::getFeeds($srfrFeedsArray,$totalFeedItems,$perFeedItems,$feedTimeout,$feedItemDateFormat,$feedItemDescriptionWordlimit,$cacheLocation,$srfrCacheTime,$feedItemImageHandling,$feedItemImageResizeWidth,$feedItemImageResampleQuality,$this->feedFavicon) );
    $css = 'components/com_joomleague/assets/css/rssfeedstyle.css';
		$document->addStyleSheet($css);
    }
     
    }


		$this->assignRef('round',     $model->round);
		$this->assignRef('part',      $model->part);
		$this->assignRef('rounds',    $rounds);
		$this->assignRef('divisions', $model->getDivisions());
		$this->assignRef('type',      $model->type);
		$this->assignRef('from',      $model->from);
		$this->assignRef('to',        $model->to);
		$this->assignRef('divLevel',  $model->divLevel);
		$this->assignRef('currentRanking',  $model->currentRanking);
		$this->assignRef('previousRanking', $model->previousRanking);
		$this->assignRef('homeRank',      $model->homeRank);
		$this->assignRef('awayRank',      $model->awayRank);
		$this->assignRef('current_round', $model->current_round);
		$this->assignRef('teams',			    $model->getTeamsIndexedByPtid());
		$this->assignRef('previousgames', $model->getPreviousGames());
		$this->assign('action', $uri->toString());

		$frommatchday[] = JHTML :: _('select.option', '0', JText :: _('JL_RANKING_FROM_MATCHDAY'));
		$frommatchday = array_merge($frommatchday, $rounds);
		$lists['frommatchday'] = $frommatchday;
		$tomatchday[] = JHTML :: _('select.option', '0', JText :: _('JL_RANKING_TO_MATCHDAY'));
		$tomatchday = array_merge($tomatchday, $rounds);
		$lists['tomatchday'] = $tomatchday;

		$opp_arr = array ();
		$opp_arr[] = JHTML :: _('select.option', "0", JText :: _('JL_RANKING_FULL_RANKING'));
		$opp_arr[] = JHTML :: _('select.option', "1", JText :: _('JL_RANKING_HOME_RANKING'));
		$opp_arr[] = JHTML :: _('select.option', "2", JText :: _('JL_RANKING_AWAY_RANKING'));

		$lists['type'] = $opp_arr;
		$this->assignRef('lists', $lists);

		if (!isset ($config['colors'])) {
			$config['colors'] = "";
		}

		$this->assignRef('colors', $model->getColors($config['colors']));
		//$this->assignRef('result', $model->getTeamInfo());
		//		$this->assignRef( 'pageNav', $model->pagenav( "ranking", count( $rounds ), $sr->to ) );
		//		$this->assignRef( 'pageNav2', $model->pagenav2( "ranking", count( $rounds ), $sr->to ) );

		// Set page title
		$pageTitle = JText::_( 'JL_RANKING_PAGE_TITLE' );
		if ( isset( $this->project->name ) )
		{
			$pageTitle .= ': ' . $this->project->name;
		}
		$document->setTitle( $pageTitle );
		
		//$model = $this->getModel( "teams" );
		$mdlTeams = JModel::getInstance("Teams", "JoomleagueModel");
		$this->assignRef( 'allteams', $mdlTeams->getTeams() );
		
		if (($this->config['show_maps'])==1)
	  {
	  $this->map = new simpleGMapAPI();
  $this->geo = new simpleGMapGeocoder();
  $this->map->setWidth($this->mapconfig['width']);
  $this->map->setHeight($this->mapconfig['height']);
  $this->map->setZoomLevel($this->mapconfig['ranking_map_zoom']); 
  $this->map->setMapType($this->mapconfig['default_map_type']);
  $this->map->setBackgroundColor('#d0d0d0');
  $this->map->setMapDraggable(true);
  $this->map->setDoubleclickZoom(false);
  $this->map->setScrollwheelZoom(true);
  $this->map->showDefaultUI(false);
  $this->map->showMapTypeControl(true, 'DROPDOWN_MENU');
  $this->map->showNavigationControl(true, 'DEFAULT');
  $this->map->showScaleControl(true);
  $this->map->showStreetViewControl(true);
  $this->map->setInfoWindowBehaviour('SINGLE_CLOSE_ON_MAPCLICK');
  $this->map->setInfoWindowTrigger('CLICK');
  
  //echo 'allteams <br><pre>'.print_r($this->allteams,true).'</pre><br>';
  
  foreach ( $this->allteams as $row )
    {
    $address_parts = array();
		if (!empty($row->club_address))
		{
			$address_parts[] = $row->club_address;
		}
		if (!empty($row->club_state))
		{
			$address_parts[] = $row->club_state;
		}
		if (!empty($row->club_location))
		{
			if (!empty($row->club_zipcode))
			{
				$address_parts[] = $row->club_zipcode. ' ' .$row->club_location;
			}
			else
			{
				$address_parts[] = $row->club_location;
			}
		}
		if (!empty($row->club_country))
		{
			$address_parts[] = Countries::getShortCountryName($row->club_country);
		}
		$row->address_string = implode(', ', $address_parts);
    $this->map->addMarkerByAddress($row->address_string, $row->team_name, '"<a href="'.$row->club_www.'" target="_blank">'.$row->club_www.'</a>"', "http://maps.google.com/mapfiles/kml/pal2/icon49.png");		
    }
    
  
  $document->addScript($this->map->JLprintGMapsJS());
  $document->addScriptDeclaration($this->map->JLshowMap(false));
  
	}
	
		
		
		
		
		parent :: display($tpl);
	}
		
}
?>