<?php
/*  Copyright 2014 PressLabs SRL <ping@presslabs.com>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Gitium_Submenu_Commits extends Gitium_Menu {

	public function __construct() {
		parent::__construct( $this->gitium_menu_slug, $this->commits_menu_slug );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public function admin_menu() {
		$submenu_hook = add_submenu_page(
			$this->menu_slug,
			__( 'Git Commits', 'gitium' ),
			__( 'Commits', 'gitium' ),
			'manage_options',
			$this->submenu_slug,
			array( $this, 'page' )
		);
		new Gitium_Help( $submenu_hook, 'GITIUM_COMMITS' );
	}

	public function page() {
		$git = $this->git; ?>
		<div class="wrap">
		<h2><?php printf( __( 'Last %s commits', 'gitium' ), GITIUM_LAST_COMMITS ); ?></h2>

		<table class="wp-list-table widefat plugins">
		<thead>
		<tr>
			<th scope="col"><?php _e( 'Commits', 'gitium' ); ?></th>
			<th scope="col"></th>
		</tr>
		</thead>
		<tbody>
		<?php

		$last_commits = $git->get_last_commits( GITIUM_LAST_COMMITS );
		$counter = 0;
		foreach ( $last_commits as $commit_id => $data ) {
			$counter++;
			$committer = '';
			$committers_avatar = '';
			unset( $committer_name );
			extract( $data );
			if ( isset( $committer_name ) ) {
				$committer         = "<span title='$committer_email'> -> $committer_name " . sprintf( __( 'committed %s ago', 'gitium' ), human_time_diff( strtotime( $committer_date ) ) ) . '</span>';
				$committers_avatar = '<div style="position:absolute; left:30px; border: 1px solid white; background:white; height:17px; top:30px; border-radius:2px">' . get_avatar( $committer_email, 16 ) . '</div>';
			}
			?>
			<tr<?php if ( 0 != $counter % 2 ) { echo ' class="active"'; } else { echo ' class="inactive"'; } ?>>
			<td style="position:relative">
				<div style="float:left; width:auto; height:auto; padding-left:2px; padding-right:5px; padding-top:2px; margin-right:5px; border-radius:2px"><?php echo get_avatar( $author_email, 32 ); ?></div>
				<?php echo $committers_avatar; ?>
				<div style="float:left; width:auto; height:auto;"><strong><?php echo esc_html( $subject ); ?></strong><br />
				<span title="<?php echo esc_attr( $author_email ); ?>"><?php echo $author_name . ' ' . sprintf( __( 'authored %s ago', 'gitium' ), human_time_diff( strtotime( $author_date ) ) ); ?></span><?php echo $committer; ?></div>
			</td>
			<td><p style="padding-top:8px"><?php echo esc_html( $commit_id ); ?></p></td>
			</tr>
		<?php } ?>
		</tbody>
		</table>

		</div>
		<?php
	}
}