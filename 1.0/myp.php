<?php
/*
Plugin Name: IP Blocker
Plugin URI: http://www.fabian-jocks.de/wordpress-ip-blocker/
Description: Deutsch: Mit diesem Plugin lassen sich IPs von der Seite ausschlie&szlig;en und &uuml;ber ein interaktives Administrator Interface kontrollieren. English: With this plugin you can exclude IPs of the page and control over an interactive administrative interface.
Version: 1.0
Author: Fabian Jocks
Author URI: http://www.fabian-jocks.de/
License: GPL
Stable Tag: 1.0
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
            <h2>IP Blocker</h2>
            
            <form method="post" action="admin.php?page=ip_blocker">
            	<table>
                    <tr>
                        <td>
                            IP's <sup>Nur eine IP pro Zeile</sup><br />
                            <textarea name="ips" style="width: 450px; height: 300px;"><?php echo $ips; ?></textarea>
                        </td>
                        <td>
                            Source (HTML)<br />
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
		
		for($i=0; $i < count($i); $i++) {
			if(trim($ips[$i]) == $_SERVER['REMOTE_ADDR']) {
				if(is_null($page)) $page = $default['page'];
				$page = str_replace('\\', '', $page);
				echo $page;
				exit;
			}
		}
		
		return;
	}
	
	check_ip($_SERVER['REMOTE_ADDR']);
}
?>