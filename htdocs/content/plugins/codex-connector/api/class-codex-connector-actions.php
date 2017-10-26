<?php

/**
 * actions Logic
 *
 * @link       http://codex.management
 * @since      1.0.0
 *
 * @package    Codex_Connector
 * @subpackage Codex_Connector/api
 */

/**
 *
 *
 * This class defines all code necessary to run the actions.
 *
 * @since      1.0.0
 * @package    Codex_Connector
 * @subpackage Codex_Connector/api
 * @author     Daan Rijpkema <info@codex.management>
 */

/**
 * A test function to see if it works
 * @param  string $arg1 [description]
 * @return [type]       [description]
 */
function codex_connector_do_codex_test($arg1="") {
	
	// index2 is behind a login
	$response = Codex_Connector_API::api_call("get","index2",true);
	if(isset($response->status) && $response->status===false) {
		return codex_connector_render_error($response);
	}
	return ("Hello World, this is Codex :D");
}


function codex_connector_action_groups_filter($field,$value)
{
	echo codex_connector_groups_filter($field,$value);
}
function codex_connector_shortcode_groups_filter($atts)
{
	$a = shortcode_atts( array(
		'field' => '',
		'value' => '',
		), $atts );
	return codex_connector_groups_filter($a['field'],$a['value']);
}
/**
 * get a set of groups based on a filter
 * @param  [type] $field [description]
 * @param  [type] $value [description]
 * @return [type]        [description]
 */
function codex_connector_groups_filter($field,$value)
{	
	$response = Codex_Connector_API::api_call(
		"get",
		"groups/filter/{$field}/{$value}"
		);	
	if(isset($response->status) && $response->status===false) {
		return codex_connector_render_error($response);
	}
	return codex_connector_render_groups($response);
}
function codex_connector_shortcode_groups()
{
	return codex_connector_groups();
}
function codex_connector_action_groups()
{
	echo codex_connector_groups();
}

/**
 * get all groups
 * @return [type] [description]
 */
function codex_connector_groups()
{
	$response = Codex_Connector_API::api_call("get","groups");
	if(isset($response->status) && $response->status===false) {
		return codex_connector_render_error($response);
	}
	return codex_connector_render_groups($response);
}

function codex_connector_action_group($id)
{
	echo codex_connector_group($id);	
}
function codex_connector_shortcode_group($atts)
{
	// default values
	$a = shortcode_atts( array(
		'id' => '',
		), $atts );
	return codex_connector_group($a['id']);
}
/** 
 * get a single group
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function codex_connector_group($id)
{
	$response = Codex_Connector_API::api_call("get","groups/get/{$id}");	
	if(isset($response->status) && $response->status===false) {
		return codex_connector_render_error($response);
	}
	return codex_connector_render_groups([$response]);
}

function codex_connector_shortcode_group_subscriptionform($atts)
{
	$a = shortcode_atts( array(
		'id' => '',
		), $atts );
	return codex_connector_group_subscriptionform($a['id']);
}

function codex_connector_action_group_subscriptionform($id="")
{	
	echo codex_connector_group_subscriptionform($id);
}


/** 
 * get a single group
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function codex_connector_group_subscriptionform($id="")
{
	$gm_fields = Codex_Connector_API::api_call("get","groups/subscribable_members_fields");	
	if(isset($gm_fields->status) && $gm_fields->status===false) {
		return codex_connector_render_error($gm_fields);
	}
	

	$group = Codex_Connector_API::api_call("get","groups/get/{$id}");	
	if(isset($group->status) && $group->status===false) {
		return codex_connector_render_error($group);
	}
	if($group!==false && $gm_fields!==false && count($gm_fields)>0) {

		$result = "<p>Je schrijft je in voor:</p>";
		$result.= codex_connector_render_groups([$group]);
		$result.= "<br>";
		$result.=codex_connector_render_groups_subscription_form($gm_fields);
		return $result;
	} else {
		// do nothing
	}
}








// render functions, to be moved
function codex_connector_render_error($response) {
	if(isset($response->error)) 
	{
		return "<div class='codex-error'><strong>ERROR</strong>: ".($response->error)."</div>";
	}
}

function codex_connector_render_groups($groups)
{
	$result = "";
	if(!isset($groups) || $groups===false || count($groups)==0) {
		return "Geen groepen gevonden"; // todo: translation support
	}
	$result.= "<ul>";
	foreach ($groups as $group) {
		$result.=codex_connector_render_group($group);
	}
	$result.= "</ul>";
	return $result;
}

function codex_connector_render_group($group)
{
	$result.= "<li>{$group->name} </li>";
	return $result;
}



function codex_connector_render_groups_subscription_form($fields=[])
{
	$result='
	<form method="post">';
		foreach ($fields as $f) {
			$result.='
			<div class="form-group">
				<label for="'.$f->name.'">'.$f->displayName.'</label>
				<input type="text" name="'.$f->name.'" id="'.$f->name.'" required/>
			</div>';
		} 
		$result .='
		<input type="submit" name="Verzenden">
	</form>
	<script type="text/javascript">
	</script>';
	return $result;
}





// members
function codex_connector_shortcode_members()
{
	return (codex_connector_members());
}
function codex_connector_action_members()
{
	echo(codex_connector_members());
}

/**
 * get all members
 * @return [type] [description]
 */
