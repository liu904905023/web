<?php
return array(
	   "app_begin" => array("Home\\Behavior\\TestBehavior"),
	   "action_begin" => array("Home\\Behavior\\TestBehavior"),
//	   "action_end" => array("Home\\Behavior\\TestBehavior"),
	   "app_end" => array("Home\\Behavior\\TestBehavior"),
	   'view_filter' => array('Behavior\TokenBuildBehavior')
  
);