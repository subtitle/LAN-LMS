<?php
/**
 * A plugin that uses flowplayer to embed videos referenced in markdown
 *
 * @author Ben Overholts
 * @link http://www.benoverholts.com/
 * @license http://opensource.org/licenses/MIT
 */
 
class Pico_Flowplayer {

	public function __construct() {
		$plugin_path = dirname(__FILE__);
		$this->path = $plugin_path;
	}

	public function config_loaded(&$settings) {
		$this->theme = $settings['theme'];
	}

	public function file_meta(&$meta)
	{
		$this->subject = $meta['subject'];
	}

	public function content_parsed(&$content)
	{
		$videotitle = "";
		$this->lines = explode("<p>", $content);

		for($i = 0; $i < count($this->lines); ++$i) {
			if (preg_match("/^!!/", $this->lines[$i])) {
    			$videotitle = $this->lines[$i];
    			$videotitle = str_replace("!!", "", $videotitle);
    			$videotitle = str_replace("</p>", "", $videotitle);
    			$this->videopath = str_replace(" ", "_", strtolower($this->subject));
				$this->videopath = "/content/" . $this->videopath . "/media/";
				$this->videopath = $this->videopath . $videotitle;


				/* Need to re-add $config['base-url'] to the path, once config.php problems are solved */
				$this->lines[$i] = '<div class="flowplayer">
											<video>
												<source type="video/mp4" src="' . $this->videopath . '">
											</video>
									</div>';
			}
		}

		$content = implode("<p>", $this->lines);
	}
}