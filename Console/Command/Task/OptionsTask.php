<?php

class OptionsTask extends Shell {

	public function menuHeader($header, $depth = 4) {
		$this->out(str_repeat('-', $depth) . " $header " . str_repeat('-', 63 - strlen($header) - 2 - $depth));
	}

	public function select($options, $prompt = null, $default = null) {
		$valid = false;
		$max = count($options);
		while (!$valid) {
			$len = strlen(count($options) + 1);
			foreach ($options as $i => $option) {
				$this->out(sprintf("%${len}d. %s", $i + 1, $option));
			}
			if (empty($prompt)) {
				$prompt = __d('cake_console', 'Make a selection from the choices above');
			}
			$choice = $this->in($prompt, null, $default);
			if (intval($choice) > 0 && intval($choice) <= $max) {
				$valid = true;
			}
		}
		return $choice - 1;
	}
}