function codex_connector_members()
{
	$members = codex_connector_items("members");
	return codex_connector_render_members($members);
}
function codex_connector_render_members($members)
{
	if(is_null($members))
	{
		return "";
	}
	$result = "<ul>";
	
	foreach ($members as $member) {
		if(!isset($member->name)) {
			continue;
		}
		$result.="<li>#{$member->id}: {$member->name}</li>";
	}
	$result.="</ul>";
	return $result;
}

function codex_connector_action_member($id)
{
	echo codex_connector_member($id);	
}
function codex_connector_shortcode_member($atts)
{
	// default values
	$a = shortcode_atts( 
		[
		'id' => '',
		],
		$atts
	);
	return codex_connector_member($a['id']);
}
/** 
 * get a single member
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function codex_connector_member($id)
{
	$member = codex_connector_item("members",$id);	
	return codex_connector_render_members([$member]);
}


function codex_connector_action_members_filter($field,$value)
{
	echo codex_connector_members_filter($field,$value);
}
function codex_connector_shortcode_members_filter($atts)
{
	$a = shortcode_atts( array(
		'field' => '',
		'value' => '',
		), $atts );
	return codex_connector_members_filter($a['field'],$a['value']);
}
/**
 * get a set of members based on a filter
 * @param  [type] $field [description]
 * @param  [type] $value [description]
 * @return [type]        [description]
 */
function codex_connector_members_filter($field,$value)
{	
	$members = Codex_Connector_API::api_call(
		"get",
		"members/filter/{$field}/{$value}"
		);	
	
	return codex_connector_render_members($groups);
}


// ------------------ MAMBO -------------------


function codex_connector_shortcode_mambo()
{
	// default values
	$a = shortcode_atts( array(
		'id' => '',
		), $atts );
	
	if(isset($a['id']) && $a['id']!=="") {
		return (codex_connector_mambo_single($a['id']));
	}
	
	return (codex_connector_mambo());
}
function codex_connector_action_mambo()
{
	echo(codex_connector_mambo());
}

/**
 * get all mambo
 * @return [type] [description]
 */
function codex_connector_mambo()
{
	$mambo = codex_connector_items("mambo");
	return codex_connector_render_mambo($mambo);
}
function codex_connector_render_mambo($mambo)
{
	if(is_null($mambo))
	{
		return "";
	}
	$result = "<ul>";
	
	foreach ($mambo as $member) {
		if(!isset($member->name)) {
			continue;
		}
		$result.="<li>#{$member->id}: {$member->name}</li>";
	}
	$result.="</ul>";
	return $result;
}


function codex_connector_mambo_single($id)
{
	$member = codex_connector_item("mambo",$id);	
	return codex_connector_render_mambo([$member]);
}


function codex_connector_action_mambo_filter($field,$value)
{
	echo codex_connector_mambo_filter($field,$value);
}
function codex_connector_shortcode_mambo_filter($atts)
{
	$a = shortcode_atts( array(
		'field' => '',
		'value' => '',
		), $atts );
	return codex_connector_mambo_filter($a['field'],$a['value']);
}

function codex_connector_mambo_filter($field,$value)
{	
	$mambo = codex_connector_filter($mambo,$field,$value);
	
	return codex_connector_render_mambo($mambo);
}




function codex_connector_filter($internal_name,$field,$value)
{
	$items = Codex_Connector_API::api_call(
		"get",
		"{$internal_name}/filter/{$field}/{$value}"
		);	
	return $items;
}

function codex_connector_items($internal_name)
{
	$item = Codex_Connector_API::api_call(
		"get",
		"{$internal_name}"
	);	
	return $item;
}

function codex_connector_item($internal_name,$id)
{
	$item = Codex_Connector_API::api_call(
		"get",
		"{$internal_name}/get/{$id}"
	);	
	return $item;
}



/**
 * Get the Codex User Id set in session by the third-party implementation
 * @return int $codex_user_id
 */
function codex_connector_codex_user_id()
{
	if(isset($_SESSION['codex_user_id']))
	{
    	return ($_SESSION['codex_user_id']);
	}
	return null;
}


/**
 * Send an update request for a Mambo Item (a member)
 * @param  int $item_id          ID of the mambo item
 * @param  array  $update_data      PHP array of updated fields as keyvalue pairs (key = internal name in Codex)
 * @return $response or false                   Returns response object or false if it doesn't work
 */
function codex_connector_mambo_send_update_request($item_id,$update_data=[])
{
	$request_data = [
		"item_id" => $item_id,
		"data"=> json_encode($update_data)
	];
	
	Codex_Connector_API::initiate(
		get_option( 'codex_connector_server' ),
		get_option( 'codex_connector_username' ),
		get_option( 'codex_connector_password' )
	);

	try {
		
		$result = Codex_Connector_API::api_call("post","mambo/send_update_request/",true,$request_data);
		
	} catch (Exception $e) {
		return ['status'=>false,'error'=>$e->getMessage()];	
	}
	return $result;
}