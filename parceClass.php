<?php

class channelSampling {
	
	function __construct() {
		echo "class construct <br />";
	}

	private $headersMap = [
		'prt.Participant' => 0,
		'Duration' => 0,
		'In FPS' => 0,
		'In Rate' => 0,
		'Out FPS' => 0,
		'Out Rate' => 0,
		'In dim' => 0,
		'Out dim' => 0,
		'Error' => 0,
		'CurPla' => 0,
		'Limited By' => 0,
		'Audio' => 0,
		'Video' => 0,
		'Vformat' => 0,
		'WannaS' => 0,
		'Exited' => 0,
		'WSA' => 0,
		'MOH' => 0,
		'CPUOVRHD' => 0,
		'CumLost' => 0,
		'FracLost' => 0,
		'LPercent' => 0,
		'RxLost' => 0,
		'RxFracLost' => 0,
		'Aformat' => 0,
		'AudioIN' => 0,
		'VideoIN' => 0,
		'Encrypt' => 0,
		'H239In FP' => 0,
		'H239In Rate' => 0,
		'H239Out F' => 0,
		'H239Out R' => 0,
		'H239In dim' => 0,
		'H239Out dim' => 0,
		'H239 Format' => 0,
		'SL' => 0,
		'Watermark' => 0,
		'PVP' => 0,
		'FEC' => 0,
		'MSRV' => 0
	];

	private $source = [];
	private $partition = [];

	public function readFile($filePath='') {
		if(file_exists($filePath) && is_readable($filePath)){
			$data = file($filePath);
			foreach ($data as $key => $value) {
				if(preg_match("/---[A-Z\s]+---/", $value) || $value === "\n") unset($data[$key]);
			}
			$this->source = $data;
			return true;
		} else {
			return false;
		}
	}

	private function utfCleaner($string='') {
		// return mb_convert_encoding($string, 'UTF-8', 'UTF-8');
		// return iconv("UTF-8", "UTF-8//IGNORE", $string);
		// return preg_replace("/[^\\x00-\\xFFFF]/", "", $string);
		$changeString = preg_replace( '/[^[:print:]]/', '', $string);
		return str_replace('_', ' ', $changeString);
	}

	private function mapRifling($string='', $map=[]) {
		$discret = [];
		$left = 0;
		foreach ($map as $name => $position) {
			$length = $position - $left;
			$piece = trim(substr($string, $left, $length));
			$discret[$name] = ($name == 'Watermark') ? $this->utfCleaner($piece) : $piece;
			$left = $position;
		}
		return $discret;
	}

	public function convProcess() {
		$first = true;
		$partition=[];
		foreach ($this->source as $line) {
			if ($first) {
				$save = false;
				foreach ($this->headersMap as $key_h => $header) {
					if ($save) $this->headersMap[$save] = strpos($line, $key_h);
					$save = $key_h;
				}
				$this->headersMap[$save] = strlen($line);
				$first = false;
			} else {
				$this->partition[] = $this->mapRifling($line, $this->headersMap);
			}
		}
		return $this->partition;
	}

	public function getAll() {
		return $this->partition;
	}

	public function getLine($line=0) {
		return $this->partition[$line];
	}

	public function getParameter($line=0, $name='') {
		return $this->partition[$line][$name];
	}

	function __destruct() {
		echo "<br /> class destruct";
	}

}


?>