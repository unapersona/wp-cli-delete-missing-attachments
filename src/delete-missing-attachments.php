<?php


	// Only run through WP CLI.
	if (!defined('WP_CLI')) {
		return;
	}

	/**
	 * Media Restore command for WP CLI.
	 */
	class Delete_Missing_Attachments extends WP_CLI_Command
	{


		/**
		 * Delete missing attachments from the media libray using WP CLI.
		 *
		 *
		 * ### Options
		 *
		 * #### `[--dry-run]`
		 * Run the media library scan and show report, but don't delete attachments.
		 *
		 *
		 * ### Examples
		 *
		 *     wp media delete-missing --dry-run
		 *
		 * @param array $args
		 * @param array $assoc_args
		 */
		public function __invoke(array $args = [], array $assoc_args = [])
		{

			$attachments = (new \WP_Query([
				'post_type' => 'attachment',
				'post_status' => 'any',
				//'posts_per_page' => 10,
				'nopaging' => true,
				'fields' => 'ids',
				'order' => 'DESC',
				'orderby' => 'date'
			]))->get_posts();

			$dry = array_key_exists('dry-run', $assoc_args);
			$dir = wp_upload_dir()['basedir'];
			$total = count($attachments);

			WP_CLI::line(sprintf('Scanning %d attachments', $total));
			$progress = \WP_CLI\Utils\make_progress_bar('Scanning', $total);

			$deleted = 0;
			foreach ($attachments as $attachment) {
				$file = get_post_meta($attachment, '_wp_attached_file', true);
				$filepath = trailingslashit($dir) . $file;
				$progress->tick(1, $file);
				if (!file_exists($filepath)) {
					$deleted++;
					if (!$dry) {
						wp_delete_attachment($attachment, true);
					}
				}
			}

			$progress->finish();

			\WP_CLI\Utils\format_items('table', [(object)[
				'Total' => $total,
				'Deleted' => $deleted,
				'Exists' => $total - $deleted
			]], ['Total', 'Deleted', 'Exists']);

		}

	}

	WP_CLI::add_command('media delete-missing', 'Delete_Missing_Attachments');
