<?php
/*
Plugin Name: IP Blocker
Plugin URI: http://www.fabian-jocks.de/wordpress-ip-blocker/
Description: With this plugin you can exclude IPs of the page and control over an interactive administrative interface.
Version: 1.2
Author: Fabian Jocks
Author URI: http://www.fabian-jocks.de/
License: GPL
Stable Tag: 1.2
*/

if(is_admin()) {
	add_action('admin_menu', 'mk_admin');
	
	$default['page'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>You are banned!</title></head><body><div align="center"><div style="width: 500px; padding: 20px; background-color: #f4f4f4; border: 1px solid #CCCCCC; font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 14px; color: #222222;">Sie wurden von dieser Seite ausgeschlossen!<br />You have been excluded from this page.</div></div></body></html>';
	
	function mk_admin(){
		add_menu_page('IP Blocker', 'IP Blocker', 10, 'ip_blocker', 'adm_page');
	}
	
	function adm_page(){ ?>
		<?php
			if($_POST) {
				$post_vars = array(
					'ips' => $_POST['ips'], 
					'page' => stripslashes($_POST['page'])
				);
				update_option('ip-blocker-opts', $post_vars);
				
				if(is_null($page)) $page = $default['page'];
			}
			
			extract(get_option('ip-blocker-opts'));
		?>
	
        <div class="wrap">
        	<script src="http://code.jquery.com/jquery.min.js" type="text/javascript"></script>
            <script type="text/javascript">
				<!--
				function reloadlist() {
					var ips = $('#ips').html();
					ips = ips.split("\n");
					
					for(i=0; i < ips.length; i++) {
						if(i==0) $('#iplist').html('');
						if(i%2 == 0) {
							var style = '';
						} else {
							var style = 'background-color: #f6f6f6';
						}
						$('#iplist').append('<tr><td>' + (i + 1) + '</td><td style="' + style + '">' + ips[i] + '<div style="float: right; width: 16px;"><img src="http://www.abload.de/img/icon.xjyyo0.png" width="12" height="12" onclick="item_del(' + i + ')" style="cursor: pointer;" title="Delete this IP" /></div></td></tr>');
					}
				}
				
				function item_del(itemid) {
					var items = $('#ips').html();
					items = items.split("\n");
					var new_items = null;
					
					for(i=0; i < items.length; i++) {
						if(i == itemid) {
							
						} else {
							if(new_items == null) {
								new_items = items[i];
							} else {
								new_items = new_items + '\n' + items[i];
							}
						}
					}
					
					$('#ips').html('');
					$('#ips').html(new_items);
					
					reloadlist();
				}
				
				function check_submit(key) {
					if(key == 13) {
						$('#ips').append('\n' + $('#addip').val());
						$('#addip').val('');
						reloadlist();
					}
				}
				
				$(document).ready(function() {
					$('#addipbutton').click(function() {
						$('#ips').append('\n' + $('#addip').val());
						$('#addip').val('');
						reloadlist();
					});
					
					reloadlist();
				});
				-->
			</script>
            <div id="icon-themes" class="icon32"></div><h2>IP Blocker - Control Panel</h2><br />
            
            	<table>
                    <tr>
                        <td>
                            <table class="widefat">
                            <thead>
                                <tr>
                                    <th width="20px;">#</th>
                                    <th>Blocked IP's</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Blocked IP's</th>
                                </tr>
                            </tfoot>
                            <tbody id="iplist">
                            	<tr>
                                	<td colspan="2"><img src="http://www.abload.de/img/ajax-loaderapj3o.gif" /> loading...</td>
                                </tr>
                            </tbody>
                            <tbody>
                            	<tr>
                                	<td colspan="2" style="background-color: #e8e8e8;">
                                        <div style="float: left; padding-top: 3px;">Add IP:</div> 
                                        <input type="button" id="addipbutton" class="button-secondary" value="Add" style="float: right;" />
                                        <input type="text" id="addip" style="float: right; width: 75%;" onkeypress="check_submit(event.which);" />
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        	
           					<form method="post" action="admin.php?page=ip_blocker">
                            <textarea name="ips" id="ips" style="width: 450px; height: 0px; visibility: hidden;"><?php echo $ips; ?></textarea>
                            <h3>Blocked Page - Source (HTML)</h3>
                            <textarea name="page" style="width: 450px; height: 300px;"><?php echo $page; ?></textarea>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                </p>
            
            </form>
        </div>
	
	<?PHP }
} else {
	function check_ip($ip) {
		extract(get_option('ip-blocker-opts'));
		$ips = nl2br($ips);
		$ips = explode('<br />', $ips);
		
		setcookie("blocked_status", "unblocked", 0);
		
		for($i=0; $i < count($i); $i++) {
			if(trim($ips[$i]) == $_SERVER['REMOTE_ADDR']) {
				setcookie("blocked_status", "blocked", 0);
				
				if(is_null($page)) $page = $default['page'];
				$page = str_replace('\\', '', $page);
				echo $page;
				exit;
			}
		}
		
		if($_COOKIE['blocked_status'] == 'blocked') {
			if(is_null($page)) $page = $default['page'];
			$page = str_replace('\\', '', $page);
			echo $page;
			exit;
		}
		
		return;
	}
	
	check_ip($_SERVER['REMOTE_ADDR']);
}
?>